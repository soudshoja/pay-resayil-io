<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\AgentAuthorizedPhone;
use App\Models\Client;
use App\Models\PaymentRequest;
use App\Models\TransactionNote;
use App\Models\User;
use App\Models\WhatsappKeyword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ClientController extends Controller
{
    /**
     * Client Dashboard
     */
    public function dashboard()
    {
        $client = auth()->user()->client;

        $stats = [
            'total_agents' => Agent::where('client_id', $client->id)->count(),
            'active_agents' => Agent::where('client_id', $client->id)->where('is_active', true)->count(),
            'total_transactions' => PaymentRequest::where('client_id', $client->id)->count(),
            'total_revenue' => PaymentRequest::where('client_id', $client->id)->where('status', 'paid')->sum('amount'),
            'pending_payments' => PaymentRequest::where('client_id', $client->id)->where('status', 'pending')->count(),
        ];

        $recentTransactions = PaymentRequest::where('client_id', $client->id)
            ->with(['agent'])
            ->latest()
            ->take(10)
            ->get();

        $recentAgents = Agent::where('client_id', $client->id)
            ->latest()
            ->take(5)
            ->get();

        return view('client.dashboard', compact('client', 'stats', 'recentTransactions', 'recentAgents'));
    }

    /**
     * List Agents
     */
    public function agents(Request $request)
    {
        $client = auth()->user()->client;

        $query = Agent::where('client_id', $client->id)
            ->with(['salesPerson', 'authorizedPhones']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('company_name', 'like', "%{$search}%")
                  ->orWhere('iata_number', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('sales_person')) {
            $query->where('sales_person_id', $request->sales_person);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $agents = $query->latest()->paginate(20);

        $salesPersons = User::where('client_id', $client->id)
            ->where('role', User::ROLE_SALES_PERSON)
            ->get();

        return view('client.agents.index', compact('agents', 'salesPersons'));
    }

    /**
     * Show Create Agent Form
     */
    public function createAgent()
    {
        $client = auth()->user()->client;
        $salesPersons = User::where('client_id', $client->id)
            ->where('role', User::ROLE_SALES_PERSON)
            ->where('is_active', true)
            ->get();

        return view('client.agents.create', compact('salesPersons'));
    }

    /**
     * Store New Agent
     */
    public function storeAgent(Request $request)
    {
        $client = auth()->user()->client;

        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'iata_number' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'accountant_whatsapp' => 'nullable|string|max:20',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'sales_person_id' => 'nullable|exists:users,id',
            'notes' => 'nullable|string|max:1000',
        ]);

        $validated['client_id'] = $client->id;
        $validated['is_active'] = true;

        $agent = Agent::create($validated);

        return redirect()->route('client.agents.show', $agent)
            ->with('success', __('messages.agent_created'));
    }

    /**
     * Show Agent Details
     */
    public function showAgent(Agent $agent)
    {
        $this->authorizeAgent($agent);

        $agent->load(['salesPerson', 'authorizedPhones', 'paymentRequests' => function ($q) {
            $q->latest()->take(10);
        }]);

        return view('client.agents.show', compact('agent'));
    }

    /**
     * Edit Agent Form
     */
    public function editAgent(Agent $agent)
    {
        $this->authorizeAgent($agent);

        $client = auth()->user()->client;
        $salesPersons = User::where('client_id', $client->id)
            ->where('role', User::ROLE_SALES_PERSON)
            ->where('is_active', true)
            ->get();

        return view('client.agents.edit', compact('agent', 'salesPersons'));
    }

    /**
     * Update Agent
     */
    public function updateAgent(Request $request, Agent $agent)
    {
        $this->authorizeAgent($agent);

        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'iata_number' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'accountant_whatsapp' => 'nullable|string|max:20',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'sales_person_id' => 'nullable|exists:users,id',
            'notes' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ]);

        $agent->update($validated);

        return redirect()->route('client.agents.show', $agent)
            ->with('success', __('messages.agent_updated'));
    }

    /**
     * Delete Agent
     */
    public function destroyAgent(Agent $agent)
    {
        $this->authorizeAgent($agent);

        $agent->delete();

        return redirect()->route('client.agents')
            ->with('success', __('messages.agent_deleted'));
    }

    /**
     * Add Authorized Phone to Agent
     */
    public function addAuthorizedPhone(Request $request, Agent $agent)
    {
        $this->authorizeAgent($agent);

        $validated = $request->validate([
            'phone_number' => 'required|string|max:20|unique:agent_authorized_phones,phone_number',
            'full_name' => 'nullable|string|max:255',
        ]);

        $validated['agent_id'] = $agent->id;
        $validated['is_active'] = true;

        AgentAuthorizedPhone::create($validated);

        return back()->with('success', __('messages.phone_added'));
    }

    /**
     * Remove Authorized Phone
     */
    public function removeAuthorizedPhone(AgentAuthorizedPhone $phone)
    {
        $this->authorizeAgent($phone->agent);

        $phone->delete();

        return back()->with('success', __('messages.phone_removed'));
    }

    /**
     * List Sales Persons
     */
    public function salesPersons(Request $request)
    {
        $client = auth()->user()->client;

        $query = User::where('client_id', $client->id)
            ->where('role', User::ROLE_SALES_PERSON)
            ->withCount('managedAgents');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%");
            });
        }

        $salesPersons = $query->latest()->paginate(20);

        return view('client.sales-persons.index', compact('salesPersons'));
    }

    /**
     * Create Sales Person Form
     */
    public function createSalesPerson()
    {
        return view('client.sales-persons.create');
    }

    /**
     * Store Sales Person
     */
    public function storeSalesPerson(Request $request)
    {
        $client = auth()->user()->client;

        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'username' => 'required|string|max:20|unique:users,username',
            'password' => 'required|string|min:8',
        ]);

        User::create([
            'client_id' => $client->id,
            'full_name' => $validated['full_name'],
            'email' => $validated['email'],
            'username' => $validated['username'],
            'password' => Hash::make($validated['password']),
            'role' => User::ROLE_SALES_PERSON,
            'is_active' => true,
            'visible_to_clients' => true,
        ]);

        return redirect()->route('client.sales-persons')
            ->with('success', __('messages.sales_person_created'));
    }

    /**
     * Edit Sales Person
     */
    public function editSalesPerson(User $user)
    {
        $this->authorizeUser($user);

        return view('client.sales-persons.edit', compact('user'));
    }

    /**
     * Update Sales Person
     */
    public function updateSalesPerson(Request $request, User $user)
    {
        $this->authorizeUser($user);

        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8',
            'is_active' => 'boolean',
        ]);

        $user->full_name = $validated['full_name'];
        $user->email = $validated['email'];
        $user->is_active = $validated['is_active'] ?? $user->is_active;

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('client.sales-persons')
            ->with('success', __('messages.sales_person_updated'));
    }

    /**
     * Delete Sales Person
     */
    public function destroySalesPerson(User $user)
    {
        $this->authorizeUser($user);

        // Unassign agents
        Agent::where('sales_person_id', $user->id)->update(['sales_person_id' => null]);

        $user->delete();

        return redirect()->route('client.sales-persons')
            ->with('success', __('messages.sales_person_deleted'));
    }

    /**
     * List Accountants
     */
    public function accountants(Request $request)
    {
        $client = auth()->user()->client;

        $accountants = User::where('client_id', $client->id)
            ->where('role', User::ROLE_ACCOUNTANT)
            ->latest()
            ->paginate(20);

        return view('client.accountants.index', compact('accountants'));
    }

    /**
     * Create Accountant Form
     */
    public function createAccountant()
    {
        return view('client.accountants.create');
    }

    /**
     * Store Accountant
     */
    public function storeAccountant(Request $request)
    {
        $client = auth()->user()->client;

        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'username' => 'required|string|max:20|unique:users,username',
            'password' => 'required|string|min:8',
        ]);

        User::create([
            'client_id' => $client->id,
            'full_name' => $validated['full_name'],
            'email' => $validated['email'],
            'username' => $validated['username'],
            'password' => Hash::make($validated['password']),
            'role' => User::ROLE_ACCOUNTANT,
            'is_active' => true,
            'visible_to_clients' => true,
        ]);

        return redirect()->route('client.accountants')
            ->with('success', __('messages.accountant_created'));
    }

    /**
     * Edit Accountant
     */
    public function editAccountant(User $user)
    {
        $this->authorizeUser($user);

        return view('client.accountants.edit', compact('user'));
    }

    /**
     * Update Accountant
     */
    public function updateAccountant(Request $request, User $user)
    {
        $this->authorizeUser($user);

        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8',
            'is_active' => 'boolean',
        ]);

        $user->full_name = $validated['full_name'];
        $user->email = $validated['email'];
        $user->is_active = $validated['is_active'] ?? $user->is_active;

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('client.accountants')
            ->with('success', __('messages.accountant_updated'));
    }

    /**
     * Delete Accountant
     */
    public function destroyAccountant(User $user)
    {
        $this->authorizeUser($user);

        $user->delete();

        return redirect()->route('client.accountants')
            ->with('success', __('messages.accountant_deleted'));
    }

    /**
     * List Transactions
     */
    public function transactions(Request $request)
    {
        $client = auth()->user()->client;

        $query = PaymentRequest::where('client_id', $client->id)
            ->with(['agent', 'notes' => function ($q) {
                $q->where('visible_to_clients', true)->with('createdBy');
            }]);

        // Apply filters
        if ($request->filled('agent_id')) {
            $query->where('agent_id', $request->agent_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('myfatoorah_invoice_id', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%")
                  ->orWhereHas('agent', function ($q) use ($search) {
                      $q->where('company_name', 'like', "%{$search}%")
                        ->orWhere('iata_number', 'like', "%{$search}%");
                  });
            });
        }

        $transactions = $query->latest()->paginate($request->per_page ?? 20);

        $agents = Agent::where('client_id', $client->id)->get();

        return view('client.transactions.index', compact('transactions', 'agents'));
    }

    /**
     * Export Transactions to CSV
     */
    public function exportTransactions(Request $request)
    {
        $client = auth()->user()->client;

        $query = PaymentRequest::where('client_id', $client->id)
            ->with(['agent']);

        // Apply same filters as list
        if ($request->filled('agent_id')) {
            $query->where('agent_id', $request->agent_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $transactions = $query->latest()->get();

        $csv = "Invoice ID,Agent,Amount,Currency,Status,Created At,Paid At\n";
        foreach ($transactions as $t) {
            $csv .= "\"{$t->myfatoorah_invoice_id}\",";
            $csv .= "\"{$t->agent?->company_name}\",";
            $csv .= "{$t->amount},";
            $csv .= "{$t->currency},";
            $csv .= "{$t->status},";
            $csv .= "{$t->created_at},";
            $csv .= "{$t->paid_at}\n";
        }

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="transactions_' . date('Y-m-d') . '.csv"');
    }

    /**
     * WhatsApp Keywords
     */
    public function keywords(Request $request)
    {
        $client = auth()->user()->client;

        $keywords = WhatsappKeyword::where('client_id', $client->id)
            ->latest()
            ->paginate(20);

        return view('client.keywords.index', compact('keywords'));
    }

    /**
     * Store Keyword
     */
    public function storeKeyword(Request $request)
    {
        $client = auth()->user()->client;

        $validated = $request->validate([
            'keyword' => 'required|string|max:100',
            'action' => 'required|in:payment_request,balance_check,status_check,help',
            'response_template' => 'nullable|string|max:1000',
        ]);

        $validated['client_id'] = $client->id;
        $validated['is_active'] = true;

        WhatsappKeyword::create($validated);

        return back()->with('success', __('messages.keyword_created'));
    }

    /**
     * Toggle Keyword
     */
    public function toggleKeyword(WhatsappKeyword $keyword)
    {
        $this->authorizeKeyword($keyword);

        $keyword->update(['is_active' => !$keyword->is_active]);

        return back()->with('success', __('messages.keyword_updated'));
    }

    /**
     * Delete Keyword
     */
    public function destroyKeyword(WhatsappKeyword $keyword)
    {
        $this->authorizeKeyword($keyword);

        $keyword->delete();

        return back()->with('success', __('messages.keyword_deleted'));
    }

    /**
     * WhatsApp Management
     */
    public function whatsapp()
    {
        $client = auth()->user()->client;

        return view('client.whatsapp', compact('client'));
    }

    /**
     * Client Settings
     */
    public function settings()
    {
        $client = auth()->user()->client;
        $client->load('myfatoorahCredential');

        return view('client.settings', compact('client'));
    }

    /**
     * Update Settings
     */
    public function updateSettings(Request $request)
    {
        $client = auth()->user()->client;

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'whatsapp_number' => 'nullable|string|max:20',
            'company_email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
            'service_fee_type' => 'required|in:fixed,percentage',
            'service_fee_value' => 'required|numeric|min:0',
            'service_fee_payer' => 'required|in:agent,customer',
            'logo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            // Delete old logo
            if ($client->logo_path) {
                Storage::disk('public')->delete($client->logo_path);
            }
            $validated['logo_path'] = $request->file('logo')->store('logos', 'public');
        }

        unset($validated['logo']);
        $client->update($validated);

        // Update MyFatoorah credentials if provided
        if ($request->filled('myfatoorah_api_key')) {
            $client->myfatoorahCredential()->updateOrCreate(
                ['client_id' => $client->id],
                [
                    'api_key' => $request->myfatoorah_api_key,
                    'is_test_mode' => $request->boolean('myfatoorah_test_mode'),
                    'is_active' => true,
                ]
            );
        }

        return back()->with('success', __('messages.settings_updated'));
    }

    /**
     * Authorize agent belongs to client
     */
    private function authorizeAgent(Agent $agent): void
    {
        if ($agent->client_id !== auth()->user()->client_id) {
            abort(403);
        }
    }

    /**
     * Authorize user belongs to client
     */
    private function authorizeUser(User $user): void
    {
        if ($user->client_id !== auth()->user()->client_id) {
            abort(403);
        }
    }

    /**
     * Authorize keyword belongs to client
     */
    private function authorizeKeyword(WhatsappKeyword $keyword): void
    {
        if ($keyword->client_id !== auth()->user()->client_id) {
            abort(403);
        }
    }
}

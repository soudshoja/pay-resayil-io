<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\PaymentRequest;
use Illuminate\Http\Request;

class SalesController extends Controller
{
    /**
     * Sales Person Dashboard
     */
    public function dashboard()
    {
        $user = auth()->user();

        $stats = [
            'total_agents' => Agent::where('sales_person_id', $user->id)->count(),
            'active_agents' => Agent::where('sales_person_id', $user->id)->where('is_active', true)->count(),
            'total_transactions' => PaymentRequest::whereHas('agent', function ($q) use ($user) {
                $q->where('sales_person_id', $user->id);
            })->count(),
            'total_revenue' => PaymentRequest::whereHas('agent', function ($q) use ($user) {
                $q->where('sales_person_id', $user->id);
            })->where('status', 'paid')->sum('amount'),
            'pending_payments' => PaymentRequest::whereHas('agent', function ($q) use ($user) {
                $q->where('sales_person_id', $user->id);
            })->where('status', 'pending')->count(),
        ];

        $recentTransactions = PaymentRequest::whereHas('agent', function ($q) use ($user) {
            $q->where('sales_person_id', $user->id);
        })->with(['agent'])->latest()->take(10)->get();

        return view('sales.dashboard', compact('stats', 'recentTransactions'));
    }

    /**
     * List My Agents
     */
    public function agents(Request $request)
    {
        $user = auth()->user();

        $query = Agent::where('sales_person_id', $user->id)
            ->with(['authorizedPhones']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('company_name', 'like', "%{$search}%")
                  ->orWhere('iata_number', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $agents = $query->latest()->paginate(20);

        return view('sales.agents.index', compact('agents'));
    }

    /**
     * Show Agent Details
     */
    public function showAgent(Agent $agent)
    {
        $this->authorizeAgent($agent);

        $agent->load(['authorizedPhones', 'paymentRequests' => function ($q) {
            $q->latest()->take(20);
        }]);

        return view('sales.agents.show', compact('agent'));
    }

    /**
     * List My Transactions
     */
    public function transactions(Request $request)
    {
        $user = auth()->user();

        $query = PaymentRequest::whereHas('agent', function ($q) use ($user) {
            $q->where('sales_person_id', $user->id);
        })->with(['agent']);

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
                  ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        $transactions = $query->latest()->paginate($request->per_page ?? 20);

        $agents = Agent::where('sales_person_id', $user->id)->get();

        return view('sales.transactions.index', compact('transactions', 'agents'));
    }

    /**
     * Export Transactions
     */
    public function exportTransactions(Request $request)
    {
        $user = auth()->user();

        $query = PaymentRequest::whereHas('agent', function ($q) use ($user) {
            $q->where('sales_person_id', $user->id);
        })->with(['agent']);

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
            ->header('Content-Disposition', 'attachment; filename="my_transactions_' . date('Y-m-d') . '.csv"');
    }

    /**
     * Authorize agent belongs to this sales person
     */
    private function authorizeAgent(Agent $agent): void
    {
        if ($agent->sales_person_id !== auth()->id()) {
            abort(403, 'This agent is not assigned to you.');
        }
    }
}

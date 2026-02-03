<?php

namespace App\Http\Controllers;

use App\Models\PaymentRequest;
use App\Models\TransactionNote;
use Illuminate\Http\Request;

class AccountantController extends Controller
{
    /**
     * Accountant Dashboard
     */
    public function dashboard()
    {
        $client = auth()->user()->client;

        $stats = [
            'total_transactions' => PaymentRequest::where('client_id', $client->id)->count(),
            'total_revenue' => PaymentRequest::where('client_id', $client->id)->where('status', 'paid')->sum('amount'),
            'pending_payments' => PaymentRequest::where('client_id', $client->id)->where('status', 'pending')->count(),
            'today_transactions' => PaymentRequest::where('client_id', $client->id)->whereDate('created_at', today())->count(),
            'today_revenue' => PaymentRequest::where('client_id', $client->id)->where('status', 'paid')->whereDate('paid_at', today())->sum('amount'),
        ];

        $recentTransactions = PaymentRequest::where('client_id', $client->id)
            ->with(['agent', 'notes' => function ($q) {
                $q->where('visible_to_clients', true)->latest();
            }])
            ->latest()
            ->take(20)
            ->get();

        return view('accountant.dashboard', compact('stats', 'recentTransactions'));
    }

    /**
     * List All Client Transactions
     */
    public function transactions(Request $request)
    {
        $client = auth()->user()->client;

        $query = PaymentRequest::where('client_id', $client->id)
            ->with(['agent', 'notes' => function ($q) {
                $q->where('visible_to_clients', true)->with('createdBy')->latest();
            }]);

        // Apply filters
        if ($request->filled('agent_id')) {
            $query->where('agent_id', $request->agent_id);
        }

        if ($request->filled('status')) {
            $statuses = is_array($request->status) ? $request->status : [$request->status];
            $query->whereIn('status', $statuses);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('amount_min')) {
            $query->where('amount', '>=', $request->amount_min);
        }

        if ($request->filled('amount_max')) {
            $query->where('amount', '<=', $request->amount_max);
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

        // Date presets
        if ($request->filled('date_preset')) {
            switch ($request->date_preset) {
                case 'today':
                    $query->whereDate('created_at', today());
                    break;
                case 'yesterday':
                    $query->whereDate('created_at', today()->subDay());
                    break;
                case 'last_7_days':
                    $query->where('created_at', '>=', now()->subDays(7));
                    break;
                case 'last_30_days':
                    $query->where('created_at', '>=', now()->subDays(30));
                    break;
                case 'this_month':
                    $query->whereMonth('created_at', now()->month)
                          ->whereYear('created_at', now()->year);
                    break;
                case 'last_month':
                    $query->whereMonth('created_at', now()->subMonth()->month)
                          ->whereYear('created_at', now()->subMonth()->year);
                    break;
            }
        }

        $transactions = $query->latest()->paginate($request->per_page ?? 20);

        $agents = \App\Models\Agent::where('client_id', $client->id)->get();

        return view('accountant.transactions.index', compact('transactions', 'agents'));
    }

    /**
     * Show Transaction Details with Notes
     */
    public function showTransaction(PaymentRequest $payment)
    {
        $this->authorizePayment($payment);

        $payment->load(['agent', 'notes' => function ($q) {
            $q->where('visible_to_clients', true)->with('createdBy')->latest();
        }]);

        return view('accountant.transactions.show', compact('payment'));
    }

    /**
     * Add Note to Transaction
     */
    public function addNote(Request $request, PaymentRequest $payment)
    {
        $this->authorizePayment($payment);

        $validated = $request->validate([
            'note' => 'required|string|max:2000',
            'note_type' => 'required|in:general,status_update,issue,resolution',
        ]);

        TransactionNote::create([
            'payment_request_id' => $payment->id,
            'created_by_user_id' => auth()->id(),
            'note' => $validated['note'],
            'note_type' => $validated['note_type'],
            'visible_to_clients' => true, // Accountant notes are visible to client
        ]);

        return back()->with('success', __('messages.note_added'));
    }

    /**
     * Export Transactions
     */
    public function exportTransactions(Request $request)
    {
        $client = auth()->user()->client;

        $query = PaymentRequest::where('client_id', $client->id)
            ->with(['agent', 'notes' => function ($q) {
                $q->where('visible_to_clients', true);
            }]);

        // Apply same filters as list
        if ($request->filled('agent_id')) {
            $query->where('agent_id', $request->agent_id);
        }

        if ($request->filled('status')) {
            $statuses = is_array($request->status) ? $request->status : [$request->status];
            $query->whereIn('status', $statuses);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $transactions = $query->latest()->get();

        $csv = "Invoice ID,Agent,IATA,Amount,Service Fee,Total,Currency,Status,Customer Phone,Created At,Paid At,Notes\n";
        foreach ($transactions as $t) {
            $notes = $t->notes->pluck('note')->implode(' | ');
            $csv .= "\"{$t->myfatoorah_invoice_id}\",";
            $csv .= "\"{$t->agent?->company_name}\",";
            $csv .= "\"{$t->agent?->iata_number}\",";
            $csv .= "{$t->amount},";
            $csv .= "{$t->service_fee},";
            $csv .= "{$t->total_amount},";
            $csv .= "{$t->currency},";
            $csv .= "{$t->status},";
            $csv .= "\"{$t->customer_phone}\",";
            $csv .= "{$t->created_at},";
            $csv .= "{$t->paid_at},";
            $csv .= "\"" . str_replace('"', '""', $notes) . "\"\n";
        }

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="transactions_' . date('Y-m-d') . '.csv"');
    }

    /**
     * Authorize payment belongs to client
     */
    private function authorizePayment(PaymentRequest $payment): void
    {
        if ($payment->client_id !== auth()->user()->client_id) {
            abort(403);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\PaymentRequest;
use App\Models\User;
use App\Models\WhatsappLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Show dashboard
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $clientId = $user->client_id;

        // Get statistics based on user role
        if ($user->isSuperAdmin() || $user->isPlatformOwner()) {
            $stats = $this->getSuperAdminStats();
        } else {
            $stats = $this->getAgencyStats($clientId);
        }

        // Get recent payments
        $recentPayments = PaymentRequest::with(['agent', 'agency'])
            ->when(!$user->isSuperAdmin() && !$user->isPlatformOwner(), function ($query) use ($clientId) {
                $query->where('client_id', $clientId);
            })
            ->latest()
            ->take(10)
            ->get();

        // Get chart data (last 7 days)
        $chartData = $this->getChartData($clientId, $user->isSuperAdmin() || $user->isPlatformOwner());

        return view('dashboard.index', compact('stats', 'recentPayments', 'chartData'));
    }

    /**
     * Get super admin statistics
     */
    private function getSuperAdminStats(): array
    {
        $today = now()->startOfDay();
        $thisMonth = now()->startOfMonth();

        return [
            'total_agencies' => \App\Models\Agency::count(),
            'active_agencies' => \App\Models\Agency::active()->count(),
            'total_users' => User::count(),
            'total_payments' => PaymentRequest::count(),
            'payments_today' => PaymentRequest::whereDate('created_at', $today)->count(),
            'paid_today' => PaymentRequest::paid()->whereDate('paid_at', $today)->count(),
            'revenue_today' => PaymentRequest::paid()->whereDate('paid_at', $today)->sum('amount'),
            'revenue_month' => PaymentRequest::paid()->where('paid_at', '>=', $thisMonth)->sum('amount'),
            'pending_payments' => PaymentRequest::pending()->count(),
            'messages_today' => WhatsappLog::whereDate('created_at', $today)->count(),
        ];
    }

    /**
     * Get client statistics
     */
    private function getAgencyStats(?int $clientId): array
    {
        $today = now()->startOfDay();
        $thisMonth = now()->startOfMonth();

        return [
            'total_team' => User::where('client_id', $clientId)->count(),
            'total_payments' => PaymentRequest::where('client_id', $clientId)->count(),
            'payments_today' => PaymentRequest::where('client_id', $clientId)
                ->whereDate('created_at', $today)->count(),
            'paid_today' => PaymentRequest::where('client_id', $clientId)
                ->paid()->whereDate('paid_at', $today)->count(),
            'revenue_today' => PaymentRequest::where('client_id', $clientId)
                ->paid()->whereDate('paid_at', $today)->sum('amount'),
            'revenue_month' => PaymentRequest::where('client_id', $clientId)
                ->paid()->where('paid_at', '>=', $thisMonth)->sum('amount'),
            'pending_payments' => PaymentRequest::where('client_id', $clientId)
                ->pending()->count(),
            'messages_today' => WhatsappLog::where('client_id', $clientId)
                ->whereDate('created_at', $today)->count(),
        ];
    }

    /**
     * Get chart data for last 7 days
     */
    private function getChartData(?int $clientId, bool $isSuperAdmin): array
    {
        $days = collect(range(6, 0))->map(function ($daysAgo) {
            return now()->subDays($daysAgo)->format('Y-m-d');
        });

        $query = PaymentRequest::query()
            ->when(!$isSuperAdmin && $clientId, function ($q) use ($clientId) {
                $q->where('client_id', $clientId);
            })
            ->whereDate('created_at', '>=', now()->subDays(7))
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN status = "paid" THEN 1 ELSE 0 END) as paid'),
                DB::raw('SUM(CASE WHEN status = "paid" THEN amount ELSE 0 END) as revenue')
            )
            ->groupBy('date')
            ->get()
            ->keyBy('date');

        return [
            'labels' => $days->map(fn($d) => now()->parse($d)->format('M d'))->toArray(),
            'total' => $days->map(fn($d) => $query->get($d)?->total ?? 0)->toArray(),
            'paid' => $days->map(fn($d) => $query->get($d)?->paid ?? 0)->toArray(),
            'revenue' => $days->map(fn($d) => (float) ($query->get($d)?->revenue ?? 0))->toArray(),
        ];
    }
}

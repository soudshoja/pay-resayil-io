@extends('accountant.layout')

@section('title', __('messages.nav.dashboard'))

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div>
        <h1 class="text-2xl font-bold text-white">{{ __('messages.auth.welcome_back', ['name' => auth()->user()->full_name]) }}</h1>
        <p class="text-gray-400">{{ __('messages.roles.accountant') }} Dashboard</p>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="glass-card rounded-xl p-6">
            <p class="text-gray-400 text-sm">{{ __('messages.dashboard.revenue_today') }}</p>
            <p class="text-3xl font-bold text-green-400 mt-2">{{ number_format($stats['today_revenue'], 3) }}</p>
        </div>
        <div class="glass-card rounded-xl p-6">
            <p class="text-gray-400 text-sm">{{ __('messages.dashboard.paid_today') }}</p>
            <p class="text-3xl font-bold text-white mt-2">{{ $stats['today_transactions'] }}</p>
        </div>
        <div class="glass-card rounded-xl p-6">
            <p class="text-gray-400 text-sm">{{ __('messages.dashboard.pending') }}</p>
            <p class="text-3xl font-bold text-yellow-400 mt-2">{{ $stats['pending_payments'] }}</p>
        </div>
        <div class="glass-card rounded-xl p-6">
            <p class="text-gray-400 text-sm">{{ __('messages.dashboard.revenue_month') }}</p>
            <p class="text-3xl font-bold text-purple-400 mt-2">{{ number_format($stats['month_revenue'], 3) }}</p>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="glass-card rounded-xl p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-white">{{ __('messages.recent_transactions') }}</h2>
            <a href="{{ route('accountant.transactions') }}" class="text-purple-400 hover:text-purple-300 text-sm">{{ __('messages.view_all') }}</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="border-b border-gray-800">
                    <tr>
                        <th class="pb-3 text-left text-xs font-semibold text-gray-400 uppercase">{{ __('messages.payment.invoice_id') }}</th>
                        <th class="pb-3 text-left text-xs font-semibold text-gray-400 uppercase">{{ __('messages.payment.agent') }}</th>
                        <th class="pb-3 text-right text-xs font-semibold text-gray-400 uppercase">{{ __('messages.payment.amount') }}</th>
                        <th class="pb-3 text-left text-xs font-semibold text-gray-400 uppercase">{{ __('messages.payments.status') }}</th>
                        <th class="pb-3 text-left text-xs font-semibold text-gray-400 uppercase">{{ __('messages.payments.date') }}</th>
                        <th class="pb-3 text-right text-xs font-semibold text-gray-400 uppercase">{{ __('messages.common.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800">
                    @forelse($recentTransactions as $payment)
                        <tr>
                            <td class="py-3 font-mono text-sm text-white">{{ $payment->myfatoorah_invoice_id }}</td>
                            <td class="py-3 text-gray-300">{{ $payment->agent?->company_name ?: '-' }}</td>
                            <td class="py-3 text-right font-semibold text-white">{{ number_format($payment->amount, 3) }} {{ $payment->currency }}</td>
                            <td class="py-3">
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-yellow-500/20 text-yellow-400',
                                        'paid' => 'bg-green-500/20 text-green-400',
                                        'failed' => 'bg-red-500/20 text-red-400',
                                        'expired' => 'bg-gray-500/20 text-gray-400',
                                    ];
                                @endphp
                                <span class="px-2 py-1 text-xs rounded-full {{ $statusColors[$payment->status] ?? 'bg-gray-500/20 text-gray-400' }}">{{ __('messages.status.' . $payment->status) }}</span>
                            </td>
                            <td class="py-3 text-gray-300 text-sm">{{ $payment->created_at->format('d M, H:i') }}</td>
                            <td class="py-3 text-right">
                                <a href="{{ route('accountant.transactions.show', $payment) }}" class="text-purple-400 hover:text-purple-300 text-sm">{{ __('messages.common.view') }}</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-gray-400">{{ __('messages.no_transactions') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

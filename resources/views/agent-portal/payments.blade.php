@extends('agent-portal.layout')

@section('title', __('messages.nav.payments'))

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div>
        <h1 class="text-2xl font-bold text-white">{{ __('messages.nav.payments') }}</h1>
        <p class="text-gray-400">Your payment history ({{ $payments->total() }})</p>
    </div>

    <!-- Filters -->
    <div class="glass-card rounded-xl p-4">
        <form method="GET" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('messages.common.search') }}" class="w-full px-4 py-2 bg-dark-800 border border-gray-700 rounded-lg text-white focus:border-purple-500 focus:outline-none">
            </div>
            <div class="min-w-[120px]">
                <select name="status" class="w-full px-4 py-2 bg-dark-800 border border-gray-700 rounded-lg text-white focus:border-purple-500 focus:outline-none">
                    <option value="">{{ __('messages.payments.status') }}</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>{{ __('messages.status.pending') }}</option>
                    <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>{{ __('messages.status.paid') }}</option>
                    <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>{{ __('messages.status.failed') }}</option>
                </select>
            </div>
            <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                {{ __('messages.common.filter') }}
            </button>
        </form>
    </div>

    <!-- Table -->
    <div class="glass-card rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-dark-800">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">{{ __('messages.payment.invoice_id') }}</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">{{ __('messages.payments.customer_phone') }}</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-400 uppercase tracking-wider">{{ __('messages.payment.amount') }}</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">{{ __('messages.payments.status') }}</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">{{ __('messages.payments.date') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800">
                    @forelse($payments as $payment)
                        <tr class="hover:bg-dark-800/50 transition-colors">
                            <td class="px-6 py-4 font-mono text-sm text-white">{{ $payment->myfatoorah_invoice_id }}</td>
                            <td class="px-6 py-4 text-gray-300 font-mono text-sm">{{ $payment->customer_phone ?: '-' }}</td>
                            <td class="px-6 py-4 text-right font-semibold text-white">{{ number_format($payment->amount, 3) }} {{ $payment->currency }}</td>
                            <td class="px-6 py-4">
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
                            <td class="px-6 py-4 text-gray-300 text-sm">
                                {{ $payment->created_at->format('d M Y, H:i') }}
                                @if($payment->paid_at)
                                    <div class="text-xs text-green-400">Paid: {{ $payment->paid_at->format('d M Y, H:i') }}</div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                                {{ __('messages.no_transactions') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($payments->hasPages())
            <div class="px-6 py-4 border-t border-gray-800">
                {{ $payments->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

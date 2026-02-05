@extends('accountant.layout')

@section('title', __('messages.nav.transactions'))

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-white">{{ __('messages.nav.transactions') }}</h1>
            <p class="text-gray-400">All transactions ({{ $transactions->total() }})</p>
        </div>
        <a href="{{ route('accountant.transactions.export', request()->query()) }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
            </svg>
            {{ __('messages.common.export_csv') }}
        </a>
    </div>

    <!-- Filters -->
    <div class="glass-card rounded-xl p-4">
        <form method="GET" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('messages.common.search') }}" class="w-full px-4 py-2 bg-dark-800 border border-gray-700 rounded-lg text-white focus:border-purple-500 focus:outline-none">
            </div>
            <div class="min-w-[150px]">
                <select name="agent_id" class="w-full px-4 py-2 bg-dark-800 border border-gray-700 rounded-lg text-white focus:border-purple-500 focus:outline-none">
                    <option value="">{{ __('messages.nav.agents') }}</option>
                    @foreach($agents as $agent)
                        <option value="{{ $agent->id }}" {{ request('agent_id') == $agent->id ? 'selected' : '' }}>{{ $agent->company_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="min-w-[120px]">
                <select name="status" class="w-full px-4 py-2 bg-dark-800 border border-gray-700 rounded-lg text-white focus:border-purple-500 focus:outline-none">
                    <option value="">{{ __('messages.payments.status') }}</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>{{ __('messages.status.pending') }}</option>
                    <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>{{ __('messages.status.paid') }}</option>
                    <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>{{ __('messages.status.failed') }}</option>
                </select>
            </div>
            <div class="min-w-[140px]">
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full px-4 py-2 bg-dark-800 border border-gray-700 rounded-lg text-white focus:border-purple-500 focus:outline-none">
            </div>
            <div class="min-w-[140px]">
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full px-4 py-2 bg-dark-800 border border-gray-700 rounded-lg text-white focus:border-purple-500 focus:outline-none">
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
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">{{ __('messages.payment.agent') }}</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">{{ __('messages.payments.customer_phone') }}</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-400 uppercase tracking-wider">{{ __('messages.payment.amount') }}</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">{{ __('messages.payments.status') }}</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">{{ __('messages.payments.date') }}</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-400 uppercase tracking-wider">{{ __('messages.common.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800">
                    @forelse($transactions as $payment)
                        <tr class="hover:bg-dark-800/50 transition-colors">
                            <td class="px-6 py-4 font-mono text-sm text-white">{{ $payment->myfatoorah_invoice_id }}</td>
                            <td class="px-6 py-4">
                                <div>
                                    <div class="font-medium text-white">{{ $payment->agent?->company_name ?: '-' }}</div>
                                    @if($payment->agent?->iata_number)
                                        <div class="text-xs text-gray-500">IATA: {{ $payment->agent->iata_number }}</div>
                                    @endif
                                </div>
                            </td>
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
                            <td class="px-6 py-4 text-gray-300 text-sm">{{ $payment->created_at->format('d M Y, H:i') }}</td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('accountant.transactions.show', $payment) }}" class="text-purple-400 hover:text-purple-300 text-sm">{{ __('messages.common.view') }}</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                                {{ __('messages.no_transactions') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($transactions->hasPages())
            <div class="px-6 py-4 border-t border-gray-800">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

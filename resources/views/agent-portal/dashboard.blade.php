@extends('agent-portal.layout')

@section('title', __('messages.nav.dashboard'))

@section('content')
@php $agent = session('agent'); @endphp
<div class="space-y-6">
    <!-- Header -->
    <div>
        <h1 class="text-2xl font-bold text-white">{{ __('messages.auth.welcome_back', ['name' => $agent->company_name]) }}</h1>
        @if($agent->iata_number)
            <span class="inline-block mt-2 px-3 py-1 bg-purple-500/20 text-purple-400 rounded-full text-sm font-medium">IATA: {{ $agent->iata_number }}</span>
        @endif
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="glass-card rounded-xl p-6">
            <p class="text-gray-400 text-sm">{{ __('messages.stats.total_transactions') }}</p>
            <p class="text-3xl font-bold text-white mt-2">{{ $stats['total_transactions'] }}</p>
        </div>
        <div class="glass-card rounded-xl p-6">
            <p class="text-gray-400 text-sm">{{ __('messages.stats.total_revenue') }}</p>
            <p class="text-3xl font-bold text-purple-400 mt-2">{{ number_format($stats['total_revenue'], 3) }} KWD</p>
        </div>
        <div class="glass-card rounded-xl p-6">
            <p class="text-gray-400 text-sm">{{ __('messages.stats.pending_payments') }}</p>
            <p class="text-3xl font-bold text-yellow-400 mt-2">{{ $stats['pending_payments'] }}</p>
        </div>
        <div class="glass-card rounded-xl p-6">
            <p class="text-gray-400 text-sm">Authorized Phones</p>
            <p class="text-3xl font-bold text-white mt-2">{{ $stats['authorized_phones'] }}</p>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="glass-card rounded-xl p-6">
        <h2 class="text-lg font-semibold text-white mb-4">{{ __('messages.dashboard.quick_actions') }}</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('agent.phones') }}" class="flex items-center p-4 bg-dark-800 rounded-lg hover:bg-dark-800/70 transition-colors">
                <div class="w-12 h-12 bg-purple-500/20 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                </div>
                <div>
                    <p class="font-medium text-white">Add Phone</p>
                    <p class="text-sm text-gray-400">Authorize new phone numbers</p>
                </div>
            </a>
            <a href="{{ route('agent.payments') }}" class="flex items-center p-4 bg-dark-800 rounded-lg hover:bg-dark-800/70 transition-colors">
                <div class="w-12 h-12 bg-green-500/20 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <div>
                    <p class="font-medium text-white">View Payments</p>
                    <p class="text-sm text-gray-400">Check payment history</p>
                </div>
            </a>
            <a href="{{ route('agent.settings') }}" class="flex items-center p-4 bg-dark-800 rounded-lg hover:bg-dark-800/70 transition-colors">
                <div class="w-12 h-12 bg-blue-500/20 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="font-medium text-white">Settings</p>
                    <p class="text-sm text-gray-400">Update your details</p>
                </div>
            </a>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="glass-card rounded-xl p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-white">{{ __('messages.recent_transactions') }}</h2>
            <a href="{{ route('agent.payments') }}" class="text-purple-400 hover:text-purple-300 text-sm">{{ __('messages.view_all') }}</a>
        </div>
        <div class="space-y-3">
            @forelse($recentPayments as $payment)
                <div class="flex items-center justify-between p-3 bg-dark-800 rounded-lg">
                    <div>
                        <p class="font-medium text-white">{{ number_format($payment->amount, 3) }} {{ $payment->currency }}</p>
                        <p class="text-sm text-gray-400">{{ $payment->myfatoorah_invoice_id }}</p>
                    </div>
                    <div class="text-right">
                        @php
                            $statusColors = [
                                'pending' => 'bg-yellow-500/20 text-yellow-400',
                                'paid' => 'bg-green-500/20 text-green-400',
                                'failed' => 'bg-red-500/20 text-red-400',
                                'expired' => 'bg-gray-500/20 text-gray-400',
                            ];
                        @endphp
                        <span class="px-2 py-1 text-xs rounded-full {{ $statusColors[$payment->status] ?? 'bg-gray-500/20 text-gray-400' }}">{{ __('messages.status.' . $payment->status) }}</span>
                        <p class="text-xs text-gray-500 mt-1">{{ $payment->created_at->format('d M Y') }}</p>
                    </div>
                </div>
            @empty
                <p class="text-center text-gray-400 py-4">{{ __('messages.no_transactions') }}</p>
            @endforelse
        </div>
    </div>
</div>
@endsection

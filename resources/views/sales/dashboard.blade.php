@extends('sales.layout')

@section('title', __('messages.nav.dashboard'))

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div>
        <h1 class="text-2xl font-bold text-white">{{ __('messages.auth.welcome_back', ['name' => auth()->user()->full_name]) }}</h1>
        <p class="text-gray-400">{{ __('messages.roles.sales_person') }} Dashboard</p>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="glass-card rounded-xl p-6">
            <p class="text-gray-400 text-sm">{{ __('messages.stats.total_agents') }}</p>
            <p class="text-3xl font-bold text-white mt-2">{{ $stats['total_agents'] }}</p>
        </div>
        <div class="glass-card rounded-xl p-6">
            <p class="text-gray-400 text-sm">{{ __('messages.active') }} {{ __('messages.nav.agents') }}</p>
            <p class="text-3xl font-bold text-green-400 mt-2">{{ $stats['active_agents'] }}</p>
        </div>
        <div class="glass-card rounded-xl p-6">
            <p class="text-gray-400 text-sm">{{ __('messages.stats.total_transactions') }}</p>
            <p class="text-3xl font-bold text-white mt-2">{{ $stats['total_transactions'] }}</p>
        </div>
        <div class="glass-card rounded-xl p-6">
            <p class="text-gray-400 text-sm">{{ __('messages.stats.total_revenue') }}</p>
            <p class="text-3xl font-bold text-purple-400 mt-2">{{ number_format($stats['total_revenue'], 3) }} KWD</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Agents -->
        <div class="glass-card rounded-xl p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-white">{{ __('messages.recent_agents') }}</h2>
                <a href="{{ route('sales.agents') }}" class="text-purple-400 hover:text-purple-300 text-sm">{{ __('messages.view_all') }}</a>
            </div>
            <div class="space-y-3">
                @forelse($recentAgents as $agent)
                    <a href="{{ route('sales.agents.show', $agent) }}" class="flex items-center justify-between p-3 bg-dark-800 rounded-lg hover:bg-dark-800/70 transition-colors">
                        <div>
                            <p class="font-medium text-white">{{ $agent->company_name }}</p>
                            <p class="text-sm text-gray-400">{{ $agent->iata_number ?: 'No IATA' }}</p>
                        </div>
                        @if($agent->is_active)
                            <span class="px-2 py-1 text-xs rounded-full bg-green-500/20 text-green-400">{{ __('messages.active') }}</span>
                        @else
                            <span class="px-2 py-1 text-xs rounded-full bg-red-500/20 text-red-400">{{ __('messages.inactive') }}</span>
                        @endif
                    </a>
                @empty
                    <p class="text-center text-gray-400 py-4">{{ __('messages.no_agents') }}</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="glass-card rounded-xl p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-white">{{ __('messages.recent_transactions') }}</h2>
                <a href="{{ route('sales.transactions') }}" class="text-purple-400 hover:text-purple-300 text-sm">{{ __('messages.view_all') }}</a>
            </div>
            <div class="space-y-3">
                @forelse($recentTransactions as $payment)
                    <div class="flex items-center justify-between p-3 bg-dark-800 rounded-lg">
                        <div>
                            <p class="font-medium text-white">{{ number_format($payment->amount, 3) }} {{ $payment->currency }}</p>
                            <p class="text-sm text-gray-400">{{ $payment->agent?->company_name ?: 'Unknown' }}</p>
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
                            <p class="text-xs text-gray-500 mt-1">{{ $payment->created_at->format('d M') }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-400 py-4">{{ __('messages.no_transactions') }}</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

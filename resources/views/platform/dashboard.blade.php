@extends('platform.layout')
@section('title', 'Dashboard')

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-bold gradient-text">Platform Dashboard</h1>
    <p class="text-gray-400 mt-1">Overview of all agencies, users, and payments</p>
</div>

<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="stat-card rounded-2xl p-5">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 rounded-xl bg-purple-500/20 flex items-center justify-center">
                <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <span class="text-xs text-green-400 bg-green-500/20 px-2 py-1 rounded-full">{{ $stats['active_agencies'] }} active</span>
        </div>
        <p class="text-3xl font-bold text-white">{{ $stats['agencies'] }}</p>
        <p class="text-sm text-gray-400">Total Agencies</p>
    </div>

    <div class="stat-card rounded-2xl p-5">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 rounded-xl bg-pink-500/20 flex items-center justify-center">
                <svg class="w-5 h-5 text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </div>
            <span class="text-xs text-blue-400 bg-blue-500/20 px-2 py-1 rounded-full">{{ $stats['active_users'] }} active</span>
        </div>
        <p class="text-3xl font-bold text-white">{{ $stats['users'] }}</p>
        <p class="text-sm text-gray-400">Total Users</p>
    </div>

    <div class="stat-card rounded-2xl p-5">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 rounded-xl bg-green-500/20 flex items-center justify-center">
                <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <span class="text-xs text-green-400 bg-green-500/20 px-2 py-1 rounded-full">{{ $stats['paid_payments'] }} paid</span>
        </div>
        <p class="text-3xl font-bold text-white">{{ $stats['payments'] }}</p>
        <p class="text-sm text-gray-400">Total Payments</p>
    </div>

    <div class="stat-card rounded-2xl p-5">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 rounded-xl bg-orange-500/20 flex items-center justify-center">
                <svg class="w-5 h-5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <span class="text-xs text-orange-400">KWD</span>
        </div>
        <p class="text-3xl font-bold text-white">{{ number_format($stats['revenue'], 3) }}</p>
        <p class="text-sm text-gray-400">Total Revenue</p>
    </div>
</div>

<div class="grid lg:grid-cols-2 gap-6">
    <!-- Recent Agencies -->
    <div class="glass-card rounded-2xl p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-white">Recent Agencies</h2>
            <a href="{{ route('platform.agencies') }}" class="text-sm text-purple-400 hover:text-purple-300">View All →</a>
        </div>
        @if($recentAgencies->count() > 0)
        <div class="space-y-3">
            @foreach($recentAgencies as $agency)
            <div class="flex items-center justify-between p-3 rounded-xl bg-white/5 hover:bg-white/10 transition">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-purple-500/20 to-pink-500/20 flex items-center justify-center">
                        <span class="text-sm font-bold text-purple-400">{{ substr($agency->agency_name, 0, 2) }}</span>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-white">{{ $agency->agency_name }}</p>
                        <p class="text-xs text-gray-500">IATA: {{ $agency->iata_number }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-400">{{ $agency->users_count }} users</p>
                    @if($agency->is_active)
                        <span class="text-xs text-green-400">Active</span>
                    @else
                        <span class="text-xs text-red-400">Inactive</span>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @else
        <p class="text-gray-500 text-center py-8">No agencies yet</p>
        @endif
    </div>

    <!-- Recent Payments -->
    <div class="glass-card rounded-2xl p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-white">Recent Payments</h2>
            <a href="{{ route('platform.payments') }}" class="text-sm text-purple-400 hover:text-purple-300">View All →</a>
        </div>
        @if($recentPayments->count() > 0)
        <div class="space-y-3">
            @foreach($recentPayments->take(5) as $payment)
            <div class="flex items-center justify-between p-3 rounded-xl bg-white/5">
                <div>
                    <p class="text-sm font-medium text-white">{{ number_format($payment->amount, 3) }} {{ $payment->currency }}</p>
                    <p class="text-xs text-gray-500">{{ $payment->agency->agency_name ?? 'N/A' }}</p>
                </div>
                <div class="text-right">
                    @if($payment->status === 'paid')
                        <span class="px-2 py-1 text-xs rounded-full bg-green-500/20 text-green-400">Paid</span>
                    @elseif($payment->status === 'pending')
                        <span class="px-2 py-1 text-xs rounded-full bg-yellow-500/20 text-yellow-400">Pending</span>
                    @else
                        <span class="px-2 py-1 text-xs rounded-full bg-red-500/20 text-red-400">Failed</span>
                    @endif
                    <p class="text-xs text-gray-500 mt-1">{{ $payment->created_at->diffForHumans() }}</p>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <p class="text-gray-500 text-center py-8">No payments yet</p>
        @endif
    </div>
</div>

<!-- Quick Actions -->
<div class="mt-6 glass-card rounded-2xl p-6">
    <h2 class="text-lg font-semibold text-white mb-4">Quick Actions</h2>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <a href="{{ route('platform.agencies') }}" class="flex flex-col items-center gap-2 p-4 rounded-xl bg-white/5 hover:bg-purple-500/10 transition group">
            <div class="w-12 h-12 rounded-xl bg-purple-500/20 flex items-center justify-center group-hover:bg-purple-500/30 transition">
                <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <span class="text-sm text-gray-300">Manage Agencies</span>
        </a>
        <a href="{{ route('platform.users') }}" class="flex flex-col items-center gap-2 p-4 rounded-xl bg-white/5 hover:bg-pink-500/10 transition group">
            <div class="w-12 h-12 rounded-xl bg-pink-500/20 flex items-center justify-center group-hover:bg-pink-500/30 transition">
                <svg class="w-6 h-6 text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </div>
            <span class="text-sm text-gray-300">Manage Users</span>
        </a>
        <a href="{{ route('platform.payments') }}" class="flex flex-col items-center gap-2 p-4 rounded-xl bg-white/5 hover:bg-green-500/10 transition group">
            <div class="w-12 h-12 rounded-xl bg-green-500/20 flex items-center justify-center group-hover:bg-green-500/30 transition">
                <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <span class="text-sm text-gray-300">View Payments</span>
        </a>
        <a href="{{ route('platform.settings') }}" class="flex flex-col items-center gap-2 p-4 rounded-xl bg-white/5 hover:bg-blue-500/10 transition group">
            <div class="w-12 h-12 rounded-xl bg-blue-500/20 flex items-center justify-center group-hover:bg-blue-500/30 transition">
                <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <span class="text-sm text-gray-300">Settings</span>
        </a>
    </div>
</div>
@endsection

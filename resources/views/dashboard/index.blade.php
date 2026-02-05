<x-layouts.app :title="__('messages.nav.dashboard')">
    @section('page-title', __('messages.nav.dashboard'))

    <!-- Stats Grid -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-8">
        <!-- Revenue Today -->
        <div class="glass-card rounded-2xl p-6 relative overflow-hidden group hover:scale-105 transition-transform duration-300">
            <div class="absolute inset-0 bg-gradient-to-br from-purple-500/10 to-transparent opacity-0 group-hover:opacity-100 transition"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-3xl font-bold text-white mb-1">{{ number_format($stats['revenue_today'], 3) }}</p>
                <p class="text-sm text-gray-400">{{ __('messages.dashboard.revenue_today') }}</p>
            </div>
        </div>

        <!-- Paid Payments Today -->
        <div class="glass-card rounded-2xl p-6 relative overflow-hidden group hover:scale-105 transition-transform duration-300">
            <div class="absolute inset-0 bg-gradient-to-br from-green-500/10 to-transparent opacity-0 group-hover:opacity-100 transition"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-green-500 to-emerald-500 flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-3xl font-bold text-white mb-1">{{ $stats['paid_today'] }}</p>
                <p class="text-sm text-gray-400">{{ __('messages.dashboard.paid_today') }}</p>
            </div>
        </div>

        <!-- Pending Payments -->
        <div class="glass-card rounded-2xl p-6 relative overflow-hidden group hover:scale-105 transition-transform duration-300">
            <div class="absolute inset-0 bg-gradient-to-br from-yellow-500/10 to-transparent opacity-0 group-hover:opacity-100 transition"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-yellow-500 to-orange-500 flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-3xl font-bold text-white mb-1">{{ $stats['pending_payments'] }}</p>
                <p class="text-sm text-gray-400">{{ __('messages.dashboard.pending') }}</p>
            </div>
        </div>

        <!-- Monthly Revenue -->
        <div class="glass-card rounded-2xl p-6 relative overflow-hidden group hover:scale-105 transition-transform duration-300">
            <div class="absolute inset-0 bg-gradient-to-br from-cyan-500/10 to-transparent opacity-0 group-hover:opacity-100 transition"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-cyan-500 to-blue-500 flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                    </div>
                </div>
                <p class="text-3xl font-bold text-white mb-1">{{ number_format($stats['revenue_month'], 3) }}</p>
                <p class="text-sm text-gray-400">{{ __('messages.dashboard.revenue_month') }}</p>
            </div>
        </div>
    </div>

    <!-- Chart & Recent Payments -->
    <div class="grid lg:grid-cols-3 gap-6">
        <!-- Chart -->
        <div class="lg:col-span-2 glass-card rounded-2xl p-6">
            <h3 class="text-lg font-semibold text-white mb-6">{{ __('messages.dashboard.weekly_overview') }}</h3>
            <div class="h-64 flex items-end justify-between gap-2">
                @foreach($chartData['labels'] as $index => $label)
                    <div class="flex-1 flex flex-col items-center gap-2">
                        <div class="w-full bg-dark-600 rounded-t-lg relative overflow-hidden" style="height: {{ max(20, ($chartData['revenue'][$index] / max(1, max($chartData['revenue']))) * 200) }}px;">
                            <div class="absolute inset-0 bg-gradient-to-t from-purple-500 to-pink-500 opacity-80"></div>
                        </div>
                        <span class="text-xs text-gray-500">{{ $label }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Recent Payments -->
        <div class="glass-card rounded-2xl p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-white">{{ __('messages.dashboard.recent_payments') }}</h3>
                <a href="{{ route('payments.index') }}" class="text-sm text-purple-400 hover:text-purple-300">
                    {{ __('messages.common.view_all') }} &rarr;
                </a>
            </div>

            <div class="space-y-4">
                @forelse($recentPayments as $payment)
                    <div class="flex items-center gap-4 p-3 rounded-xl hover:bg-dark-700/50 transition">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center
                            @if($payment->status === 'paid') bg-green-500/20 text-green-400
                            @elseif($payment->status === 'pending') bg-yellow-500/20 text-yellow-400
                            @else bg-red-500/20 text-red-400 @endif">
                            @if($payment->status === 'paid')
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            @elseif($payment->status === 'pending')
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3"/>
                                </svg>
                            @else
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-white truncate">
                                {{ $payment->customer_phone ?? 'N/A' }}
                            </p>
                            <p class="text-xs text-gray-500">
                                {{ $payment->created_at->diffForHumans() }}
                            </p>
                        </div>
                        <p class="text-sm font-semibold text-white">
                            {{ number_format($payment->amount, 3) }} {{ $payment->currency }}
                        </p>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                        <p class="text-gray-500">{{ __('messages.payments.no_payments') }}</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mt-8 glass-card rounded-2xl p-6">
        <h3 class="text-lg font-semibold text-white mb-6">{{ __('messages.dashboard.quick_actions') }}</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="{{ route('payments.create') }}"
               class="flex flex-col items-center gap-3 p-6 rounded-xl bg-dark-700 hover:bg-dark-600 border border-dark-500 hover:border-purple-500/50 transition group">
                <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center group-hover:scale-110 transition">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                </div>
                <span class="text-sm font-medium text-gray-300">{{ __('messages.payments.new') }}</span>
            </a>

            <a href="{{ route('payments.index') }}?status=pending"
               class="flex flex-col items-center gap-3 p-6 rounded-xl bg-dark-700 hover:bg-dark-600 border border-dark-500 hover:border-yellow-500/50 transition group">
                <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-yellow-500 to-orange-500 flex items-center justify-center group-hover:scale-110 transition">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="text-sm font-medium text-gray-300">{{ __('messages.dashboard.view_pending') }}</span>
            </a>

            @if(auth()->user()->hasRole(['admin', 'super_admin']))
            <a href="{{ route('team.index') }}"
               class="flex flex-col items-center gap-3 p-6 rounded-xl bg-dark-700 hover:bg-dark-600 border border-dark-500 hover:border-cyan-500/50 transition group">
                <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-cyan-500 to-blue-500 flex items-center justify-center group-hover:scale-110 transition">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <span class="text-sm font-medium text-gray-300">{{ __('messages.nav.team') }}</span>
            </a>
            @endif

            <a href="{{ route('settings.index') }}"
               class="flex flex-col items-center gap-3 p-6 rounded-xl bg-dark-700 hover:bg-dark-600 border border-dark-500 hover:border-gray-500/50 transition group">
                <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-gray-500 to-gray-600 flex items-center justify-center group-hover:scale-110 transition">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <span class="text-sm font-medium text-gray-300">{{ __('messages.nav.settings') }}</span>
            </a>
        </div>
    </div>
</x-layouts.app>

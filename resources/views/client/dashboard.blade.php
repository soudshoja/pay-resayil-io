<x-client-layout title="{{ __('messages.nav.dashboard') }}">
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <!-- Total Agents -->
        <div class="glass-card rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm">{{ __('messages.stats.total_agents') }}</p>
                    <p class="text-3xl font-bold mt-1">{{ number_format($stats['total_agents']) }}</p>
                    <p class="text-green-400 text-sm mt-1">{{ $stats['active_agents'] }} {{ __('messages.active') }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-purple-500/20 flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Transactions -->
        <div class="glass-card rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm">{{ __('messages.stats.total_transactions') }}</p>
                    <p class="text-3xl font-bold mt-1">{{ number_format($stats['total_transactions']) }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-pink-500/20 flex items-center justify-center">
                    <svg class="w-6 h-6 text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Revenue -->
        <div class="glass-card rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm">{{ __('messages.stats.total_revenue') }}</p>
                    <p class="text-3xl font-bold mt-1">{{ number_format($stats['total_revenue'], 3) }}</p>
                    <p class="text-gray-500 text-sm mt-1">KWD</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-green-500/20 flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Pending Payments -->
        <div class="glass-card rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm">{{ __('messages.stats.pending_payments') }}</p>
                    <p class="text-3xl font-bold mt-1">{{ number_format($stats['pending_payments']) }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-yellow-500/20 flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Resayil WhatsApp Dashboard (iframe) -->
    <div class="glass-card rounded-xl p-6 mb-6">
        <h2 class="text-xl font-bold mb-4 flex items-center gap-2">
            <svg class="w-6 h-6 text-green-400" fill="currentColor" viewBox="0 0 24 24">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
            </svg>
            Resayil WhatsApp Dashboard
        </h2>
        <div class="rounded-lg overflow-hidden border border-purple-500/30">
            <iframe
                src="https://wa.resayil.io/"
                width="100%"
                height="600px"
                class="bg-dark-900"
                allow="clipboard-write"
            ></iframe>
        </div>
        <p class="text-gray-500 text-sm mt-2">
            {{ __('messages.resayil_iframe_note') }}
        </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Transactions -->
        <div class="glass-card rounded-xl p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold">{{ __('messages.recent_transactions') }}</h2>
                <a href="{{ route('client.transactions') }}" class="text-purple-400 hover:text-purple-300 text-sm">
                    {{ __('messages.view_all') }} &rarr;
                </a>
            </div>
            <div class="space-y-3">
                @forelse($recentTransactions as $transaction)
                    <div class="flex items-center justify-between p-3 rounded-lg bg-dark-900/50">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-{{ $transaction->status === 'paid' ? 'green' : ($transaction->status === 'pending' ? 'yellow' : 'red') }}-500/20 flex items-center justify-center">
                                @if($transaction->status === 'paid')
                                    <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                @elseif($transaction->status === 'pending')
                                    <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                @else
                                    <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                @endif
                            </div>
                            <div>
                                <p class="font-medium text-sm">{{ $transaction->agent?->company_name ?? 'N/A' }}</p>
                                <p class="text-gray-500 text-xs">{{ $transaction->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <div class="text-end">
                            <p class="font-bold">{{ number_format($transaction->amount, 3) }} {{ $transaction->currency }}</p>
                            <span class="text-xs px-2 py-1 rounded-full bg-{{ $transaction->status === 'paid' ? 'green' : ($transaction->status === 'pending' ? 'yellow' : 'red') }}-500/20 text-{{ $transaction->status === 'paid' ? 'green' : ($transaction->status === 'pending' ? 'yellow' : 'red') }}-400">
                                {{ ucfirst($transaction->status) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-8">{{ __('messages.no_transactions') }}</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Agents -->
        <div class="glass-card rounded-xl p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold">{{ __('messages.recent_agents') }}</h2>
                <a href="{{ route('client.agents') }}" class="text-purple-400 hover:text-purple-300 text-sm">
                    {{ __('messages.view_all') }} &rarr;
                </a>
            </div>
            <div class="space-y-3">
                @forelse($recentAgents as $agent)
                    <div class="flex items-center justify-between p-3 rounded-lg bg-dark-900/50">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-purple-500/20 flex items-center justify-center text-purple-400 font-bold">
                                {{ substr($agent->company_name, 0, 1) }}
                            </div>
                            <div>
                                <p class="font-medium text-sm">{{ $agent->company_name }}</p>
                                <p class="text-gray-500 text-xs">{{ $agent->iata_number ?? 'No IATA' }}</p>
                            </div>
                        </div>
                        <div class="text-end">
                            <span class="text-xs px-2 py-1 rounded-full bg-{{ $agent->is_active ? 'green' : 'red' }}-500/20 text-{{ $agent->is_active ? 'green' : 'red' }}-400">
                                {{ $agent->is_active ? __('messages.active') : __('messages.inactive') }}
                            </span>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-8">{{ __('messages.no_agents') }}</p>
                @endforelse
            </div>
        </div>
    </div>
</x-client-layout>

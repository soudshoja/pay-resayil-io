<x-layouts.app :title="__('messages.nav.payments')">
    @section('page-title', __('messages.nav.payments'))

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold text-white">{{ __('messages.payments.title') }}</h2>
            <p class="text-gray-400">{{ __('messages.payments.subtitle') }}</p>
        </div>
        <a href="{{ route('payments.create') }}"
           class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-gradient-to-r from-purple-500 to-pink-500 text-white font-semibold hover:shadow-lg hover:shadow-purple-500/30 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            {{ __('messages.payments.new') }}
        </a>
    </div>

    <!-- Filters -->
    <div class="glass-card rounded-2xl p-4 mb-6">
        <form method="GET" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="{{ __('messages.payments.search_placeholder') }}"
                       class="w-full px-4 py-2 bg-dark-700 border border-dark-500 rounded-xl text-white placeholder-gray-500 focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20">
            </div>
            <select name="status"
                    class="px-4 py-2 bg-dark-700 border border-dark-500 rounded-xl text-white focus:border-purple-500">
                <option value="">{{ __('messages.payments.all_status') }}</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>{{ __('messages.status.pending') }}</option>
                <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>{{ __('messages.status.paid') }}</option>
                <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>{{ __('messages.status.failed') }}</option>
                <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>{{ __('messages.status.expired') }}</option>
            </select>
            <button type="submit"
                    class="px-6 py-2 bg-purple-500 hover:bg-purple-600 text-white rounded-xl transition">
                {{ __('messages.common.filter') }}
            </button>
        </form>
    </div>

    <!-- Payments Table -->
    <div class="glass-card rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-dark-600">
                        <th class="px-6 py-4 text-start text-sm font-medium text-gray-400">{{ __('messages.payments.customer') }}</th>
                        <th class="px-6 py-4 text-start text-sm font-medium text-gray-400">{{ __('messages.payments.amount') }}</th>
                        <th class="px-6 py-4 text-start text-sm font-medium text-gray-400">{{ __('messages.payments.status') }}</th>
                        <th class="px-6 py-4 text-start text-sm font-medium text-gray-400">{{ __('messages.payments.date') }}</th>
                        <th class="px-6 py-4 text-end text-sm font-medium text-gray-400">{{ __('messages.common.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-dark-600">
                    @forelse($payments as $payment)
                        <tr class="hover:bg-dark-700/50 transition">
                            <td class="px-6 py-4">
                                <div>
                                    <p class="text-white font-medium">{{ $payment->customer_phone ?? 'N/A' }}</p>
                                    <p class="text-sm text-gray-500">{{ $payment->customer_name ?? '-' }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-white font-semibold">{{ number_format($payment->amount, 3) }} {{ $payment->currency }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium
                                    @if($payment->status === 'paid') bg-green-500/20 text-green-400
                                    @elseif($payment->status === 'pending') bg-yellow-500/20 text-yellow-400
                                    @elseif($payment->status === 'failed') bg-red-500/20 text-red-400
                                    @else bg-gray-500/20 text-gray-400 @endif">
                                    <span class="w-1.5 h-1.5 rounded-full
                                        @if($payment->status === 'paid') bg-green-400
                                        @elseif($payment->status === 'pending') bg-yellow-400
                                        @elseif($payment->status === 'failed') bg-red-400
                                        @else bg-gray-400 @endif"></span>
                                    {{ __('messages.status.' . $payment->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-gray-400 text-sm">{{ $payment->created_at->format('Y-m-d H:i') }}</p>
                            </td>
                            <td class="px-6 py-4 text-end">
                                <a href="{{ route('payments.show', $payment) }}"
                                   class="text-purple-400 hover:text-purple-300 text-sm font-medium">
                                    {{ __('messages.common.view') }}
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <svg class="w-12 h-12 text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                </svg>
                                <p class="text-gray-500">{{ __('messages.payments.no_payments') }}</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($payments->hasPages())
            <div class="px-6 py-4 border-t border-dark-600">
                {{ $payments->links() }}
            </div>
        @endif
    </div>
</x-layouts.app>

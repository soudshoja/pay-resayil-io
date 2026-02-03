<x-layouts.app :title="__('messages.payments.details')">
    @section('page-title', __('messages.payments.details'))

    <div class="max-w-3xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('payments.index') }}" class="inline-flex items-center gap-2 text-gray-400 hover:text-white mb-4">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                {{ __('messages.common.back') }}
            </a>

            <div class="flex items-start justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-white">{{ __('messages.payments.details') }}</h2>
                    <p class="text-gray-400">#{{ $payment->id }}</p>
                </div>
                <span class="px-4 py-2 rounded-xl text-sm font-medium
                    @if($payment->status === 'paid') bg-green-500/20 text-green-400
                    @elseif($payment->status === 'pending') bg-yellow-500/20 text-yellow-400
                    @elseif($payment->status === 'failed') bg-red-500/20 text-red-400
                    @else bg-gray-500/20 text-gray-400 @endif">
                    {{ __('messages.status.' . $payment->status) }}
                </span>
            </div>
        </div>

        <!-- Payment Card -->
        <div class="glass-card rounded-2xl overflow-hidden mb-6">
            <!-- Amount Header -->
            <div class="bg-gradient-to-r from-purple-500/20 to-pink-500/20 p-8 text-center border-b border-dark-600">
                <p class="text-gray-400 mb-2">{{ __('messages.payments.amount') }}</p>
                <p class="text-5xl font-bold text-white">{{ number_format($payment->amount, 3) }}</p>
                <p class="text-xl text-gray-400 mt-1">{{ $payment->currency }}</p>
            </div>

            <!-- Details -->
            <div class="p-6 space-y-6">
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-400 mb-1">{{ __('messages.payments.customer_phone') }}</p>
                        <p class="text-white font-medium" dir="ltr">{{ $payment->customer_phone ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400 mb-1">{{ __('messages.payments.customer_name') }}</p>
                        <p class="text-white font-medium">{{ $payment->customer_name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400 mb-1">{{ __('messages.payments.created_at') }}</p>
                        <p class="text-white">{{ $payment->created_at->format('Y-m-d H:i:s') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400 mb-1">{{ __('messages.payments.paid_at') }}</p>
                        <p class="text-white">{{ $payment->paid_at?->format('Y-m-d H:i:s') ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400 mb-1">{{ __('messages.payments.invoice_id') }}</p>
                        <p class="text-white font-mono text-sm">{{ $payment->myfatoorah_invoice_id ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400 mb-1">{{ __('messages.payments.reference_id') }}</p>
                        <p class="text-white font-mono text-sm">{{ $payment->reference_id ?? '-' }}</p>
                    </div>
                </div>

                @if($payment->description)
                    <div>
                        <p class="text-sm text-gray-400 mb-1">{{ __('messages.payments.description') }}</p>
                        <p class="text-white">{{ $payment->description }}</p>
                    </div>
                @endif

                @if($payment->agent)
                    <div>
                        <p class="text-sm text-gray-400 mb-1">{{ __('messages.payments.created_by') }}</p>
                        <p class="text-white">{{ $payment->agent->full_name }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Actions -->
        @if($payment->isPending())
            <div class="flex flex-col sm:flex-row gap-4">
                <!-- Payment Link -->
                <div class="flex-1 glass-card rounded-xl p-4">
                    <p class="text-sm text-gray-400 mb-2">{{ __('messages.payments.payment_link') }}</p>
                    <div class="flex gap-2">
                        <input type="text"
                               value="{{ $payment->payment_url }}"
                               readonly
                               class="flex-1 px-3 py-2 bg-dark-700 border border-dark-500 rounded-lg text-white text-sm font-mono truncate"
                               id="payment-link">
                        <button onclick="navigator.clipboard.writeText(document.getElementById('payment-link').value)"
                                class="px-4 py-2 bg-purple-500 hover:bg-purple-600 text-white rounded-lg transition">
                            {{ __('messages.common.copy') }}
                        </button>
                    </div>
                </div>

                <!-- Resend -->
                <form method="POST" action="{{ route('payments.resend', $payment) }}">
                    @csrf
                    <button type="submit"
                            class="w-full sm:w-auto px-6 py-4 bg-green-500 hover:bg-green-600 text-white rounded-xl font-medium transition flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        {{ __('messages.payments.resend_whatsapp') }}
                    </button>
                </form>

                <!-- Cancel -->
                <form method="POST" action="{{ route('payments.cancel', $payment) }}" onsubmit="return confirm('{{ __('messages.common.confirm_action') }}')">
                    @csrf
                    <button type="submit"
                            class="w-full sm:w-auto px-6 py-4 bg-red-500/20 hover:bg-red-500/30 text-red-400 rounded-xl font-medium transition">
                        {{ __('messages.payments.cancel') }}
                    </button>
                </form>
            </div>
        @endif
    </div>
</x-layouts.app>

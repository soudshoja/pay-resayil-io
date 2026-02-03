@extends('accountant.layout')

@section('title', 'Transaction ' . $payment->myfatoorah_invoice_id)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div>
        <a href="{{ route('accountant.transactions') }}" class="inline-flex items-center text-gray-400 hover:text-white transition-colors mb-2">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            {{ __('messages.common.back') }}
        </a>
        <h1 class="text-2xl font-bold text-white">Transaction Details</h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Transaction Details -->
        <div class="lg:col-span-2 space-y-6">
            <div class="glass-card rounded-xl p-6">
                <h2 class="text-lg font-semibold text-white mb-4">Payment Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-400">{{ __('messages.payment.invoice_id') }}</p>
                        <p class="text-white font-mono">{{ $payment->myfatoorah_invoice_id }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">{{ __('messages.payments.status') }}</p>
                        @php
                            $statusColors = [
                                'pending' => 'bg-yellow-500/20 text-yellow-400',
                                'paid' => 'bg-green-500/20 text-green-400',
                                'failed' => 'bg-red-500/20 text-red-400',
                                'expired' => 'bg-gray-500/20 text-gray-400',
                            ];
                        @endphp
                        <span class="px-2 py-1 text-xs rounded-full {{ $statusColors[$payment->status] ?? 'bg-gray-500/20 text-gray-400' }}">{{ __('messages.status.' . $payment->status) }}</span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">{{ __('messages.payment.amount') }}</p>
                        <p class="text-2xl font-bold text-white">{{ number_format($payment->amount, 3) }} {{ $payment->currency }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">{{ __('messages.payment.service_fee') }}</p>
                        <p class="text-white">{{ number_format($payment->service_fee ?? 0, 3) }} {{ $payment->currency }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">{{ __('messages.payment.total') }}</p>
                        <p class="text-white font-semibold">{{ number_format($payment->total_amount ?? $payment->amount, 3) }} {{ $payment->currency }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">{{ __('messages.payments.customer_phone') }}</p>
                        <p class="text-white font-mono">{{ $payment->customer_phone ?: '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Created At</p>
                        <p class="text-white">{{ $payment->created_at->format('d M Y, H:i:s') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Paid At</p>
                        <p class="text-white">{{ $payment->paid_at?->format('d M Y, H:i:s') ?: '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Agent Info -->
            @if($payment->agent)
            <div class="glass-card rounded-xl p-6">
                <h2 class="text-lg font-semibold text-white mb-4">{{ __('messages.payment.agent') }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-400">Company Name</p>
                        <p class="text-white">{{ $payment->agent->company_name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">IATA Number</p>
                        <p class="text-white">{{ $payment->agent->iata_number ?: '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Email</p>
                        <p class="text-white">{{ $payment->agent->email ?: '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Phone</p>
                        <p class="text-white">{{ $payment->agent->phone ?: '-' }}</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Notes -->
            <div class="glass-card rounded-xl p-6">
                <h2 class="text-lg font-semibold text-white mb-4">Notes</h2>

                <!-- Add Note Form -->
                <form action="{{ route('accountant.transactions.notes.store', $payment) }}" method="POST" class="mb-4">
                    @csrf
                    <div class="flex gap-2">
                        <input type="text" name="note" placeholder="Add a note..." required class="flex-1 px-4 py-2 bg-dark-800 border border-gray-700 rounded-lg text-white focus:border-purple-500 focus:outline-none">
                        <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                            Add
                        </button>
                    </div>
                </form>

                <!-- Notes List -->
                <div class="space-y-3">
                    @forelse($payment->notes->where('visible_to_clients', true) as $note)
                        <div class="p-3 bg-dark-800 rounded-lg">
                            <p class="text-white">{{ $note->note }}</p>
                            <p class="text-xs text-gray-500 mt-2">{{ $note->createdBy?->full_name }} - {{ $note->created_at->format('d M Y, H:i') }}</p>
                        </div>
                    @empty
                        <p class="text-center text-gray-400 py-4">No notes yet</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="space-y-4">
            <div class="glass-card rounded-xl p-6">
                <h2 class="text-lg font-semibold text-white mb-4">Quick Actions</h2>
                <div class="space-y-3">
                    @if($payment->payment_url)
                        <a href="{{ $payment->payment_url }}" target="_blank" class="flex items-center justify-center w-full px-4 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                            Open Payment Link
                        </a>
                    @endif
                    <button onclick="navigator.clipboard.writeText('{{ $payment->myfatoorah_invoice_id }}')" class="flex items-center justify-center w-full px-4 py-3 bg-gray-700 text-white rounded-lg hover:bg-gray-600 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/>
                        </svg>
                        {{ __('messages.common.copy') }} Invoice ID
                    </button>
                </div>
            </div>

            <!-- Payment URL -->
            @if($payment->payment_url)
            <div class="glass-card rounded-xl p-6">
                <h2 class="text-lg font-semibold text-white mb-4">Payment URL</h2>
                <div class="p-3 bg-dark-800 rounded-lg break-all">
                    <p class="text-xs text-gray-400 font-mono">{{ $payment->payment_url }}</p>
                </div>
                <button onclick="navigator.clipboard.writeText('{{ $payment->payment_url }}')" class="mt-3 w-full px-4 py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-600 transition-colors text-sm">
                    {{ __('messages.common.copy') }} URL
                </button>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

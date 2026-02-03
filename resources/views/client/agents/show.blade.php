@extends('client.layout')

@section('title', $agent->company_name)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <a href="{{ route('client.agents') }}" class="inline-flex items-center text-gray-400 hover:text-white transition-colors mb-2">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                {{ __('messages.common.back') }}
            </a>
            <h1 class="text-2xl font-bold text-white">{{ $agent->company_name }}</h1>
            @if($agent->iata_number)
                <span class="inline-block mt-2 px-3 py-1 bg-purple-500/20 text-purple-400 rounded-full text-sm font-medium">IATA: {{ $agent->iata_number }}</span>
            @endif
        </div>
        <div class="flex gap-2">
            <a href="{{ route('client.agents.edit', $agent) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                {{ __('messages.common.edit') }}
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Agent Details -->
        <div class="lg:col-span-2 space-y-6">
            <div class="glass-card rounded-xl p-6">
                <h2 class="text-lg font-semibold text-white mb-4">Agent Details</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-400">Company Name</p>
                        <p class="text-white">{{ $agent->company_name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">IATA Number</p>
                        <p class="text-white">{{ $agent->iata_number ?: '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Email</p>
                        <p class="text-white">{{ $agent->email ?: '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Phone</p>
                        <p class="text-white">{{ $agent->phone ?: '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Accountant WhatsApp</p>
                        <p class="text-white">{{ $agent->accountant_whatsapp ?: '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Sales Person</p>
                        <p class="text-white">{{ $agent->salesPerson?->full_name ?: '-' }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-sm text-gray-400">Address</p>
                        <p class="text-white">{{ $agent->address ?: '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Status</p>
                        @if($agent->is_active)
                            <span class="px-2 py-1 text-xs rounded-full bg-green-500/20 text-green-400">{{ __('messages.active') }}</span>
                        @else
                            <span class="px-2 py-1 text-xs rounded-full bg-red-500/20 text-red-400">{{ __('messages.inactive') }}</span>
                        @endif
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Created At</p>
                        <p class="text-white">{{ $agent->created_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>
                @if($agent->notes)
                    <div class="mt-4 pt-4 border-t border-gray-700">
                        <p class="text-sm text-gray-400">Notes</p>
                        <p class="text-white whitespace-pre-line">{{ $agent->notes }}</p>
                    </div>
                @endif
            </div>

            <!-- Recent Transactions -->
            <div class="glass-card rounded-xl p-6">
                <h2 class="text-lg font-semibold text-white mb-4">{{ __('messages.recent_transactions') }}</h2>
                <div class="space-y-3">
                    @forelse($agent->paymentRequests as $payment)
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

        <!-- Authorized Phones -->
        <div class="space-y-6">
            <div class="glass-card rounded-xl p-6">
                <h2 class="text-lg font-semibold text-white mb-4">Authorized Phones</h2>
                <p class="text-sm text-gray-400 mb-4">These phone numbers can create payments via WhatsApp.</p>

                <form action="{{ route('client.agents.phones.store', $agent) }}" method="POST" class="mb-4">
                    @csrf
                    <div class="space-y-3">
                        <input type="text" name="phone_number" placeholder="+965XXXXXXXX" required class="w-full px-4 py-2 bg-dark-800 border border-gray-700 rounded-lg text-white focus:border-purple-500 focus:outline-none">
                        <input type="text" name="full_name" placeholder="Name (optional)" class="w-full px-4 py-2 bg-dark-800 border border-gray-700 rounded-lg text-white focus:border-purple-500 focus:outline-none">
                        <button type="submit" class="w-full px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                            {{ __('messages.common.add') }}
                        </button>
                    </div>
                </form>

                <div class="space-y-2">
                    @forelse($agent->authorizedPhones as $phone)
                        <div class="flex items-center justify-between p-3 bg-dark-800 rounded-lg">
                            <div>
                                <p class="font-mono text-white">{{ $phone->phone_number }}</p>
                                @if($phone->full_name)
                                    <p class="text-sm text-gray-400">{{ $phone->full_name }}</p>
                                @endif
                            </div>
                            <form action="{{ route('client.phones.destroy', $phone) }}" method="POST" onsubmit="return confirm('{{ __('messages.common.confirm_delete') }}')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-gray-400 hover:text-red-400 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    @empty
                        <p class="text-center text-gray-400 py-4">No authorized phones</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

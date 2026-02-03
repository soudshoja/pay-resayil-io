@extends('sales.layout')

@section('title', $agent->company_name)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div>
        <a href="{{ route('sales.agents') }}" class="inline-flex items-center text-gray-400 hover:text-white transition-colors mb-2">
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Agent Details -->
        <div class="lg:col-span-2">
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
                        <p class="text-white">{{ $agent->created_at->format('d M Y') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats -->
        <div class="space-y-4">
            <div class="glass-card rounded-xl p-6">
                <p class="text-gray-400 text-sm">Total Transactions</p>
                <p class="text-3xl font-bold text-white mt-2">{{ $agent->paymentRequests()->count() }}</p>
            </div>
            <div class="glass-card rounded-xl p-6">
                <p class="text-gray-400 text-sm">Total Revenue</p>
                <p class="text-3xl font-bold text-purple-400 mt-2">{{ number_format($agent->paymentRequests()->where('status', 'paid')->sum('amount'), 3) }} KWD</p>
            </div>
            <div class="glass-card rounded-xl p-6">
                <p class="text-gray-400 text-sm">Authorized Phones</p>
                <p class="text-3xl font-bold text-white mt-2">{{ $agent->authorizedPhones()->count() }}</p>
            </div>
        </div>
    </div>
</div>
@endsection

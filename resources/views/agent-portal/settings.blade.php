@extends('agent-portal.layout')

@section('title', __('messages.nav.settings'))

@section('content')
@php $agent = session('agent'); @endphp
<div class="max-w-2xl mx-auto space-y-6">
    <!-- Header -->
    <div>
        <h1 class="text-2xl font-bold text-white">{{ __('messages.nav.settings') }}</h1>
        <p class="text-gray-400">Update your agency information</p>
    </div>

    <!-- Form -->
    <div class="glass-card rounded-xl p-6">
        <form action="{{ route('agent.settings.update') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-300 mb-2">Company Name *</label>
                    <input type="text" name="company_name" value="{{ old('company_name', $agent->company_name) }}" required class="w-full px-4 py-3 bg-dark-800 border border-gray-700 rounded-lg text-white focus:border-purple-500 focus:outline-none @error('company_name') border-red-500 @enderror">
                    @error('company_name')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">IATA Number</label>
                    <input type="text" name="iata_number" value="{{ old('iata_number', $agent->iata_number) }}" class="w-full px-4 py-3 bg-dark-800 border border-gray-700 rounded-lg text-white focus:border-purple-500 focus:outline-none @error('iata_number') border-red-500 @enderror">
                    @error('iata_number')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email', $agent->email) }}" class="w-full px-4 py-3 bg-dark-800 border border-gray-700 rounded-lg text-white focus:border-purple-500 focus:outline-none @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">{{ __('messages.auth.phone') }}</label>
                    <input type="text" name="phone" value="{{ old('phone', $agent->phone) }}" placeholder="+965XXXXXXXX" class="w-full px-4 py-3 bg-dark-800 border border-gray-700 rounded-lg text-white focus:border-purple-500 focus:outline-none @error('phone') border-red-500 @enderror">
                    @error('phone')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Accountant WhatsApp</label>
                    <input type="text" name="accountant_whatsapp" value="{{ old('accountant_whatsapp', $agent->accountant_whatsapp) }}" placeholder="+965XXXXXXXX" class="w-full px-4 py-3 bg-dark-800 border border-gray-700 rounded-lg text-white focus:border-purple-500 focus:outline-none @error('accountant_whatsapp') border-red-500 @enderror">
                    <p class="mt-1 text-sm text-gray-500">Payment confirmations will be sent to this number</p>
                    @error('accountant_whatsapp')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-300 mb-2">Address</label>
                    <textarea name="address" rows="2" class="w-full px-4 py-3 bg-dark-800 border border-gray-700 rounded-lg text-white focus:border-purple-500 focus:outline-none @error('address') border-red-500 @enderror">{{ old('address', $agent->address) }}</textarea>
                    @error('address')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex justify-end pt-4 border-t border-gray-700">
                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-lg hover:from-purple-600 hover:to-pink-600 transition-all font-semibold">
                    {{ __('messages.common.save') }}
                </button>
            </div>
        </form>
    </div>

    <!-- Account Info -->
    <div class="glass-card rounded-xl p-6">
        <h2 class="text-lg font-semibold text-white mb-4">Account Information</h2>
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-gray-400">Account Status</p>
                @if($agent->is_active)
                    <span class="px-2 py-1 text-xs rounded-full bg-green-500/20 text-green-400">{{ __('messages.active') }}</span>
                @else
                    <span class="px-2 py-1 text-xs rounded-full bg-red-500/20 text-red-400">{{ __('messages.inactive') }}</span>
                @endif
            </div>
            <div>
                <p class="text-gray-400">Created At</p>
                <p class="text-white">{{ $agent->created_at->format('d M Y') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection

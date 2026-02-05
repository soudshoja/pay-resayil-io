@extends('client.layout')

@section('title', __('messages.common.add') . ' ' . __('messages.nav.agents'))

@section('content')
<div class="max-w-2xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <a href="{{ route('client.agents') }}" class="inline-flex items-center text-gray-400 hover:text-white transition-colors mb-4">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            {{ __('messages.common.back') }}
        </a>
        <h1 class="text-2xl font-bold text-white">{{ __('messages.common.add') }} {{ __('messages.nav.agents') }}</h1>
    </div>

    <!-- Form -->
    <div class="glass-card rounded-xl p-6">
        <form action="{{ route('client.agents.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-300 mb-2">{{ __('messages.payments.customer_name') }} *</label>
                    <input type="text" name="company_name" value="{{ old('company_name') }}" required class="w-full px-4 py-3 bg-dark-800 border border-gray-700 rounded-lg text-white focus:border-purple-500 focus:outline-none @error('company_name') border-red-500 @enderror">
                    @error('company_name')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">IATA Number</label>
                    <input type="text" name="iata_number" value="{{ old('iata_number') }}" class="w-full px-4 py-3 bg-dark-800 border border-gray-700 rounded-lg text-white focus:border-purple-500 focus:outline-none @error('iata_number') border-red-500 @enderror">
                    @error('iata_number')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="w-full px-4 py-3 bg-dark-800 border border-gray-700 rounded-lg text-white focus:border-purple-500 focus:outline-none @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">{{ __('messages.auth.phone') }}</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" placeholder="+965XXXXXXXX" class="w-full px-4 py-3 bg-dark-800 border border-gray-700 rounded-lg text-white focus:border-purple-500 focus:outline-none @error('phone') border-red-500 @enderror">
                    @error('phone')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Accountant WhatsApp</label>
                    <input type="text" name="accountant_whatsapp" value="{{ old('accountant_whatsapp') }}" placeholder="+965XXXXXXXX" class="w-full px-4 py-3 bg-dark-800 border border-gray-700 rounded-lg text-white focus:border-purple-500 focus:outline-none @error('accountant_whatsapp') border-red-500 @enderror">
                    @error('accountant_whatsapp')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">{{ __('messages.nav.sales_persons') }}</label>
                    <select name="sales_person_id" class="w-full px-4 py-3 bg-dark-800 border border-gray-700 rounded-lg text-white focus:border-purple-500 focus:outline-none">
                        <option value="">-- Select --</option>
                        @foreach($salesPersons as $sp)
                            <option value="{{ $sp->id }}" {{ old('sales_person_id') == $sp->id ? 'selected' : '' }}>{{ $sp->full_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-300 mb-2">Address</label>
                    <textarea name="address" rows="2" class="w-full px-4 py-3 bg-dark-800 border border-gray-700 rounded-lg text-white focus:border-purple-500 focus:outline-none @error('address') border-red-500 @enderror">{{ old('address') }}</textarea>
                    @error('address')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-300 mb-2">Notes</label>
                    <textarea name="notes" rows="3" class="w-full px-4 py-3 bg-dark-800 border border-gray-700 rounded-lg text-white focus:border-purple-500 focus:outline-none @error('notes') border-red-500 @enderror">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex justify-end gap-4 pt-4 border-t border-gray-700">
                <a href="{{ route('client.agents') }}" class="px-6 py-3 bg-gray-700 text-white rounded-lg hover:bg-gray-600 transition-colors">
                    {{ __('messages.common.cancel') }}
                </a>
                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-lg hover:from-purple-600 hover:to-pink-600 transition-all">
                    {{ __('messages.common.save') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@extends('client.layout')

@section('title', __('messages.common.edit') . ' - ' . $agent->company_name)

@section('content')
<div class="max-w-2xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <a href="{{ route('client.agents.show', $agent) }}" class="inline-flex items-center text-gray-400 hover:text-white transition-colors mb-4">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            {{ __('messages.common.back') }}
        </a>
        <h1 class="text-2xl font-bold text-white">{{ __('messages.common.edit') }} {{ $agent->company_name }}</h1>
    </div>

    <!-- Form -->
    <div class="glass-card rounded-xl p-6">
        <form action="{{ route('client.agents.update', $agent) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-300 mb-2">{{ __('messages.payments.customer_name') }} *</label>
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
                    @error('accountant_whatsapp')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">{{ __('messages.nav.sales_persons') }}</label>
                    <select name="sales_person_id" class="w-full px-4 py-3 bg-dark-800 border border-gray-700 rounded-lg text-white focus:border-purple-500 focus:outline-none">
                        <option value="">-- Select --</option>
                        @foreach($salesPersons as $sp)
                            <option value="{{ $sp->id }}" {{ old('sales_person_id', $agent->sales_person_id) == $sp->id ? 'selected' : '' }}>{{ $sp->full_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-300 mb-2">Address</label>
                    <textarea name="address" rows="2" class="w-full px-4 py-3 bg-dark-800 border border-gray-700 rounded-lg text-white focus:border-purple-500 focus:outline-none @error('address') border-red-500 @enderror">{{ old('address', $agent->address) }}</textarea>
                    @error('address')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-300 mb-2">Notes</label>
                    <textarea name="notes" rows="3" class="w-full px-4 py-3 bg-dark-800 border border-gray-700 rounded-lg text-white focus:border-purple-500 focus:outline-none @error('notes') border-red-500 @enderror">{{ old('notes', $agent->notes) }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $agent->is_active) ? 'checked' : '' }} class="w-5 h-5 rounded border-gray-600 bg-dark-800 text-purple-500 focus:ring-purple-500">
                        <span class="text-gray-300">{{ __('messages.active') }}</span>
                    </label>
                </div>
            </div>

            <div class="flex justify-end gap-4 pt-4 border-t border-gray-700">
                <a href="{{ route('client.agents.show', $agent) }}" class="px-6 py-3 bg-gray-700 text-white rounded-lg hover:bg-gray-600 transition-colors">
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

@extends('client.layout')

@section('title', __('messages.nav.settings'))

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <!-- Header -->
    <div>
        <h1 class="text-2xl font-bold text-white">{{ __('messages.nav.settings') }}</h1>
        <p class="text-gray-400">Manage your company settings and integrations</p>
    </div>

    <form action="{{ route('client.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Company Information -->
        <div class="glass-card rounded-xl p-6">
            <h2 class="text-lg font-semibold text-white mb-4">Company Information</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-300 mb-2">Company Name *</label>
                    <input type="text" name="name" value="{{ old('name', $client->name) }}" required class="w-full px-4 py-3 bg-dark-800 border border-gray-700 rounded-lg text-white focus:border-purple-500 focus:outline-none @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Company Email</label>
                    <input type="email" name="company_email" value="{{ old('company_email', $client->company_email) }}" class="w-full px-4 py-3 bg-dark-800 border border-gray-700 rounded-lg text-white focus:border-purple-500 focus:outline-none @error('company_email') border-red-500 @enderror">
                    @error('company_email')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">WhatsApp Number</label>
                    <input type="text" name="whatsapp_number" value="{{ old('whatsapp_number', $client->whatsapp_number) }}" placeholder="+965XXXXXXXX" class="w-full px-4 py-3 bg-dark-800 border border-gray-700 rounded-lg text-white focus:border-purple-500 focus:outline-none @error('whatsapp_number') border-red-500 @enderror">
                    @error('whatsapp_number')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-300 mb-2">Address</label>
                    <textarea name="address" rows="2" class="w-full px-4 py-3 bg-dark-800 border border-gray-700 rounded-lg text-white focus:border-purple-500 focus:outline-none @error('address') border-red-500 @enderror">{{ old('address', $client->address) }}</textarea>
                    @error('address')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-300 mb-2">Company Logo</label>
                    @if($client->logo_path)
                        <div class="mb-3">
                            <img src="{{ Storage::url($client->logo_path) }}" alt="Logo" class="max-h-16 rounded">
                        </div>
                    @endif
                    <input type="file" name="logo" accept="image/*" class="w-full px-4 py-3 bg-dark-800 border border-gray-700 rounded-lg text-white focus:border-purple-500 focus:outline-none @error('logo') border-red-500 @enderror">
                    <p class="mt-1 text-sm text-gray-500">Max size: 2MB. Recommended: 200x200px</p>
                    @error('logo')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Service Fee Configuration -->
        <div class="glass-card rounded-xl p-6">
            <h2 class="text-lg font-semibold text-white mb-4">Service Fee Configuration</h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Fee Type *</label>
                    <select name="service_fee_type" required class="w-full px-4 py-3 bg-dark-800 border border-gray-700 rounded-lg text-white focus:border-purple-500 focus:outline-none">
                        <option value="fixed" {{ old('service_fee_type', $client->service_fee_type) === 'fixed' ? 'selected' : '' }}>Fixed Amount (KWD)</option>
                        <option value="percentage" {{ old('service_fee_type', $client->service_fee_type) === 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Fee Value *</label>
                    <input type="number" name="service_fee_value" value="{{ old('service_fee_value', $client->service_fee_value) }}" required step="0.001" min="0" class="w-full px-4 py-3 bg-dark-800 border border-gray-700 rounded-lg text-white focus:border-purple-500 focus:outline-none @error('service_fee_value') border-red-500 @enderror">
                    @error('service_fee_value')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Fee Payer *</label>
                    <select name="service_fee_payer" required class="w-full px-4 py-3 bg-dark-800 border border-gray-700 rounded-lg text-white focus:border-purple-500 focus:outline-none">
                        <option value="agent" {{ old('service_fee_payer', $client->service_fee_payer) === 'agent' ? 'selected' : '' }}>Agent pays</option>
                        <option value="customer" {{ old('service_fee_payer', $client->service_fee_payer) === 'customer' ? 'selected' : '' }}>Customer pays</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- MyFatoorah Integration -->
        <div class="glass-card rounded-xl p-6">
            <h2 class="text-lg font-semibold text-white mb-4">MyFatoorah Integration</h2>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">API Key</label>
                    <input type="password" name="myfatoorah_api_key" placeholder="{{ $client->myfatoorahCredential ? '••••••••••••••••' : 'Enter your MyFatoorah API Key' }}" class="w-full px-4 py-3 bg-dark-800 border border-gray-700 rounded-lg text-white focus:border-purple-500 focus:outline-none @error('myfatoorah_api_key') border-red-500 @enderror">
                    <p class="mt-1 text-sm text-gray-500">Leave blank to keep existing key</p>
                    @error('myfatoorah_api_key')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="myfatoorah_test_mode" value="1" {{ old('myfatoorah_test_mode', $client->myfatoorahCredential?->is_test_mode ?? true) ? 'checked' : '' }} class="w-5 h-5 rounded border-gray-600 bg-dark-800 text-purple-500 focus:ring-purple-500">
                        <span class="text-gray-300">Test Mode</span>
                    </label>
                    <p class="mt-1 text-sm text-gray-500 ml-8">Enable for testing with sandbox credentials</p>
                </div>
            </div>
        </div>

        <!-- Submit -->
        <div class="flex justify-end">
            <button type="submit" class="px-6 py-3 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-lg hover:from-purple-600 hover:to-pink-600 transition-all font-semibold">
                {{ __('messages.common.save') }}
            </button>
        </div>
    </form>
</div>
@endsection

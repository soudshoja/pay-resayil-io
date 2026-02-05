@extends('platform.layout')
@section('title', 'Edit ' . $agency->agency_name)

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('platform.agencies.show', $agency) }}" class="inline-flex items-center gap-2 text-gray-400 hover:text-white mb-4">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Agency
        </a>
        <h1 class="text-2xl font-bold text-white">Edit Agency</h1>
    </div>

    @if($errors->any())
    <div class="mb-6 p-4 rounded-xl bg-red-500/10 border border-red-500/20">
        <ul class="list-disc list-inside text-red-400 text-sm">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('platform.agencies.update', $agency) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="glass-card rounded-2xl p-6">
            <h2 class="text-lg font-semibold text-white mb-4">Basic Information</h2>
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Agency Name *</label>
                    <input type="text" name="agency_name" value="{{ old('agency_name', $agency->agency_name) }}" required
                           class="w-full px-4 py-3 rounded-xl text-white">
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-2">IATA Number *</label>
                    <input type="text" name="iata_number" value="{{ old('iata_number', $agency->iata_number) }}" required
                           class="w-full px-4 py-3 rounded-xl text-white">
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Email</label>
                    <input type="email" name="company_email" value="{{ old('company_email', $agency->company_email) }}"
                           class="w-full px-4 py-3 rounded-xl text-white">
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Phone</label>
                    <input type="tel" name="phone" value="{{ old('phone', $agency->phone) }}"
                           class="w-full px-4 py-3 rounded-xl text-white" dir="ltr">
                </div>
            </div>
            <div class="mt-4">
                <label class="block text-sm text-gray-400 mb-2">Address</label>
                <textarea name="address" rows="2" class="w-full px-4 py-3 rounded-xl text-white">{{ old('address', $agency->address) }}</textarea>
            </div>
            <div class="mt-4">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $agency->is_active) ? 'checked' : '' }}
                           class="w-5 h-5 rounded border-gray-600 bg-gray-700 text-purple-500">
                    <span class="text-gray-300">Agency is Active</span>
                </label>
            </div>
        </div>

        <div class="glass-card rounded-2xl p-6">
            <h2 class="text-lg font-semibold text-white mb-4">MyFatoorah Configuration</h2>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm text-gray-400 mb-2">API Key</label>
                    <input type="text" name="myfatoorah_api_key"
                           value="{{ old('myfatoorah_api_key', $agency->myfatoorahCredential->api_key ?? '') }}"
                           placeholder="SK_KWT_..."
                           class="w-full px-4 py-3 rounded-xl text-white font-mono text-sm">
                    <p class="text-xs text-gray-500 mt-1">Leave empty to keep current or not configure</p>
                </div>
                <div>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="myfatoorah_test_mode" value="1"
                               {{ old('myfatoorah_test_mode', $agency->myfatoorahCredential->is_test_mode ?? true) ? 'checked' : '' }}
                               class="w-5 h-5 rounded border-gray-600 bg-gray-700 text-purple-500">
                        <span class="text-gray-300">Test Mode (Sandbox)</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="flex gap-4">
            <button type="submit" class="flex-1 gradient-btn py-3 rounded-xl text-white font-semibold">
                Update Agency
            </button>
            <a href="{{ route('platform.agencies.show', $agency) }}" class="px-6 py-3 rounded-xl border border-gray-600 text-gray-400 hover:text-white">
                Cancel
            </a>
        </div>
    </form>

    <!-- Danger Zone -->
    <div class="mt-8 glass-card rounded-2xl p-6 border border-red-500/20">
        <h2 class="text-lg font-semibold text-red-400 mb-4">Danger Zone</h2>
        <p class="text-gray-400 text-sm mb-4">Deleting an agency will remove all associated users and payment records. This action cannot be undone.</p>
        <form method="POST" action="{{ route('platform.agencies.delete', $agency) }}"
              onsubmit="return confirm('Are you sure you want to delete this agency? This action cannot be undone.')">
            @csrf
            @method('DELETE')
            <button type="submit" class="px-4 py-2 rounded-xl bg-red-500/10 border border-red-500/30 text-red-400 hover:bg-red-500/20">
                Delete Agency
            </button>
        </form>
    </div>
</div>
@endsection

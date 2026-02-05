<x-layouts.app :title="__('messages.settings.myfatoorah')">
    @section('page-title', __('messages.settings.myfatoorah'))

    <div class="max-w-2xl mx-auto">
        <div class="mb-8">
            <a href="{{ route('settings.index') }}" class="inline-flex items-center gap-2 text-gray-400 hover:text-white mb-4">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                {{ __('messages.common.back') }}
            </a>
            <h2 class="text-2xl font-bold text-white">{{ __('messages.settings.myfatoorah') }}</h2>
            <p class="text-gray-400">{{ __('messages.settings.myfatoorah_desc') }}</p>
        </div>

        <form method="POST" action="{{ route('settings.myfatoorah.update') }}" class="glass-card rounded-2xl p-8">
            @csrf @method('PUT')

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-300 mb-2">{{ __('messages.settings.api_key') }}</label>
                <textarea name="api_key" rows="3"
                          class="w-full px-4 py-3 bg-dark-700 border border-dark-500 rounded-xl text-white font-mono text-sm focus:border-purple-500"
                          placeholder="Bearer rLtt6JWvbUHDDhsZnfpAhpYk4dxYDQkbcPTyGaKp2TYqQgG7FGZ5Th_WD53Oq8Ebz6A..."
                          required>{{ old('api_key', $credential?->api_key) }}</textarea>
            </div>

            <div class="grid md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">{{ __('messages.settings.country_code') }}</label>
                    <select name="country_code"
                            class="w-full px-4 py-3 bg-dark-700 border border-dark-500 rounded-xl text-white focus:border-purple-500">
                        <option value="KWT" {{ ($credential?->country_code ?? 'KWT') === 'KWT' ? 'selected' : '' }}>Kuwait (KWT)</option>
                        <option value="SAU" {{ ($credential?->country_code ?? '') === 'SAU' ? 'selected' : '' }}>Saudi Arabia (SAU)</option>
                        <option value="UAE" {{ ($credential?->country_code ?? '') === 'UAE' ? 'selected' : '' }}>UAE (UAE)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">{{ __('messages.settings.mode') }}</label>
                    <div class="flex items-center gap-4 h-12">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="is_test_mode" value="1" {{ ($credential?->is_test_mode ?? true) ? 'checked' : '' }}
                                   class="text-purple-500 bg-dark-700 border-dark-500 focus:ring-purple-500">
                            <span class="text-gray-300">{{ __('messages.settings.test_mode') }}</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="is_test_mode" value="0" {{ !($credential?->is_test_mode ?? true) ? 'checked' : '' }}
                                   class="text-purple-500 bg-dark-700 border-dark-500 focus:ring-purple-500">
                            <span class="text-gray-300">{{ __('messages.settings.live_mode') }}</span>
                        </label>
                    </div>
                </div>
            </div>

            @if($credential?->last_verified_at)
                <div class="mb-6 p-4 rounded-xl bg-green-500/10 border border-green-500/30">
                    <p class="text-green-400 text-sm">
                        {{ __('messages.settings.last_verified') }}: {{ $credential->last_verified_at->format('Y-m-d H:i') }}
                    </p>
                </div>
            @endif

            <div class="flex gap-4">
                <button type="submit"
                        class="flex-1 py-3 px-6 rounded-xl bg-gradient-to-r from-purple-500 to-pink-500 text-white font-semibold hover:shadow-lg transition">
                    {{ __('messages.common.save') }}
                </button>
                <button type="button"
                        onclick="testCredentials()"
                        class="px-6 py-3 rounded-xl border border-green-500 text-green-400 hover:bg-green-500/10 transition">
                    {{ __('messages.settings.test_connection') }}
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        async function testCredentials() {
            try {
                const response = await fetch('{{ route("settings.myfatoorah.test") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    }
                });
                const data = await response.json();
                alert(data.message);
            } catch (error) {
                alert('Connection failed');
            }
        }
    </script>
    @endpush
</x-layouts.app>

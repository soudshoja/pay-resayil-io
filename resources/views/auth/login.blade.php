<x-layouts.guest :title="__('messages.auth.login')">
    <h2 class="text-2xl font-bold text-white text-center mb-2">{{ __('messages.auth.welcome') }}</h2>
    <p class="text-gray-400 text-center mb-8">{{ __('messages.auth.login_subtitle') }}</p>

    <form method="POST" action="{{ route('login.send-otp') }}" x-data="{ loading: false }" @submit="loading = true">
        @csrf

        <div class="mb-6">
            <label for="phone" class="block text-sm font-medium text-gray-300 mb-2">
                {{ __('messages.auth.phone') }}
            </label>
            <div class="relative">
                <span class="absolute inset-y-0 start-0 flex items-center ps-4 text-gray-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                </span>
                <input type="tel"
                       id="phone"
                       name="phone"
                       value="{{ old('phone') }}"
                       placeholder="+965XXXXXXXX"
                       class="w-full ps-12 pe-4 py-3 bg-dark-700 border border-dark-500 rounded-xl text-white placeholder-gray-500 focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 input-glow transition"
                       required
                       autofocus
                       dir="ltr">
            </div>
            @error('phone')
                <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit"
                class="w-full py-3 px-4 rounded-xl btn-gradient text-white font-semibold flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed"
                :disabled="loading">
            <template x-if="loading">
                <svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </template>
            <template x-if="!loading">
                <span>{{ __('messages.auth.send_otp') }}</span>
            </template>
        </button>
    </form>

    <div class="mt-6 text-center">
        <p class="text-sm text-gray-500">
            {{ __('messages.auth.otp_info') }}
        </p>
    </div>
</x-layouts.guest>

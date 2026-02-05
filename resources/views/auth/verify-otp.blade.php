<x-layouts.guest :title="__('messages.auth.verify_otp')">
    <h2 class="text-2xl font-bold text-white text-center mb-2">{{ __('messages.auth.verify_otp') }}</h2>
    <p class="text-gray-400 text-center mb-8">{{ __('messages.auth.otp_sent_to', ['phone' => $phone]) }}</p>

    <form method="POST" action="{{ route('verify-otp') }}" x-data="{ loading: false, otp: '' }" @submit="loading = true">
        @csrf
        <input type="hidden" name="phone" value="{{ $phone }}">

        <div class="mb-6">
            <label for="otp" class="block text-sm font-medium text-gray-300 mb-2">
                {{ __('messages.auth.enter_otp') }}
            </label>
            <input type="text"
                   id="otp"
                   name="otp"
                   x-model="otp"
                   maxlength="6"
                   placeholder="000000"
                   class="w-full py-4 px-4 bg-dark-700 border border-dark-500 rounded-xl text-white text-center text-2xl tracking-[0.5em] font-mono placeholder-gray-600 focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 input-glow transition"
                   required
                   autofocus
                   dir="ltr"
                   pattern="[0-9]{6}"
                   inputmode="numeric">
            @error('otp')
                <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit"
                class="w-full py-3 px-4 rounded-xl btn-gradient text-white font-semibold flex items-center justify-center gap-2 disabled:opacity-50"
                :disabled="loading || otp.length !== 6">
            <template x-if="loading">
                <svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </template>
            <template x-if="!loading">
                <span>{{ __('messages.auth.verify') }}</span>
            </template>
        </button>
    </form>

    <!-- Resend OTP -->
    <div class="mt-6 text-center" x-data="{ countdown: 60, canResend: false }" x-init="
        let timer = setInterval(() => {
            countdown--;
            if (countdown <= 0) {
                clearInterval(timer);
                canResend = true;
            }
        }, 1000);
    ">
        <template x-if="!canResend">
            <p class="text-sm text-gray-500">
                {{ __('messages.auth.resend_in') }} <span x-text="countdown" class="text-purple-400 font-medium"></span> {{ __('messages.auth.seconds') }}
            </p>
        </template>
        <template x-if="canResend">
            <form method="POST" action="{{ route('resend-otp') }}">
                @csrf
                <input type="hidden" name="phone" value="{{ $phone }}">
                <button type="submit" class="text-purple-400 hover:text-purple-300 font-medium">
                    {{ __('messages.auth.resend_otp') }}
                </button>
            </form>
        </template>
    </div>

    <!-- Back to login -->
    <div class="mt-4 text-center">
        <a href="{{ route('login') }}" class="text-sm text-gray-400 hover:text-white">
            &larr; {{ __('messages.auth.back_to_login') }}
        </a>
    </div>
</x-layouts.guest>

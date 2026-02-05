<x-layouts.guest :title="__('messages.auth.login')">
    <h2 class="text-2xl font-bold text-white text-center mb-2">{{ __('messages.auth.welcome') }}</h2>
    <p class="text-gray-400 text-center mb-6">{{ __('messages.auth.subtitle') }}</p>

    <!-- Login Tabs -->
    <div x-data="{ activeTab: 'email' }" class="space-y-6">
        <!-- Tab Buttons -->
        <div class="flex rounded-xl bg-dark-700 p-1">
            <button type="button"
                    @click="activeTab = 'email'"
                    :class="activeTab === 'email' ? 'bg-gradient-to-r from-purple-600 to-pink-600 text-white' : 'text-gray-400 hover:text-white'"
                    class="flex-1 py-2.5 px-4 rounded-lg text-sm font-medium transition-all flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                {{ __('messages.auth.email_login') ?? 'Email Login' }}
            </button>
            <button type="button"
                    @click="activeTab = 'phone'"
                    :class="activeTab === 'phone' ? 'bg-gradient-to-r from-purple-600 to-pink-600 text-white' : 'text-gray-400 hover:text-white'"
                    class="flex-1 py-2.5 px-4 rounded-lg text-sm font-medium transition-all flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
                {{ __('messages.auth.phone_login') ?? 'Phone Login' }}
            </button>
        </div>

        <!-- Email Login Form -->
        <div x-show="activeTab === 'email'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
            <form method="POST" action="{{ route('login.email') }}" x-data="{ loading: false }" @submit="loading = true">
                @csrf

                <!-- Email -->
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-300 mb-2">
                        {{ __('messages.auth.email') ?? 'Email' }}
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 start-0 flex items-center ps-4 text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </span>
                        <input type="email"
                               id="email"
                               name="email"
                               value="{{ old('email') }}"
                               placeholder="your@email.com"
                               class="w-full ps-12 pe-4 py-3 bg-dark-700 border border-dark-500 rounded-xl text-white placeholder-gray-500 focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 input-glow transition"
                               required
                               autofocus>
                    </div>
                    @error('email')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-300 mb-2">
                        {{ __('messages.auth.password') ?? 'Password' }}
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 start-0 flex items-center ps-4 text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </span>
                        <input type="password"
                               id="password"
                               name="password"
                               placeholder="••••••••"
                               class="w-full ps-12 pe-4 py-3 bg-dark-700 border border-dark-500 rounded-xl text-white placeholder-gray-500 focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 input-glow transition"
                               required>
                    </div>
                    @error('password')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center mb-6">
                    <input type="checkbox"
                           id="remember"
                           name="remember"
                           class="w-4 h-4 rounded border-dark-500 bg-dark-700 text-purple-600 focus:ring-purple-500 focus:ring-offset-dark-800">
                    <label for="remember" class="ms-2 text-sm text-gray-400">
                        {{ __('messages.auth.remember_me') ?? 'Remember me' }}
                    </label>
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
                        <span>{{ __('messages.auth.login_button') ?? 'Sign In' }}</span>
                    </template>
                </button>
            </form>

            <div class="mt-4 text-center">
                <p class="text-sm text-gray-500">
                    {{ __('messages.auth.email_login_info') ?? 'For client admins, sales persons & accountants' }}
                </p>
            </div>
        </div>

        <!-- Phone OTP Login Form -->
        <div x-show="activeTab === 'phone'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
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

            <div class="mt-4 text-center">
                <p class="text-sm text-gray-500">
                    {{ __('messages.auth.otp_info') }}
                </p>
            </div>
        </div>
    </div>
</x-layouts.guest>

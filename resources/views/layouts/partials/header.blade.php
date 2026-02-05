<!-- Header -->
<header class="h-16 bg-dark-800/80 backdrop-blur-xl border-b border-dark-600 flex items-center justify-between px-4 md:px-6 sticky top-0 z-30">
    <!-- Left: Menu Toggle & Page Title -->
    <div class="flex items-center gap-4">
        <button @click="sidebarOpen = !sidebarOpen" class="text-gray-400 hover:text-white transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>

        <div class="hidden sm:block">
            <h1 class="text-lg font-semibold text-white">
                @yield('page-title', __('messages.nav.dashboard'))
            </h1>
        </div>
    </div>

    <!-- Right: Actions -->
    <div class="flex items-center gap-4">
        <!-- Quick Create Payment -->
        <a href="{{ route('payments.create') }}"
           class="hidden sm:flex items-center gap-2 px-4 py-2 rounded-xl bg-gradient-to-r from-purple-500 to-pink-500 text-white font-medium hover:shadow-lg hover:shadow-purple-500/30 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            <span>{{ __('messages.payments.new') }}</span>
        </a>

        <!-- Language Switcher -->
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" class="flex items-center gap-2 px-3 py-2 rounded-lg text-gray-400 hover:text-white hover:bg-dark-700 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/>
                </svg>
                <span class="hidden md:inline">{{ app()->getLocale() === 'ar' ? 'العربية' : 'English' }}</span>
            </button>

            <div x-show="open"
                 x-cloak
                 @click.away="open = false"
                 class="absolute end-0 mt-2 w-36 rounded-xl glass-card shadow-xl py-2 z-50">
                <a href="{{ route('language.switch', 'en') }}"
                   class="flex items-center gap-2 px-4 py-2 hover:bg-purple-500/10 {{ app()->getLocale() === 'en' ? 'text-purple-400' : 'text-gray-300' }}">
                    <span>🇺🇸</span> English
                </a>
                <a href="{{ route('language.switch', 'ar') }}"
                   class="flex items-center gap-2 px-4 py-2 hover:bg-purple-500/10 {{ app()->getLocale() === 'ar' ? 'text-purple-400' : 'text-gray-300' }}">
                    <span>🇰🇼</span> العربية
                </a>
            </div>
        </div>

        <!-- User Menu -->
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" class="flex items-center gap-2">
                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white font-bold text-sm">
                    {{ substr(auth()->user()->full_name, 0, 1) }}
                </div>
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <div x-show="open"
                 x-cloak
                 @click.away="open = false"
                 class="absolute end-0 mt-2 w-48 rounded-xl glass-card shadow-xl py-2 z-50">
                <div class="px-4 py-3 border-b border-dark-600">
                    <p class="text-sm font-medium text-white">{{ auth()->user()->full_name }}</p>
                    <p class="text-xs text-gray-400">{{ auth()->user()->username }}</p>
                </div>

                <a href="{{ route('settings.index') }}"
                   class="flex items-center gap-2 px-4 py-2 text-gray-300 hover:text-white hover:bg-purple-500/10">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    {{ __('messages.nav.settings') }}
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 text-red-400 hover:text-red-300 hover:bg-red-500/10">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        {{ __('messages.auth.logout') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>

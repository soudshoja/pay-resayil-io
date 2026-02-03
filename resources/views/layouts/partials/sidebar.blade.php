<!-- Sidebar -->
<aside class="fixed inset-y-0 start-0 z-50 w-64 bg-dark-800 border-e border-dark-600 transform transition-transform duration-300"
       :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
       x-cloak>

    <!-- Logo -->
    <div class="h-16 flex items-center justify-between px-4 border-b border-dark-600">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center shadow-lg shadow-purple-500/30">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <span class="text-xl font-bold gradient-text">Collect Resayil</span>
        </a>
        <button @click="sidebarOpen = false" class="md:hidden text-gray-400 hover:text-white">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    <!-- Navigation -->
    <nav class="p-4 space-y-2">
        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}"
           class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-gray-300 hover:text-white {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
            </svg>
            <span>{{ __('messages.nav.dashboard') }}</span>
        </a>

        <!-- Payments -->
        <a href="{{ route('payments.index') }}"
           class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-gray-300 hover:text-white {{ request()->routeIs('payments.*') ? 'active' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
            </svg>
            <span>{{ __('messages.nav.payments') }}</span>
        </a>

        <!-- Team (Admin only) -->
        @if(auth()->user()->hasRole(['admin', 'super_admin']))
        <a href="{{ route('team.index') }}"
           class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-gray-300 hover:text-white {{ request()->routeIs('team.*') ? 'active' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
            <span>{{ __('messages.nav.team') }}</span>
        </a>
        @endif

        <!-- Agencies (Super Admin only) -->
        @if(auth()->user()->isSuperAdmin())
        <a href="{{ route('agencies.index') }}"
           class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-gray-300 hover:text-white {{ request()->routeIs('agencies.*') ? 'active' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
            <span>{{ __('messages.nav.agencies') }}</span>
        </a>
        @endif

        <!-- Settings -->
        <a href="{{ route('settings.index') }}"
           class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-gray-300 hover:text-white {{ request()->routeIs('settings.*') ? 'active' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span>{{ __('messages.nav.settings') }}</span>
        </a>
    </nav>

    <!-- User Info (Bottom) -->
    <div class="absolute bottom-0 start-0 end-0 p-4 border-t border-dark-600">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white font-bold">
                {{ substr(auth()->user()->full_name, 0, 1) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-white truncate">{{ auth()->user()->full_name }}</p>
                <p class="text-xs text-gray-400 truncate">{{ auth()->user()->agency?->agency_name ?? 'Super Admin' }}</p>
            </div>
        </div>
    </div>
</aside>

<!-- Mobile Overlay -->
<div x-show="sidebarOpen"
     x-cloak
     @click="sidebarOpen = false"
     class="fixed inset-0 bg-black/60 z-40 md:hidden"></div>

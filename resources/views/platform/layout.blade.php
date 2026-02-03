<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Platform Admin') - Collect Resayil.io</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        * { font-family: 'Space Grotesk', sans-serif; }
        body { background: linear-gradient(135deg, #0f0f1a 0%, #1a1a2e 50%, #16213e 100%); min-height: 100vh; }
        .glass-card { background: rgba(255, 255, 255, 0.03); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.08); }
        .glass-card-hover:hover { background: rgba(255, 255, 255, 0.06); border-color: rgba(168, 85, 247, 0.3); }
        .gradient-text { background: linear-gradient(135deg, #a855f7 0%, #ec4899 50%, #f97316 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        .gradient-btn { background: linear-gradient(135deg, #a855f7 0%, #ec4899 100%); }
        .gradient-btn:hover { box-shadow: 0 10px 30px rgba(168, 85, 247, 0.3); transform: translateY(-1px); }
        .sidebar-link { transition: all 0.2s; }
        .sidebar-link:hover, .sidebar-link.active { background: rgba(168, 85, 247, 0.2); border-color: rgba(168, 85, 247, 0.5); }
        .stat-card { background: linear-gradient(135deg, rgba(168, 85, 247, 0.1) 0%, rgba(236, 72, 153, 0.05) 100%); border: 1px solid rgba(168, 85, 247, 0.2); }
        .table-row:hover { background: rgba(255, 255, 255, 0.02); }
        input, select, textarea { background: rgba(255, 255, 255, 0.05) !important; border: 1px solid rgba(255, 255, 255, 0.1) !important; }
        input:focus, select:focus, textarea:focus { border-color: #a855f7 !important; outline: none; box-shadow: 0 0 0 2px rgba(168, 85, 247, 0.2); }
    </style>
</head>
<body class="text-white" x-data="{ sidebarOpen: true }">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-64 glass-card border-r border-white/10 p-4 flex flex-col" :class="{ 'hidden': !sidebarOpen }">
            <!-- Logo -->
            <div class="flex items-center gap-3 mb-8 px-2">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="font-bold gradient-text">Platform Admin</h1>
                    <p class="text-xs text-gray-500">collect.resayil.io</p>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 space-y-1">
                <a href="{{ route('platform.dashboard') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl border border-transparent {{ request()->routeIs('platform.dashboard') ? 'active' : '' }}">
                    <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                    <span class="text-sm font-medium text-gray-300">Dashboard</span>
                </a>

                <a href="{{ route('platform.agencies') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl border border-transparent {{ request()->routeIs('platform.agencies*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <span class="text-sm font-medium text-gray-300">Agencies</span>
                </a>

                <a href="{{ route('platform.users') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl border border-transparent {{ request()->routeIs('platform.users*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <span class="text-sm font-medium text-gray-300">Users</span>
                </a>

                <a href="{{ route('platform.payments') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl border border-transparent {{ request()->routeIs('platform.payments*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <span class="text-sm font-medium text-gray-300">Payments</span>
                </a>

                <a href="{{ route('platform.settings') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl border border-transparent {{ request()->routeIs('platform.settings*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span class="text-sm font-medium text-gray-300">Settings</span>
                </a>

                <a href="{{ route('platform.logs') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl border border-transparent {{ request()->routeIs('platform.logs*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <span class="text-sm font-medium text-gray-300">Activity Logs</span>
                </a>
            </nav>

            <!-- User Info & Logout -->
            <div class="border-t border-white/10 pt-4 mt-4">
                @if(session('impersonating_from'))
                <form method="POST" action="{{ route('platform.stop-impersonation') }}" class="mb-2">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 rounded-xl bg-yellow-500/20 text-yellow-400 text-sm hover:bg-yellow-500/30 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                        </svg>
                        Stop Impersonation
                    </button>
                </form>
                @endif

                <div class="flex items-center gap-3 px-2 mb-3">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-sm font-bold">
                        {{ substr(Auth::user()->full_name ?? 'U', 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-white truncate">{{ Auth::user()->full_name ?? 'User' }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('platform.logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 px-3 py-2 rounded-xl border border-red-500/30 text-red-400 text-sm hover:bg-red-500/10 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6 overflow-auto">
            <!-- Alerts -->
            @if(session('success'))
            <div class="mb-6 p-4 rounded-xl bg-green-500/10 border border-green-500/20 flex items-center gap-3">
                <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="text-green-400">{{ session('success') }}</span>
            </div>
            @endif

            @if(session('error'))
            <div class="mb-6 p-4 rounded-xl bg-red-500/10 border border-red-500/20 flex items-center gap-3">
                <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="text-red-400">{{ session('error') }}</span>
            </div>
            @endif

            @if(session('info'))
            <div class="mb-6 p-4 rounded-xl bg-blue-500/10 border border-blue-500/20 flex items-center gap-3">
                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="text-blue-400">{{ session('info') }}</span>
            </div>
            @endif

            @yield('content')
        </main>
    </div>
</body>
</html>

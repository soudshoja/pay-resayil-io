<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Agent Portal') - {{ config('app.name') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        dark: { 800: '#1a1a24', 900: '#0f0f17' }
                    }
                }
            }
        }
    </script>
    <style>
        [dir="rtl"] { font-family: 'Tajawal', sans-serif; }
        [dir="ltr"] { font-family: 'Space Grotesk', sans-serif; }
        .glass-card { background: rgba(26, 26, 36, 0.9); backdrop-filter: blur(20px); border: 1px solid rgba(168, 85, 247, 0.2); }
    </style>
</head>
<body class="bg-[#0a0a0f] min-h-screen text-gray-100">
    <!-- Header -->
    <header class="sticky top-0 z-50 bg-dark-900/80 backdrop-blur-lg border-b border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-8">
                    <a href="{{ route('agent.dashboard') }}" class="text-xl font-bold bg-gradient-to-r from-purple-400 to-pink-400 bg-clip-text text-transparent">
                        Collect Resayil
                    </a>
                    @if(session('agent'))
                    <nav class="hidden md:flex items-center gap-6">
                        <a href="{{ route('agent.dashboard') }}" class="text-gray-300 hover:text-white transition-colors {{ request()->routeIs('agent.dashboard') ? 'text-white' : '' }}">{{ __('messages.nav.dashboard') }}</a>
                        <a href="{{ route('agent.payments') }}" class="text-gray-300 hover:text-white transition-colors {{ request()->routeIs('agent.payments') ? 'text-white' : '' }}">{{ __('messages.nav.payments') }}</a>
                        <a href="{{ route('agent.phones') }}" class="text-gray-300 hover:text-white transition-colors {{ request()->routeIs('agent.phones') ? 'text-white' : '' }}">Authorized Phones</a>
                        <a href="{{ route('agent.settings') }}" class="text-gray-300 hover:text-white transition-colors {{ request()->routeIs('agent.settings') ? 'text-white' : '' }}">{{ __('messages.nav.settings') }}</a>
                    </nav>
                    @endif
                </div>
                <div class="flex items-center gap-4">
                    @if(session('agent'))
                        <span class="text-sm text-gray-400">{{ session('agent')->company_name }}</span>
                        <span class="px-2 py-1 text-xs rounded-full bg-green-500/20 text-green-400">{{ __('messages.roles.agent') }}</span>
                        <form action="{{ route('agent.logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-400 hover:text-white transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                            </button>
                        </form>
                    @else
                        <a href="{{ route('agent.login') }}" class="text-gray-300 hover:text-white transition-colors">Login</a>
                        <a href="{{ route('agent.register.step1') }}" class="px-4 py-2 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-lg hover:from-purple-600 hover:to-pink-600 transition-all">Register</a>
                    @endif
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-500/20 border border-green-500/30 rounded-lg text-green-400">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 bg-red-500/20 border border-red-500/30 rounded-lg text-red-400">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>
</body>
</html>

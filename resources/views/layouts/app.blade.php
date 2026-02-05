<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Dashboard' }} - {{ config('app.name') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Cairo:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Space Grotesk', 'Cairo', 'sans-serif'],
                    },
                    colors: {
                        dark: {
                            900: '#0a0a0f',
                            800: '#12121a',
                            700: '#1a1a24',
                            600: '#24242f',
                            500: '#2f2f3d',
                        },
                        accent: {
                            purple: '#a855f7',
                            pink: '#ec4899',
                            cyan: '#22d3ee',
                        }
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [dir="rtl"] { font-family: 'Cairo', 'Space Grotesk', sans-serif; }
        [dir="ltr"] { font-family: 'Space Grotesk', 'Cairo', sans-serif; }

        .gradient-text {
            background: linear-gradient(135deg, #a855f7 0%, #ec4899 50%, #22d3ee 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .gradient-border {
            position: relative;
        }
        .gradient-border::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: inherit;
            padding: 1px;
            background: linear-gradient(135deg, #a855f7, #ec4899, #22d3ee);
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            pointer-events: none;
        }

        .glass-card {
            background: rgba(26, 26, 36, 0.8);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(168, 85, 247, 0.1);
        }

        .glow-purple {
            box-shadow: 0 0 20px rgba(168, 85, 247, 0.3);
        }

        .glow-pink {
            box-shadow: 0 0 20px rgba(236, 72, 153, 0.3);
        }

        .animate-gradient {
            background-size: 200% 200%;
            animation: gradient 8s ease infinite;
        }

        @keyframes gradient {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        .mesh-bg {
            background-color: #0a0a0f;
            background-image:
                radial-gradient(at 40% 20%, rgba(168, 85, 247, 0.15) 0px, transparent 50%),
                radial-gradient(at 80% 0%, rgba(236, 72, 153, 0.1) 0px, transparent 50%),
                radial-gradient(at 0% 50%, rgba(34, 211, 238, 0.1) 0px, transparent 50%),
                radial-gradient(at 80% 50%, rgba(168, 85, 247, 0.1) 0px, transparent 50%),
                radial-gradient(at 0% 100%, rgba(236, 72, 153, 0.1) 0px, transparent 50%);
        }

        .sidebar-link {
            transition: all 0.3s ease;
        }
        .sidebar-link:hover {
            background: linear-gradient(90deg, rgba(168, 85, 247, 0.1), transparent);
            border-left: 3px solid #a855f7;
        }
        .sidebar-link.active {
            background: linear-gradient(90deg, rgba(168, 85, 247, 0.2), transparent);
            border-left: 3px solid #a855f7;
        }
        [dir="rtl"] .sidebar-link:hover,
        [dir="rtl"] .sidebar-link.active {
            border-left: none;
            border-right: 3px solid #a855f7;
            background: linear-gradient(-90deg, rgba(168, 85, 247, 0.2), transparent);
        }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #12121a; }
        ::-webkit-scrollbar-thumb { background: #a855f7; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #ec4899; }
    </style>

    @stack('styles')
</head>
<body class="mesh-bg min-h-screen text-gray-100 antialiased">
    <div class="flex min-h-screen" x-data="{ sidebarOpen: true }">
        <!-- Sidebar -->
        @include('layouts.partials.sidebar')

        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-h-screen" :class="sidebarOpen ? 'md:ms-64' : ''">
            <!-- Header -->
            @include('layouts.partials.header')

            <!-- Page Content -->
            <main class="flex-1 p-4 md:p-6 lg:p-8">
                <!-- Alerts -->
                @if(session('success'))
                    <div class="mb-6 p-4 rounded-xl glass-card border-l-4 border-green-500 flex items-center gap-3" x-data="{ show: true }" x-show="show" x-transition>
                        <svg class="w-6 h-6 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="flex-1">{{ session('success') }}</span>
                        <button @click="show = false" class="text-gray-400 hover:text-white">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-6 p-4 rounded-xl glass-card border-l-4 border-red-500" x-data="{ show: true }" x-show="show" x-transition>
                        <div class="flex items-center gap-3">
                            <svg class="w-6 h-6 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div class="flex-1">
                                @foreach($errors->all() as $error)
                                    <p>{{ $error }}</p>
                                @endforeach
                            </div>
                            <button @click="show = false" class="text-gray-400 hover:text-white">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                @endif

                {{ $slot }}
            </main>

            <!-- Footer -->
            @include('layouts.partials.footer')
        </div>
    </div>

    @stack('scripts')
</body>
</html>

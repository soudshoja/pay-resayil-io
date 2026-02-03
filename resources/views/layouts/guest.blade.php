<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Login' }} - {{ config('app.name') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Cairo:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Space Grotesk', 'Cairo', 'sans-serif'],
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

        .glass-card {
            background: rgba(26, 26, 36, 0.9);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(168, 85, 247, 0.2);
        }

        .mesh-bg {
            background-color: #0a0a0f;
            background-image:
                radial-gradient(at 40% 20%, rgba(168, 85, 247, 0.2) 0px, transparent 50%),
                radial-gradient(at 80% 0%, rgba(236, 72, 153, 0.15) 0px, transparent 50%),
                radial-gradient(at 0% 50%, rgba(34, 211, 238, 0.1) 0px, transparent 50%),
                radial-gradient(at 80% 50%, rgba(168, 85, 247, 0.1) 0px, transparent 50%),
                radial-gradient(at 0% 100%, rgba(236, 72, 153, 0.15) 0px, transparent 50%);
        }

        .input-glow:focus {
            box-shadow: 0 0 0 2px rgba(168, 85, 247, 0.3);
        }

        .btn-gradient {
            background: linear-gradient(135deg, #a855f7, #ec4899);
            transition: all 0.3s ease;
        }
        .btn-gradient:hover {
            background: linear-gradient(135deg, #9333ea, #db2777);
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(168, 85, 247, 0.4);
        }

        .floating-shape {
            position: absolute;
            border-radius: 50%;
            filter: blur(60px);
            animation: float 15s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            25% { transform: translate(20px, -20px) rotate(5deg); }
            50% { transform: translate(-10px, 10px) rotate(-5deg); }
            75% { transform: translate(15px, 15px) rotate(3deg); }
        }
    </style>
</head>
<body class="mesh-bg min-h-screen flex items-center justify-center p-4 text-gray-100 antialiased overflow-hidden">
    <!-- Floating Shapes -->
    <div class="floating-shape w-96 h-96 bg-purple-600/20 -top-20 -left-20 fixed"></div>
    <div class="floating-shape w-80 h-80 bg-pink-600/20 -bottom-20 -right-20 fixed" style="animation-delay: -5s;"></div>
    <div class="floating-shape w-64 h-64 bg-cyan-600/15 top-1/2 left-1/4 fixed" style="animation-delay: -10s;"></div>

    <div class="w-full max-w-md relative z-10">
        <!-- Logo & Title -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-gradient-to-br from-purple-500 to-pink-500 mb-4 shadow-lg shadow-purple-500/30">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <h1 class="text-3xl font-bold gradient-text">Pay Resayil</h1>
            <p class="text-gray-400 mt-2">{{ __('messages.auth.subtitle') }}</p>
        </div>

        <!-- Card -->
        <div class="glass-card rounded-2xl p-8 shadow-2xl">
            {{ $slot }}
        </div>

        <!-- Language Switcher -->
        <div class="flex justify-center gap-4 mt-6">
            <a href="{{ route('language.switch', 'en') }}"
               class="px-4 py-2 rounded-lg {{ app()->getLocale() === 'en' ? 'bg-purple-500/20 text-purple-400' : 'text-gray-400 hover:text-white' }} transition">
                English
            </a>
            <a href="{{ route('language.switch', 'ar') }}"
               class="px-4 py-2 rounded-lg {{ app()->getLocale() === 'ar' ? 'bg-purple-500/20 text-purple-400' : 'text-gray-400 hover:text-white' }} transition">
                العربية
            </a>
        </div>

        <!-- Footer -->
        <p class="text-center text-gray-500 text-sm mt-6">
            &copy; {{ date('Y') }} Resayil.io - {{ __('messages.footer.rights') }}
        </p>
    </div>
</body>
</html>

@props(['title' => 'Login'])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }} - {{ config('app.name') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: { sans: ['Space Grotesk', 'Tajawal', 'sans-serif'] },
                    colors: {
                        dark: { 500: '#2a2a3a', 700: '#1f1f2e', 800: '#1a1a24', 900: '#0a0a0f' }
                    }
                }
            }
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [dir="rtl"] { font-family: 'Tajawal', 'Space Grotesk', sans-serif; }
        [dir="ltr"] { font-family: 'Space Grotesk', 'Tajawal', sans-serif; }
        body { background: linear-gradient(135deg, #0a0a0f 0%, #1a1a24 50%, #0f0f1a 100%); }
        .glass-card { background: rgba(26, 26, 36, 0.8); backdrop-filter: blur(20px); border: 1px solid rgba(168, 85, 247, 0.2); }
        .btn-gradient { background: linear-gradient(135deg, #a855f7, #ec4899); }
        .btn-gradient:hover { background: linear-gradient(135deg, #9333ea, #db2777); box-shadow: 0 10px 40px rgba(168, 85, 247, 0.3); }
        .input-glow:focus { box-shadow: 0 0 20px rgba(168, 85, 247, 0.15); }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="min-h-screen text-gray-100 antialiased flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <a href="/" class="inline-flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <span class="text-2xl font-bold bg-gradient-to-r from-purple-400 via-pink-400 to-cyan-400 bg-clip-text text-transparent">
                    Collect Resayil
                </span>
            </a>
        </div>

        <div class="glass-card rounded-2xl p-8">
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-500/10 border border-green-500/20 rounded-xl text-green-400 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 bg-red-500/10 border border-red-500/20 rounded-xl text-red-400 text-sm">
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 p-4 bg-red-500/10 border border-red-500/20 rounded-xl text-red-400 text-sm">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{ $slot }}
        </div>

        <p class="text-center text-gray-500 text-sm mt-8">
            &copy; {{ date('Y') }} Collect Resayil. All rights reserved.
        </p>
    </div>
</body>
</html>

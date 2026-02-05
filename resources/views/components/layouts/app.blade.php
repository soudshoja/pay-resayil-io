@props(['title' => 'Dashboard'])

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
        .glass-card { background: rgba(26, 26, 36, 0.8); backdrop-filter: blur(10px); border: 1px solid rgba(168, 85, 247, 0.1); }
        .btn-gradient { background: linear-gradient(135deg, #a855f7, #ec4899); }
        .btn-gradient:hover { background: linear-gradient(135deg, #9333ea, #db2777); }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-dark-900 text-gray-100 antialiased">
    <div class="min-h-screen p-4 md:p-8">
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-500/10 border border-green-500/20 rounded-xl text-green-400">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 p-4 bg-red-500/10 border border-red-500/20 rounded-xl text-red-400">
                {{ session('error') }}
            </div>
        @endif

        {{ $slot }}
    </div>
</body>
</html>

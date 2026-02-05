<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('messages.payments.success_title') }} - {{ config('app.name') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Cairo:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        [dir="rtl"] { font-family: 'Cairo', 'Space Grotesk', sans-serif; }
        [dir="ltr"] { font-family: 'Space Grotesk', 'Cairo', sans-serif; }
        .mesh-bg {
            background-color: #0a0a0f;
            background-image:
                radial-gradient(at 40% 20%, rgba(34, 197, 94, 0.2) 0px, transparent 50%),
                radial-gradient(at 80% 50%, rgba(34, 197, 94, 0.1) 0px, transparent 50%);
        }
        @keyframes check {
            0% { transform: scale(0) rotate(-45deg); opacity: 0; }
            50% { transform: scale(1.2) rotate(-45deg); }
            100% { transform: scale(1) rotate(0deg); opacity: 1; }
        }
        .animate-check { animation: check 0.6s ease-out forwards; }
    </style>
</head>
<body class="mesh-bg min-h-screen flex items-center justify-center p-4 text-gray-100">
    <div class="text-center max-w-md">
        <div class="w-24 h-24 mx-auto mb-8 rounded-full bg-green-500/20 flex items-center justify-center">
            <svg class="w-12 h-12 text-green-400 animate-check" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
            </svg>
        </div>

        <h1 class="text-3xl font-bold text-white mb-4">{{ __('messages.payments.success_title') }}</h1>
        <p class="text-gray-400 text-lg mb-8">{{ __('messages.payments.success_message') }}</p>

        <div class="bg-green-500/10 border border-green-500/30 rounded-2xl p-6 mb-8">
            <p class="text-green-400">{{ __('messages.payments.success_note') }}</p>
        </div>

        <p class="text-gray-500 text-sm">{{ __('messages.payments.close_window') }}</p>
    </div>
</body>
</html>

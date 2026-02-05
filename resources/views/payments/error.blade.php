<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('messages.payments.error_title') }} - {{ config('app.name') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Cairo:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        [dir="rtl"] { font-family: 'Cairo', 'Space Grotesk', sans-serif; }
        [dir="ltr"] { font-family: 'Space Grotesk', 'Cairo', sans-serif; }
        .mesh-bg {
            background-color: #0a0a0f;
            background-image:
                radial-gradient(at 40% 20%, rgba(239, 68, 68, 0.2) 0px, transparent 50%),
                radial-gradient(at 80% 50%, rgba(239, 68, 68, 0.1) 0px, transparent 50%);
        }
    </style>
</head>
<body class="mesh-bg min-h-screen flex items-center justify-center p-4 text-gray-100">
    <div class="text-center max-w-md">
        <div class="w-24 h-24 mx-auto mb-8 rounded-full bg-red-500/20 flex items-center justify-center">
            <svg class="w-12 h-12 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </div>

        <h1 class="text-3xl font-bold text-white mb-4">{{ __('messages.payments.error_title') }}</h1>
        <p class="text-gray-400 text-lg mb-8">
            {{ session('error') ?? __('messages.payments.error_message') }}
        </p>

        <div class="bg-red-500/10 border border-red-500/30 rounded-2xl p-6 mb-8">
            <p class="text-red-400">{{ __('messages.payments.error_note') }}</p>
        </div>

        <p class="text-gray-500 text-sm">{{ __('messages.payments.contact_support') }}</p>
    </div>
</body>
</html>

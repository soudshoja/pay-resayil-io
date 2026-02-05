<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('messages.payment.failed_title') }} - {{ config('app.name') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        [dir="rtl"] { font-family: 'Tajawal', sans-serif; }
        [dir="ltr"] { font-family: 'Space Grotesk', sans-serif; }

        .mesh-bg {
            background-color: #0a0a0f;
            background-image:
                radial-gradient(at 50% 50%, rgba(239, 68, 68, 0.15) 0px, transparent 50%);
        }

        .glass-card {
            background: rgba(26, 26, 36, 0.9);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }

        .shake {
            animation: shake 0.6s ease-out;
        }

        @keyframes scale-in {
            0% { transform: scale(0); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }

        .scale-in {
            animation: scale-in 0.5s ease-out forwards;
        }
    </style>
</head>
<body class="mesh-bg min-h-screen flex items-center justify-center p-4 text-gray-100">
    <div class="w-full max-w-md">
        <div class="glass-card rounded-2xl p-8 shadow-2xl text-center">
            <!-- Error Icon -->
            <div class="w-24 h-24 mx-auto mb-6 scale-in shake">
                <svg class="w-full h-full text-red-400" viewBox="0 0 24 24" fill="none">
                    <circle cx="12" cy="12" r="11" stroke="currentColor" stroke-width="2" class="opacity-20"/>
                    <circle cx="12" cy="12" r="11" stroke="currentColor" stroke-width="2"/>
                    <path d="M8 8l8 8M16 8l-8 8" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </div>

            <h1 class="text-3xl font-bold text-red-400 mb-2">
                {{ __('messages.payment.failed_heading') }}
            </h1>
            <p class="text-gray-400 mb-6">
                {{ __('messages.payment.failed_message') }}
            </p>

            <!-- Transaction Details -->
            <div class="bg-dark-900/50 rounded-xl p-6 mb-6 text-start">
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-500">{{ __('messages.payment.amount') }}</span>
                        <span class="font-semibold">{{ number_format($payment->total_amount ?? $payment->amount, 3) }} {{ $payment->currency }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">{{ __('messages.payment.reference') }}</span>
                        <span class="font-mono text-sm">{{ $payment->myfatoorah_invoice_id }}</span>
                    </div>
                    @if($payment->agent)
                        <div class="flex justify-between">
                            <span class="text-gray-500">{{ __('messages.payment.agent') }}</span>
                            <span>{{ $payment->agent->company_name }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Try Again Button -->
            <a href="{{ route('payment.show', $payment->myfatoorah_invoice_id) }}"
               class="block w-full bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white font-bold py-4 px-6 rounded-xl text-lg transition-all mb-4">
                {{ __('messages.payment.try_again') }}
            </a>

            <!-- Contact Support -->
            <p class="text-gray-500 text-sm">
                {{ __('messages.payment.need_help') }}
                <a href="mailto:support@collect.resayil.io" class="text-purple-400 hover:text-purple-300">
                    {{ __('messages.payment.contact_support') }}
                </a>
            </p>
        </div>

        <p class="text-center text-gray-600 text-sm mt-6">
            {{ __('messages.payment.powered_by') }}
            <a href="https://collect.resayil.io" class="text-purple-400 hover:text-purple-300">Collect Resayil</a>
        </p>
    </div>
</body>
</html>

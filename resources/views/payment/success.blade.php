<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('messages.payment.success_title') }} - {{ config('app.name') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        [dir="rtl"] { font-family: 'Tajawal', sans-serif; }
        [dir="ltr"] { font-family: 'Space Grotesk', sans-serif; }

        .mesh-bg {
            background-color: #0a0a0f;
            background-image:
                radial-gradient(at 50% 50%, rgba(34, 197, 94, 0.15) 0px, transparent 50%);
        }

        .glass-card {
            background: rgba(26, 26, 36, 0.9);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(34, 197, 94, 0.2);
        }

        @keyframes checkmark {
            0% { stroke-dashoffset: 100; }
            100% { stroke-dashoffset: 0; }
        }

        .checkmark-animation {
            stroke-dasharray: 100;
            animation: checkmark 0.8s ease-out forwards;
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
            <!-- Success Icon -->
            <div class="w-24 h-24 mx-auto mb-6 scale-in">
                <svg class="w-full h-full text-green-400" viewBox="0 0 24 24" fill="none">
                    <circle cx="12" cy="12" r="11" stroke="currentColor" stroke-width="2" class="opacity-20"/>
                    <circle cx="12" cy="12" r="11" stroke="currentColor" stroke-width="2" stroke-dasharray="69.115" stroke-dashoffset="0" class="checkmark-animation" style="animation-delay: 0.2s;"/>
                    <path d="M7 13l3 3 7-7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="checkmark-animation" style="animation-delay: 0.6s;"/>
                </svg>
            </div>

            <h1 class="text-3xl font-bold text-green-400 mb-2">
                {{ __('messages.payment.success_heading') }}
            </h1>
            <p class="text-gray-400 mb-6">
                {{ __('messages.payment.success_message') }}
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
                    <div class="flex justify-between">
                        <span class="text-gray-500">{{ __('messages.payment.date') }}</span>
                        <span>{{ $payment->paid_at?->format('d M Y, H:i') ?? now()->format('d M Y, H:i') }}</span>
                    </div>
                    @if($payment->agent)
                        <div class="flex justify-between">
                            <span class="text-gray-500">{{ __('messages.payment.agent') }}</span>
                            <span>{{ $payment->agent->company_name }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- WhatsApp Button -->
            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $payment->customer_phone ?? '') }}"
               class="block w-full bg-green-600 hover:bg-green-700 text-white font-bold py-4 px-6 rounded-xl text-lg transition-all mb-4">
                <svg class="w-6 h-6 inline-block mr-2" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                </svg>
                {{ __('messages.payment.return_whatsapp') }}
            </a>

            <p class="text-gray-600 text-sm">
                {{ __('messages.payment.receipt_sent') }}
            </p>
        </div>

        <p class="text-center text-gray-600 text-sm mt-6">
            {{ __('messages.payment.powered_by') }}
            <a href="https://collect.resayil.io" class="text-purple-400 hover:text-purple-300">Collect Resayil</a>
        </p>
    </div>
</body>
</html>

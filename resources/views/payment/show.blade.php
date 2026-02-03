<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('messages.payment.title') }} - {{ config('app.name') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Space Grotesk', 'Tajawal', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <style>
        [dir="rtl"] { font-family: 'Tajawal', 'Space Grotesk', sans-serif; }
        [dir="ltr"] { font-family: 'Space Grotesk', 'Tajawal', sans-serif; }

        .mesh-bg {
            background-color: #0a0a0f;
            background-image:
                radial-gradient(at 40% 20%, rgba(168, 85, 247, 0.15) 0px, transparent 50%),
                radial-gradient(at 80% 0%, rgba(236, 72, 153, 0.1) 0px, transparent 50%),
                radial-gradient(at 0% 100%, rgba(34, 211, 238, 0.1) 0px, transparent 50%);
        }

        .glass-card {
            background: rgba(26, 26, 36, 0.9);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(168, 85, 247, 0.2);
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

        .knet-green {
            background: linear-gradient(135deg, #00a651, #008c45);
        }
        .knet-green:hover {
            background: linear-gradient(135deg, #008c45, #007a3d);
        }
    </style>
</head>
<body class="mesh-bg min-h-screen flex items-center justify-center p-4 text-gray-100">
    <div class="w-full max-w-md">
        <!-- Client Logo -->
        @if($payment->client?->logo_path)
            <div class="text-center mb-6">
                <img src="{{ Storage::url($payment->client->logo_path) }}"
                     alt="{{ $payment->client->name }}"
                     class="max-h-20 mx-auto">
            </div>
        @endif

        <!-- Payment Card -->
        <div class="glass-card rounded-2xl p-8 shadow-2xl">
            <!-- Agent/Company Info -->
            <div class="text-center mb-6">
                <h1 class="text-2xl font-bold text-white mb-2">
                    {{ $payment->agent?->company_name ?? $payment->client?->name ?? 'Payment' }}
                </h1>
                @if($payment->agent?->iata_number)
                    <span class="inline-block px-3 py-1 bg-purple-500/20 text-purple-400 rounded-full text-sm font-medium">
                        IATA: {{ $payment->agent->iata_number }}
                    </span>
                @endif
            </div>

            <!-- Amount Breakdown -->
            <div class="bg-dark-900/50 rounded-xl p-6 mb-6 space-y-3">
                <div class="flex justify-between items-center text-lg">
                    <span class="text-gray-400">{{ __('messages.payment.amount') }}</span>
                    <span class="font-semibold">{{ number_format($payment->amount, 3) }} {{ $payment->currency }}</span>
                </div>

                @if($serviceFee > 0)
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-500">{{ __('messages.payment.service_fee') }}</span>
                        <span class="text-gray-400">{{ number_format($serviceFee, 3) }} {{ $payment->currency }}</span>
                    </div>
                    <hr class="border-gray-700">
                    <div class="flex justify-between items-center text-xl font-bold">
                        <span class="text-gray-300">{{ __('messages.payment.total') }}</span>
                        <span class="text-white">{{ number_format($totalAmount, 3) }} {{ $payment->currency }}</span>
                    </div>
                @endif
            </div>

            <!-- Invoice Info -->
            <div class="text-center text-sm text-gray-500 mb-6">
                <p>{{ __('messages.payment.invoice_id') }}: {{ $payment->myfatoorah_invoice_id }}</p>
                <p>{{ __('messages.payment.created') }}: {{ $payment->created_at->format('d M Y, H:i') }}</p>
            </div>

            <!-- Pay Button -->
            <form action="{{ route('payment.redirect', $payment->myfatoorah_invoice_id) }}" method="POST">
                @csrf
                <button type="submit" class="w-full knet-green text-white font-bold py-4 px-6 rounded-xl text-lg flex items-center justify-center gap-3 transition-all hover:shadow-lg hover:shadow-green-500/30">
                    <svg class="w-8 h-8" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M20 4H4c-1.11 0-1.99.89-1.99 2L2 18c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm0 14H4v-6h16v6zm0-10H4V6h16v2z"/>
                    </svg>
                    {{ __('messages.payment.pay_now_knet') }}
                </button>
            </form>

            <!-- Security Note -->
            <p class="text-center text-gray-500 text-xs mt-6">
                <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                {{ __('messages.payment.secure_note') }}
            </p>
        </div>

        <!-- Footer -->
        <p class="text-center text-gray-600 text-sm mt-6">
            {{ __('messages.payment.powered_by') }}
            <a href="https://collect.resayil.io" class="text-purple-400 hover:text-purple-300">Collect Resayil</a>
        </p>
    </div>
</body>
</html>

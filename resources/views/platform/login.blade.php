<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Platform Login - Pay Resayil.io</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Space Grotesk', sans-serif; }
        body {
            background: linear-gradient(135deg, #0f0f1a 0%, #1a1a2e 50%, #16213e 100%);
            min-height: 100vh;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        .gradient-text {
            background: linear-gradient(135deg, #a855f7 0%, #ec4899 50%, #f97316 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .gradient-btn {
            background: linear-gradient(135deg, #a855f7 0%, #ec4899 100%);
            transition: all 0.3s ease;
        }
        .gradient-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 40px rgba(168, 85, 247, 0.3);
        }
        .input-field {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }
        .input-field:focus {
            border-color: #a855f7;
            box-shadow: 0 0 0 3px rgba(168, 85, 247, 0.1);
            outline: none;
        }
        .floating-orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.5;
            animation: float 8s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(5deg); }
        }
    </style>
</head>
<body class="flex items-center justify-center p-4 relative overflow-hidden">
    <!-- Background Orbs -->
    <div class="floating-orb w-96 h-96 bg-purple-600 -top-48 -left-48" style="animation-delay: 0s;"></div>
    <div class="floating-orb w-80 h-80 bg-pink-600 -bottom-40 -right-40" style="animation-delay: 2s;"></div>
    <div class="floating-orb w-64 h-64 bg-orange-500 top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2" style="animation-delay: 4s;"></div>

    <div class="w-full max-w-md relative z-10">
        <!-- Logo Section -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl glass-card mb-6">
                <svg class="w-10 h-10 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </div>
            <h1 class="text-3xl font-bold gradient-text mb-2">Platform Admin</h1>
            <p class="text-gray-400 text-sm">Secure access for platform owners</p>
        </div>

        <!-- Login Card -->
        <div class="glass-card rounded-3xl p-8">
            @if($errors->any())
                <div class="mb-6 p-4 rounded-xl bg-red-500/10 border border-red-500/20">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-red-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-red-400 text-sm">{{ $errors->first() }}</span>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('platform.login.submit') }}" class="space-y-6">
                @csrf

                <!-- Email Field -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            Email Address
                        </span>
                    </label>
                    <input type="email" name="email" value="{{ old('email', 'soud@alphia.net') }}" required autofocus
                           class="input-field w-full px-4 py-3.5 rounded-xl text-white placeholder-gray-500"
                           placeholder="Enter your email">
                </div>

                <!-- Password Field -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            Password
                        </span>
                    </label>
                    <input type="password" name="password" required
                           class="input-field w-full px-4 py-3.5 rounded-xl text-white placeholder-gray-500"
                           placeholder="Enter your password">
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded border-gray-600 bg-gray-700 text-purple-500 focus:ring-purple-500 focus:ring-offset-0">
                        <span class="text-sm text-gray-400">Remember me</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="gradient-btn w-full py-4 rounded-xl text-white font-semibold text-lg">
                    <span class="flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                        </svg>
                        Sign In to Platform
                    </span>
                </button>
            </form>
        </div>

        <!-- Footer -->
        <div class="text-center mt-8">
            <p class="text-gray-500 text-sm">
                Protected area for authorized personnel only
            </p>
            <a href="{{ url('/') }}" class="inline-flex items-center gap-1 text-purple-400 hover:text-purple-300 text-sm mt-2 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to main site
            </a>
        </div>
    </div>
</body>
</html>

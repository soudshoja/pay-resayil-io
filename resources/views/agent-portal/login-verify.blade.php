@extends('agent-portal.layout')

@section('title', 'Verify OTP')

@section('content')
<div class="max-w-md mx-auto">
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-white">{{ __('messages.auth.verify_otp') }}</h1>
        <p class="text-gray-400 mt-2">{{ __('messages.auth.otp_sent_to', ['phone' => session('otp_phone')]) }}</p>
    </div>

    <div class="glass-card rounded-xl p-8">
        <form action="{{ route('agent.login.verify-otp') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">{{ __('messages.auth.enter_otp') }}</label>
                <input type="text" name="otp_code" required maxlength="6" placeholder="000000" class="w-full px-4 py-3 bg-dark-800 border border-gray-700 rounded-lg text-white text-center text-2xl tracking-widest font-mono focus:border-purple-500 focus:outline-none @error('otp_code') border-red-500 @enderror">
                @error('otp_code')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="w-full px-4 py-3 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-lg hover:from-purple-600 hover:to-pink-600 transition-all font-semibold">
                {{ __('messages.auth.verify') }}
            </button>
        </form>

        <div class="mt-6 text-center">
            <a href="{{ route('agent.login') }}" class="text-gray-400 hover:text-white transition-colors">
                {{ __('messages.auth.back_to_login') }}
            </a>
        </div>
    </div>
</div>
@endsection

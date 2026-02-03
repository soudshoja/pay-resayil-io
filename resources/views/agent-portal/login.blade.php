@extends('agent-portal.layout')

@section('title', 'Agent Login')

@section('content')
<div class="max-w-md mx-auto">
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-white">{{ __('messages.auth.welcome') }}</h1>
        <p class="text-gray-400 mt-2">{{ __('messages.auth.login_subtitle') }}</p>
    </div>

    <div class="glass-card rounded-xl p-8">
        <form action="{{ route('agent.login.send-otp') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">{{ __('messages.auth.phone') }}</label>
                <input type="text" name="phone_number" value="{{ old('phone_number') }}" required placeholder="+965XXXXXXXX" class="w-full px-4 py-3 bg-dark-800 border border-gray-700 rounded-lg text-white focus:border-purple-500 focus:outline-none @error('phone_number') border-red-500 @enderror">
                @error('phone_number')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
                <p class="mt-2 text-sm text-gray-500">{{ __('messages.auth.otp_info') }}</p>
            </div>

            <button type="submit" class="w-full px-4 py-3 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-lg hover:from-purple-600 hover:to-pink-600 transition-all font-semibold">
                {{ __('messages.auth.send_otp') }}
            </button>
        </form>

        <div class="mt-6 text-center">
            <p class="text-gray-400">Don't have an account?</p>
            <a href="{{ route('agent.register.step1') }}" class="text-purple-400 hover:text-purple-300 font-medium">Register your agency</a>
        </div>
    </div>
</div>
@endsection

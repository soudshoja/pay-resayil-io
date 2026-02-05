@extends('client.layout')

@section('title', __('messages.common.edit') . ' - ' . $user->full_name)

@section('content')
<div class="max-w-xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <a href="{{ route('client.accountants') }}" class="inline-flex items-center text-gray-400 hover:text-white transition-colors mb-4">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            {{ __('messages.common.back') }}
        </a>
        <h1 class="text-2xl font-bold text-white">{{ __('messages.common.edit') }} {{ $user->full_name }}</h1>
    </div>

    <!-- Form -->
    <div class="glass-card rounded-xl p-6">
        <form action="{{ route('client.accountants.update', $user) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Full Name *</label>
                <input type="text" name="full_name" value="{{ old('full_name', $user->full_name) }}" required class="w-full px-4 py-3 bg-dark-800 border border-gray-700 rounded-lg text-white focus:border-purple-500 focus:outline-none @error('full_name') border-red-500 @enderror">
                @error('full_name')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Email *</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full px-4 py-3 bg-dark-800 border border-gray-700 rounded-lg text-white focus:border-purple-500 focus:outline-none @error('email') border-red-500 @enderror">
                @error('email')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">{{ __('messages.auth.phone') }}</label>
                <input type="text" disabled value="{{ $user->username }}" class="w-full px-4 py-3 bg-dark-900 border border-gray-700 rounded-lg text-gray-500 cursor-not-allowed">
                <p class="mt-1 text-sm text-gray-500">Phone number cannot be changed</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">New Password</label>
                <input type="password" name="password" class="w-full px-4 py-3 bg-dark-800 border border-gray-700 rounded-lg text-white focus:border-purple-500 focus:outline-none @error('password') border-red-500 @enderror">
                <p class="mt-1 text-sm text-gray-500">Leave blank to keep current password</p>
                @error('password')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }} class="w-5 h-5 rounded border-gray-600 bg-dark-800 text-purple-500 focus:ring-purple-500">
                    <span class="text-gray-300">{{ __('messages.active') }}</span>
                </label>
            </div>

            <div class="flex justify-end gap-4 pt-4 border-t border-gray-700">
                <a href="{{ route('client.accountants') }}" class="px-6 py-3 bg-gray-700 text-white rounded-lg hover:bg-gray-600 transition-colors">
                    {{ __('messages.common.cancel') }}
                </a>
                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-lg hover:from-purple-600 hover:to-pink-600 transition-all">
                    {{ __('messages.common.save') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

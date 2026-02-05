@extends('platform.layout')
@section('title', 'Settings')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-8">
        <h1 class="text-2xl font-bold gradient-text">Platform Settings</h1>
        <p class="text-gray-400 mt-1">Configure system-wide settings and API credentials</p>
    </div>

    <form method="POST" action="{{ route('platform.settings.update') }}" class="space-y-6">
        @csrf

        <!-- Resayil WhatsApp API -->
        <div class="glass-card rounded-2xl p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-xl bg-green-500/20 flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-white">Resayil WhatsApp API</h2>
                    <p class="text-sm text-gray-500">WhatsApp messaging for OTP and notifications</p>
                </div>
            </div>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Base URL</label>
                    <input type="url" name="resayil_base_url" value="{{ $settings['resayil_base_url'] ?? 'https://wa.resayil.io/api/v1' }}"
                           class="w-full px-4 py-3 rounded-xl text-white font-mono text-sm">
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-2">API Key</label>
                    <input type="text" name="resayil_api_key" value="{{ $settings['resayil_api_key'] ?? '' }}"
                           class="w-full px-4 py-3 rounded-xl text-white font-mono text-sm"
                           placeholder="f0bd277a312a53381db25d5af1e3a5c2...">
                </div>
            </div>
        </div>

        <!-- MyFatoorah Default Settings -->
        <div class="glass-card rounded-2xl p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-xl bg-purple-500/20 flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-white">MyFatoorah Default Settings</h2>
                    <p class="text-sm text-gray-500">Default payment gateway configuration</p>
                </div>
            </div>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Base URL</label>
                    <select name="myfatoorah_base_url" class="w-full px-4 py-3 rounded-xl text-white">
                        <option value="https://apitest.myfatoorah.com" {{ ($settings['myfatoorah_base_url'] ?? '') == 'https://apitest.myfatoorah.com' ? 'selected' : '' }}>
                            Test: https://apitest.myfatoorah.com
                        </option>
                        <option value="https://api.myfatoorah.com" {{ ($settings['myfatoorah_base_url'] ?? '') == 'https://api.myfatoorah.com' ? 'selected' : '' }}>
                            Live (Kuwait): https://api.myfatoorah.com
                        </option>
                    </select>
                </div>
                <div>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="myfatoorah_test_mode" value="1" {{ ($settings['myfatoorah_test_mode'] ?? true) ? 'checked' : '' }}
                               class="w-5 h-5 rounded border-gray-600 bg-gray-700 text-purple-500">
                        <span class="text-gray-300">Test Mode (Sandbox) by Default</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Application Settings -->
        <div class="glass-card rounded-2xl p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-xl bg-blue-500/20 flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-white">Application Settings</h2>
                    <p class="text-sm text-gray-500">General platform configuration</p>
                </div>
            </div>
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Timezone</label>
                    <select name="app_timezone" class="w-full px-4 py-3 rounded-xl text-white">
                        <option value="Asia/Kuwait" {{ ($settings['app_timezone'] ?? '') == 'Asia/Kuwait' ? 'selected' : '' }}>Asia/Kuwait (GMT+3)</option>
                        <option value="Asia/Dubai" {{ ($settings['app_timezone'] ?? '') == 'Asia/Dubai' ? 'selected' : '' }}>Asia/Dubai (GMT+4)</option>
                        <option value="Asia/Riyadh" {{ ($settings['app_timezone'] ?? '') == 'Asia/Riyadh' ? 'selected' : '' }}>Asia/Riyadh (GMT+3)</option>
                        <option value="UTC" {{ ($settings['app_timezone'] ?? '') == 'UTC' ? 'selected' : '' }}>UTC</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Default Language</label>
                    <select name="app_locale" class="w-full px-4 py-3 rounded-xl text-white">
                        <option value="en" {{ ($settings['app_locale'] ?? '') == 'en' ? 'selected' : '' }}>English</option>
                        <option value="ar" {{ ($settings['app_locale'] ?? '') == 'ar' ? 'selected' : '' }}>Arabic</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="flex gap-4">
            <button type="submit" class="flex-1 gradient-btn py-3 rounded-xl text-white font-semibold">
                Save Settings
            </button>
        </div>
    </form>

    <!-- System Info -->
    <div class="mt-8 glass-card rounded-2xl p-6">
        <h2 class="text-lg font-semibold text-white mb-4">System Information</h2>
        <div class="grid md:grid-cols-2 gap-4 text-sm">
            <div class="flex justify-between">
                <span class="text-gray-400">Laravel Version</span>
                <span class="text-white">{{ app()->version() }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-400">PHP Version</span>
                <span class="text-white">{{ PHP_VERSION }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-400">Server Time</span>
                <span class="text-white">{{ now()->timezone(config('app.timezone'))->format('Y-m-d H:i:s') }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-400">Environment</span>
                <span class="text-white">{{ app()->environment() }}</span>
            </div>
        </div>
    </div>
</div>
@endsection

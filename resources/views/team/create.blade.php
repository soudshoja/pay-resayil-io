<x-layouts.app :title="__('messages.team.add_member')">
    @section('page-title', __('messages.team.add_member'))

    <div class="max-w-2xl mx-auto">
        <div class="mb-8">
            <a href="{{ route('team.index') }}" class="inline-flex items-center gap-2 text-gray-400 hover:text-white mb-4">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                {{ __('messages.common.back') }}
            </a>
            <h2 class="text-2xl font-bold text-white">{{ __('messages.team.add_member') }}</h2>
        </div>

        <form method="POST" action="{{ route('team.store') }}" class="glass-card rounded-2xl p-8">
            @csrf

            <div class="mb-6">
                <label for="full_name" class="block text-sm font-medium text-gray-300 mb-2">
                    {{ __('messages.team.full_name') }} <span class="text-red-400">*</span>
                </label>
                <input type="text" id="full_name" name="full_name" value="{{ old('full_name') }}"
                       class="w-full px-4 py-3 bg-dark-700 border border-dark-500 rounded-xl text-white focus:border-purple-500"
                       required>
                @error('full_name')<p class="mt-2 text-sm text-red-400">{{ $message }}</p>@enderror
            </div>

            <div class="mb-6">
                <label for="phone" class="block text-sm font-medium text-gray-300 mb-2">
                    {{ __('messages.team.phone') }} <span class="text-red-400">*</span>
                </label>
                <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" placeholder="+96512345678"
                       class="w-full px-4 py-3 bg-dark-700 border border-dark-500 rounded-xl text-white focus:border-purple-500"
                       required dir="ltr">
                @error('phone')<p class="mt-2 text-sm text-red-400">{{ $message }}</p>@enderror
            </div>

            <div class="mb-6">
                <label for="email" class="block text-sm font-medium text-gray-300 mb-2">
                    {{ __('messages.team.email') }}
                </label>
                <input type="email" id="email" name="email" value="{{ old('email') }}"
                       class="w-full px-4 py-3 bg-dark-700 border border-dark-500 rounded-xl text-white focus:border-purple-500">
                @error('email')<p class="mt-2 text-sm text-red-400">{{ $message }}</p>@enderror
            </div>

            <div class="mb-6">
                <label for="role" class="block text-sm font-medium text-gray-300 mb-2">
                    {{ __('messages.team.role') }} <span class="text-red-400">*</span>
                </label>
                <select id="role" name="role"
                        class="w-full px-4 py-3 bg-dark-700 border border-dark-500 rounded-xl text-white focus:border-purple-500">
                    <option value="agent" {{ old('role') === 'agent' ? 'selected' : '' }}>{{ __('messages.roles.agent') }}</option>
                    <option value="accountant" {{ old('role') === 'accountant' ? 'selected' : '' }}>{{ __('messages.roles.accountant') }}</option>
                    <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>{{ __('messages.roles.admin') }}</option>
                </select>
            </div>

            <div class="mb-6">
                <label for="password" class="block text-sm font-medium text-gray-300 mb-2">
                    {{ __('messages.team.password') }} <span class="text-red-400">*</span>
                </label>
                <input type="password" id="password" name="password"
                       class="w-full px-4 py-3 bg-dark-700 border border-dark-500 rounded-xl text-white focus:border-purple-500"
                       required>
                @error('password')<p class="mt-2 text-sm text-red-400">{{ $message }}</p>@enderror
            </div>

            <div class="mb-8">
                <label for="password_confirmation" class="block text-sm font-medium text-gray-300 mb-2">
                    {{ __('messages.team.password_confirm') }} <span class="text-red-400">*</span>
                </label>
                <input type="password" id="password_confirmation" name="password_confirmation"
                       class="w-full px-4 py-3 bg-dark-700 border border-dark-500 rounded-xl text-white focus:border-purple-500"
                       required>
            </div>

            <div class="flex gap-4">
                <button type="submit"
                        class="flex-1 py-3 px-6 rounded-xl bg-gradient-to-r from-purple-500 to-pink-500 text-white font-semibold hover:shadow-lg transition">
                    {{ __('messages.team.create_button') }}
                </button>
                <a href="{{ route('team.index') }}"
                   class="px-6 py-3 rounded-xl border border-dark-500 text-gray-400 hover:text-white hover:border-gray-400 transition">
                    {{ __('messages.common.cancel') }}
                </a>
            </div>
        </form>
    </div>
</x-layouts.app>

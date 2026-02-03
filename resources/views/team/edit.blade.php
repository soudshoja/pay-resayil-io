<x-layouts.app :title="'Edit ' . $member->full_name">
    @section('page-title', __('messages.common.edit'))

    <div class="max-w-2xl mx-auto">
        <div class="mb-8">
            <a href="{{ route('team.index') }}" class="inline-flex items-center gap-2 text-gray-400 hover:text-white mb-4">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                {{ __('messages.common.back') }}
            </a>
            <h2 class="text-2xl font-bold text-white">{{ __('messages.common.edit') }} - {{ $member->full_name }}</h2>
        </div>

        <form method="POST" action="{{ route('team.update', $member) }}" class="glass-card rounded-2xl p-8">
            @csrf @method('PUT')

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-300 mb-2">{{ __('messages.team.full_name') }} *</label>
                <input type="text" name="full_name" value="{{ old('full_name', $member->full_name) }}"
                       class="w-full px-4 py-3 bg-dark-700 border border-dark-500 rounded-xl text-white focus:border-purple-500"
                       required>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-300 mb-2">{{ __('messages.team.phone') }}</label>
                <input type="text" value="{{ $member->username }}" disabled
                       class="w-full px-4 py-3 bg-dark-600 border border-dark-500 rounded-xl text-gray-400" dir="ltr">
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-300 mb-2">{{ __('messages.team.email') }}</label>
                <input type="email" name="email" value="{{ old('email', $member->email) }}"
                       class="w-full px-4 py-3 bg-dark-700 border border-dark-500 rounded-xl text-white focus:border-purple-500">
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-300 mb-2">{{ __('messages.team.role') }} *</label>
                <select name="role" class="w-full px-4 py-3 bg-dark-700 border border-dark-500 rounded-xl text-white focus:border-purple-500">
                    <option value="agent" {{ $member->role === 'agent' ? 'selected' : '' }}>{{ __('messages.roles.agent') }}</option>
                    <option value="accountant" {{ $member->role === 'accountant' ? 'selected' : '' }}>{{ __('messages.roles.accountant') }}</option>
                    <option value="admin" {{ $member->role === 'admin' ? 'selected' : '' }}>{{ __('messages.roles.admin') }}</option>
                </select>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-300 mb-2">{{ __('messages.settings.new_password') }}</label>
                <input type="password" name="password"
                       class="w-full px-4 py-3 bg-dark-700 border border-dark-500 rounded-xl text-white focus:border-purple-500"
                       placeholder="Leave blank to keep current">
            </div>

            <div class="mb-8">
                <label class="block text-sm font-medium text-gray-300 mb-2">{{ __('messages.settings.confirm_password') }}</label>
                <input type="password" name="password_confirmation"
                       class="w-full px-4 py-3 bg-dark-700 border border-dark-500 rounded-xl text-white focus:border-purple-500">
            </div>

            <div class="flex gap-4">
                <button type="submit"
                        class="flex-1 py-3 px-6 rounded-xl bg-gradient-to-r from-purple-500 to-pink-500 text-white font-semibold hover:shadow-lg transition">
                    {{ __('messages.common.save') }}
                </button>
                <a href="{{ route('team.index') }}"
                   class="px-6 py-3 rounded-xl border border-dark-500 text-gray-400 hover:text-white transition">
                    {{ __('messages.common.cancel') }}
                </a>
            </div>
        </form>
    </div>
</x-layouts.app>

<x-layouts.app :title="__('messages.nav.settings')">
    @section('page-title', __('messages.nav.settings'))

    <div class="max-w-4xl mx-auto">
        <h2 class="text-2xl font-bold text-white mb-8">{{ __('messages.settings.title') }}</h2>

        <div class="grid gap-6">
            <!-- Profile Settings -->
            <div class="glass-card rounded-2xl p-6">
                <h3 class="text-lg font-semibold text-white mb-6 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-purple-500/20 flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    {{ __('messages.settings.profile') }}
                </h3>

                <form method="POST" action="{{ route('settings.profile') }}">
                    @csrf @method('PUT')

                    <div class="grid md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">{{ __('messages.team.full_name') }}</label>
                            <input type="text" name="full_name" value="{{ auth()->user()->full_name }}"
                                   class="w-full px-4 py-3 bg-dark-700 border border-dark-500 rounded-xl text-white focus:border-purple-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">{{ __('messages.team.email') }}</label>
                            <input type="email" name="email" value="{{ auth()->user()->email }}"
                                   class="w-full px-4 py-3 bg-dark-700 border border-dark-500 rounded-xl text-white focus:border-purple-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">{{ __('messages.settings.language') }}</label>
                            <select name="preferred_locale"
                                    class="w-full px-4 py-3 bg-dark-700 border border-dark-500 rounded-xl text-white focus:border-purple-500">
                                <option value="en" {{ auth()->user()->preferred_locale === 'en' ? 'selected' : '' }}>English</option>
                                <option value="ar" {{ auth()->user()->preferred_locale === 'ar' ? 'selected' : '' }}>العربية</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">{{ __('messages.team.phone') }}</label>
                            <input type="text" value="{{ auth()->user()->username }}" disabled
                                   class="w-full px-4 py-3 bg-dark-600 border border-dark-500 rounded-xl text-gray-400" dir="ltr">
                        </div>
                    </div>

                    <button type="submit" class="px-6 py-3 rounded-xl bg-purple-500 hover:bg-purple-600 text-white font-medium transition">
                        {{ __('messages.common.save') }}
                    </button>
                </form>
            </div>

            <!-- Change Password -->
            <div class="glass-card rounded-2xl p-6">
                <h3 class="text-lg font-semibold text-white mb-6 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-pink-500/20 flex items-center justify-center">
                        <svg class="w-5 h-5 text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    {{ __('messages.settings.change_password') }}
                </h3>

                <form method="POST" action="{{ route('settings.password') }}">
                    @csrf @method('PUT')

                    <div class="grid md:grid-cols-3 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">{{ __('messages.settings.current_password') }}</label>
                            <input type="password" name="current_password"
                                   class="w-full px-4 py-3 bg-dark-700 border border-dark-500 rounded-xl text-white focus:border-purple-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">{{ __('messages.settings.new_password') }}</label>
                            <input type="password" name="password"
                                   class="w-full px-4 py-3 bg-dark-700 border border-dark-500 rounded-xl text-white focus:border-purple-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">{{ __('messages.settings.confirm_password') }}</label>
                            <input type="password" name="password_confirmation"
                                   class="w-full px-4 py-3 bg-dark-700 border border-dark-500 rounded-xl text-white focus:border-purple-500">
                        </div>
                    </div>

                    <button type="submit" class="px-6 py-3 rounded-xl bg-pink-500 hover:bg-pink-600 text-white font-medium transition">
                        {{ __('messages.settings.update_password') }}
                    </button>
                </form>
            </div>

            <!-- Admin Settings Links -->
            @if(auth()->user()->hasRole(['admin', 'super_admin']))
            <div class="grid md:grid-cols-2 gap-6">
                <a href="{{ route('settings.myfatoorah') }}"
                   class="glass-card rounded-2xl p-6 hover:border-purple-500/30 transition group">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-green-500/20 flex items-center justify-center group-hover:scale-110 transition">
                            <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-white font-semibold">{{ __('messages.settings.myfatoorah') }}</h4>
                            <p class="text-sm text-gray-400">{{ __('messages.settings.myfatoorah_desc') }}</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('settings.webhooks') }}"
                   class="glass-card rounded-2xl p-6 hover:border-purple-500/30 transition group">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-cyan-500/20 flex items-center justify-center group-hover:scale-110 transition">
                            <svg class="w-6 h-6 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-white font-semibold">{{ __('messages.settings.webhooks') }}</h4>
                            <p class="text-sm text-gray-400">{{ __('messages.settings.webhooks_desc') }}</p>
                        </div>
                    </div>
                </a>
            </div>
            @endif
        </div>
    </div>
</x-layouts.app>

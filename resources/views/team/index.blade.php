<x-layouts.app :title="__('messages.nav.team')">
    @section('page-title', __('messages.nav.team'))

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold text-white">{{ __('messages.team.title') }}</h2>
            <p class="text-gray-400">{{ __('messages.team.subtitle') }}</p>
        </div>
        <a href="{{ route('team.create') }}"
           class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-gradient-to-r from-purple-500 to-pink-500 text-white font-semibold hover:shadow-lg hover:shadow-purple-500/30 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
            </svg>
            {{ __('messages.team.add_member') }}
        </a>
    </div>

    <!-- Team Grid -->
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($members as $member)
            <div class="glass-card rounded-2xl p-6 hover:border-purple-500/30 transition">
                <div class="flex items-start gap-4">
                    <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white text-xl font-bold">
                        {{ substr($member->full_name, 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="text-lg font-semibold text-white truncate">{{ $member->full_name }}</h3>
                        <p class="text-sm text-gray-400" dir="ltr">{{ $member->username }}</p>
                        <span class="inline-block mt-2 px-3 py-1 rounded-full text-xs font-medium
                            @if($member->role === 'admin') bg-purple-500/20 text-purple-400
                            @elseif($member->role === 'accountant') bg-cyan-500/20 text-cyan-400
                            @else bg-gray-500/20 text-gray-400 @endif">
                            {{ __('messages.roles.' . $member->role) }}
                        </span>
                    </div>
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="p-2 text-gray-400 hover:text-white rounded-lg hover:bg-dark-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                            </svg>
                        </button>
                        <div x-show="open" x-cloak @click.away="open = false"
                             class="absolute end-0 mt-2 w-40 rounded-xl glass-card shadow-xl py-2 z-10">
                            <a href="{{ route('team.edit', $member) }}"
                               class="flex items-center gap-2 px-4 py-2 text-gray-300 hover:text-white hover:bg-purple-500/10">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                {{ __('messages.common.edit') }}
                            </a>
                            <form method="POST" action="{{ route('team.toggle-status', $member) }}">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 text-gray-300 hover:text-white hover:bg-purple-500/10">
                                    @if($member->is_active)
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                        </svg>
                                        {{ __('messages.common.deactivate') }}
                                    @else
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        {{ __('messages.common.activate') }}
                                    @endif
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Status -->
                <div class="mt-4 pt-4 border-t border-dark-600 flex items-center justify-between text-sm">
                    <span class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full {{ $member->is_active ? 'bg-green-400' : 'bg-gray-500' }}"></span>
                        <span class="{{ $member->is_active ? 'text-green-400' : 'text-gray-500' }}">
                            {{ $member->is_active ? __('messages.common.active') : __('messages.common.inactive') }}
                        </span>
                    </span>
                    @if($member->last_login_at)
                        <span class="text-gray-500">{{ $member->last_login_at->diffForHumans() }}</span>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12">
                <svg class="w-12 h-12 text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <p class="text-gray-500">{{ __('messages.team.no_members') }}</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($members->hasPages())
        <div class="mt-6">
            {{ $members->links() }}
        </div>
    @endif
</x-layouts.app>

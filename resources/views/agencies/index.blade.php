<x-layouts.app :title="__('messages.nav.agencies')">
    @section('page-title', __('messages.nav.agencies'))

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold text-white">{{ __('messages.agencies.title') }}</h2>
        </div>
        <a href="{{ route('agencies.create') }}"
           class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-gradient-to-r from-purple-500 to-pink-500 text-white font-semibold hover:shadow-lg transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add Agency
        </a>
    </div>

    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($agencies as $agency)
            <a href="{{ route('agencies.show', $agency) }}"
               class="glass-card rounded-2xl p-6 hover:border-purple-500/30 transition group">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white text-xl font-bold">
                        {{ substr($agency->agency_name, 0, 2) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="text-lg font-semibold text-white truncate">{{ $agency->agency_name }}</h3>
                        <p class="text-sm text-gray-400">IATA: {{ $agency->iata_number }}</p>
                    </div>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-400">{{ $agency->users_count }} members</span>
                    <span class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full {{ $agency->is_active ? 'bg-green-400' : 'bg-gray-500' }}"></span>
                        <span class="{{ $agency->is_active ? 'text-green-400' : 'text-gray-500' }}">
                            {{ $agency->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </span>
                </div>
            </a>
        @empty
            <div class="col-span-full text-center py-12">
                <p class="text-gray-500">No agencies found</p>
            </div>
        @endforelse
    </div>

    @if($agencies->hasPages())
        <div class="mt-6">{{ $agencies->links() }}</div>
    @endif
</x-layouts.app>

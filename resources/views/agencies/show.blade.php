<x-layouts.app :title="$agency->agency_name">
    @section('page-title', $agency->agency_name)

    <div class="max-w-4xl mx-auto">
        <div class="mb-8">
            @if(auth()->user()->isSuperAdmin())
            <a href="{{ route('agencies.index') }}" class="inline-flex items-center gap-2 text-gray-400 hover:text-white mb-4">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back
            </a>
            @endif

            <div class="flex items-start justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-white">{{ $agency->agency_name }}</h2>
                    <p class="text-gray-400">IATA: {{ $agency->iata_number }}</p>
                </div>
                <span class="px-4 py-2 rounded-xl text-sm font-medium {{ $agency->is_active ? 'bg-green-500/20 text-green-400' : 'bg-gray-500/20 text-gray-400' }}">
                    {{ $agency->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>
        </div>

        <div class="grid md:grid-cols-3 gap-6 mb-8">
            <div class="glass-card rounded-2xl p-6">
                <p class="text-3xl font-bold text-white">{{ $agency->users->count() }}</p>
                <p class="text-gray-400">Team Members</p>
            </div>
            <div class="glass-card rounded-2xl p-6">
                <p class="text-3xl font-bold text-white">{{ $agency->payment_requests_count ?? 0 }}</p>
                <p class="text-gray-400">Total Payments</p>
            </div>
            <div class="glass-card rounded-2xl p-6">
                <p class="text-3xl font-bold text-white">{{ $agency->myfatoorahCredential ? 'Yes' : 'No' }}</p>
                <p class="text-gray-400">MyFatoorah Setup</p>
            </div>
        </div>

        <div class="glass-card rounded-2xl p-6 mb-6">
            <h3 class="text-lg font-semibold text-white mb-4">Agency Details</h3>
            <div class="grid md:grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="text-gray-400">Email:</span>
                    <span class="text-white ms-2">{{ $agency->company_email ?? '-' }}</span>
                </div>
                <div>
                    <span class="text-gray-400">Phone:</span>
                    <span class="text-white ms-2" dir="ltr">{{ $agency->phone ?? '-' }}</span>
                </div>
                <div class="md:col-span-2">
                    <span class="text-gray-400">Address:</span>
                    <span class="text-white ms-2">{{ $agency->address ?? '-' }}</span>
                </div>
            </div>
        </div>

        <div class="flex gap-4">
            <a href="{{ route('agencies.edit', $agency) }}"
               class="px-6 py-3 rounded-xl bg-purple-500 hover:bg-purple-600 text-white font-medium transition">
                Edit Agency
            </a>
            @if(auth()->user()->isSuperAdmin())
            <form method="POST" action="{{ route('agencies.toggle-status', $agency) }}">
                @csrf
                <button type="submit" class="px-6 py-3 rounded-xl border {{ $agency->is_active ? 'border-red-500 text-red-400 hover:bg-red-500/10' : 'border-green-500 text-green-400 hover:bg-green-500/10' }} transition">
                    {{ $agency->is_active ? 'Deactivate' : 'Activate' }}
                </button>
            </form>
            @endif
        </div>
    </div>
</x-layouts.app>

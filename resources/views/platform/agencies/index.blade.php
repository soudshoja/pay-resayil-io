@extends('platform.layout')
@section('title', 'Agencies')

@section('content')
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-2xl font-bold gradient-text">Agencies Management</h1>
        <p class="text-gray-400 mt-1">Manage all registered travel agencies</p>
    </div>
</div>

<!-- Filters -->
<div class="glass-card rounded-2xl p-4 mb-6">
    <form method="GET" class="flex flex-wrap gap-4">
        <div class="flex-1 min-w-[200px]">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search agencies..."
                   class="w-full px-4 py-2 rounded-xl text-white placeholder-gray-500">
        </div>
        <select name="status" class="px-4 py-2 rounded-xl text-white min-w-[150px]">
            <option value="">All Status</option>
            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
        <button type="submit" class="gradient-btn px-6 py-2 rounded-xl text-white font-medium">Filter</button>
        <a href="{{ route('platform.agencies') }}" class="px-6 py-2 rounded-xl border border-gray-600 text-gray-400 hover:text-white">Reset</a>
    </form>
</div>

<!-- Agencies Table -->
<div class="glass-card rounded-2xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-white/5">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Agency</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">IATA</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Users</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Payments</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">MyFatoorah</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-right text-xs font-medium text-gray-400 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse($agencies as $agency)
                <tr class="table-row">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-purple-500/20 to-pink-500/20 flex items-center justify-center">
                                <span class="text-sm font-bold text-purple-400">{{ substr($agency->agency_name, 0, 2) }}</span>
                            </div>
                            <div>
                                <p class="font-medium text-white">{{ $agency->agency_name }}</p>
                                <p class="text-xs text-gray-500">{{ $agency->company_email ?? 'No email' }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-300">{{ $agency->iata_number }}</td>
                    <td class="px-6 py-4 text-gray-300">{{ $agency->users_count }}</td>
                    <td class="px-6 py-4 text-gray-300">{{ $agency->payment_requests_count }}</td>
                    <td class="px-6 py-4">
                        @if($agency->myfatoorahCredential)
                            <span class="px-2 py-1 text-xs rounded-full bg-green-500/20 text-green-400">Configured</span>
                        @else
                            <span class="px-2 py-1 text-xs rounded-full bg-yellow-500/20 text-yellow-400">Not Set</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($agency->is_active)
                            <span class="px-2 py-1 text-xs rounded-full bg-green-500/20 text-green-400">Active</span>
                        @else
                            <span class="px-2 py-1 text-xs rounded-full bg-red-500/20 text-red-400">Inactive</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('platform.agencies.show', $agency) }}" class="p-2 rounded-lg bg-white/5 hover:bg-white/10 text-gray-400 hover:text-white" title="View">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>
                            <a href="{{ route('platform.agencies.edit', $agency) }}" class="p-2 rounded-lg bg-white/5 hover:bg-white/10 text-gray-400 hover:text-white" title="Edit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            <form method="POST" action="{{ route('platform.agencies.toggle-status', $agency) }}" class="inline">
                                @csrf
                                <button type="submit" class="p-2 rounded-lg bg-white/5 hover:bg-white/10 {{ $agency->is_active ? 'text-yellow-400' : 'text-green-400' }}" title="{{ $agency->is_active ? 'Deactivate' : 'Activate' }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">No agencies found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($agencies->hasPages())
    <div class="px-6 py-4 border-t border-white/5">
        {{ $agencies->links() }}
    </div>
    @endif
</div>
@endsection

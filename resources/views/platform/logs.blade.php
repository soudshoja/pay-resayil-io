@extends('platform.layout')
@section('title', 'Activity Logs')

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-bold gradient-text">Activity Logs</h1>
    <p class="text-gray-400 mt-1">Track all user activity across the platform</p>
</div>

<!-- Filters -->
<div class="glass-card rounded-2xl p-4 mb-6">
    <form method="GET" class="flex flex-wrap gap-4">
        <div class="flex-1 min-w-[200px]">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search actions..."
                   class="w-full px-4 py-2 rounded-xl text-white placeholder-gray-500">
        </div>
        <select name="user_id" class="px-4 py-2 rounded-xl text-white min-w-[180px]">
            <option value="">All Users</option>
            @foreach($users as $user)
                <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                    {{ $user->full_name }}
                </option>
            @endforeach
        </select>
        <select name="agency_id" class="px-4 py-2 rounded-xl text-white min-w-[180px]">
            <option value="">All Agencies</option>
            @foreach($agencies as $agency)
                <option value="{{ $agency->id }}" {{ request('agency_id') == $agency->id ? 'selected' : '' }}>
                    {{ $agency->agency_name }}
                </option>
            @endforeach
        </select>
        <input type="date" name="date_from" value="{{ request('date_from') }}" class="px-4 py-2 rounded-xl text-white">
        <input type="date" name="date_to" value="{{ request('date_to') }}" class="px-4 py-2 rounded-xl text-white">
        <button type="submit" class="gradient-btn px-6 py-2 rounded-xl text-white font-medium">Filter</button>
        <a href="{{ route('platform.logs') }}" class="px-6 py-2 rounded-xl border border-gray-600 text-gray-400 hover:text-white">Reset</a>
    </form>
</div>

<!-- Logs Table -->
<div class="glass-card rounded-2xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-white/5">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase">Time</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase">User</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase">Agency</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase">Action</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase">Description</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase">IP</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse($logs as $log)
                <tr class="table-row">
                    <td class="px-6 py-4">
                        <p class="text-gray-300 text-sm">{{ $log->created_at->format('M d, Y') }}</p>
                        <p class="text-xs text-gray-500">{{ $log->created_at->format('H:i:s') }}</p>
                    </td>
                    <td class="px-6 py-4">
                        @if($log->user)
                            <p class="text-white">{{ $log->user->full_name }}</p>
                            <p class="text-xs text-gray-500">{{ $log->user->email ?? $log->user->username }}</p>
                        @else
                            <span class="text-gray-500">System</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-gray-400">
                        {{ $log->agency->agency_name ?? 'N/A' }}
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full bg-purple-500/20 text-purple-400">
                            {{ $log->action }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-gray-300 text-sm max-w-md truncate">
                        {{ $log->description ?? '-' }}
                    </td>
                    <td class="px-6 py-4 text-gray-500 text-sm font-mono">
                        {{ $log->ip_address ?? 'N/A' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                        <div class="flex flex-col items-center">
                            <svg class="w-12 h-12 text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <p>No activity logs found</p>
                            <p class="text-sm text-gray-600 mt-1">Activity will appear here as users interact with the platform</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($logs->hasPages())
    <div class="px-6 py-4 border-t border-white/5">
        {{ $logs->links() }}
    </div>
    @endif
</div>
@endsection

@extends('platform.layout')
@section('title', 'Users')

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-bold gradient-text">Users Management</h1>
    <p class="text-gray-400 mt-1">Manage all users across all agencies</p>
</div>

<!-- Filters -->
<div class="glass-card rounded-2xl p-4 mb-6">
    <form method="GET" class="flex flex-wrap gap-4">
        <div class="flex-1 min-w-[200px]">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search users..."
                   class="w-full px-4 py-2 rounded-xl text-white placeholder-gray-500">
        </div>
        <select name="agency_id" class="px-4 py-2 rounded-xl text-white min-w-[180px]">
            <option value="">All Agencies</option>
            @foreach($agencies as $agency)
                <option value="{{ $agency->id }}" {{ request('agency_id') == $agency->id ? 'selected' : '' }}>
                    {{ $agency->agency_name }}
                </option>
            @endforeach
        </select>
        <select name="role" class="px-4 py-2 rounded-xl text-white min-w-[150px]">
            <option value="">All Roles</option>
            <option value="super_admin" {{ request('role') === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
            <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
            <option value="accountant" {{ request('role') === 'accountant' ? 'selected' : '' }}>Accountant</option>
            <option value="agent" {{ request('role') === 'agent' ? 'selected' : '' }}>Agent</option>
        </select>
        <select name="status" class="px-4 py-2 rounded-xl text-white min-w-[120px]">
            <option value="">All Status</option>
            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
        <button type="submit" class="gradient-btn px-6 py-2 rounded-xl text-white font-medium">Filter</button>
        <a href="{{ route('platform.users') }}" class="px-6 py-2 rounded-xl border border-gray-600 text-gray-400 hover:text-white">Reset</a>
    </form>
</div>

<!-- Users Table -->
<div class="glass-card rounded-2xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-white/5">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase">User</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase">Phone</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase">Agency</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase">Role</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase">Status</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase">Last Login</th>
                    <th class="px-6 py-4 text-right text-xs font-medium text-gray-400 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse($users as $user)
                <tr class="table-row">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center">
                                <span class="text-sm font-bold text-white">{{ substr($user->full_name, 0, 1) }}</span>
                            </div>
                            <div>
                                <p class="font-medium text-white">{{ $user->full_name }}</p>
                                <p class="text-xs text-gray-500">{{ $user->email ?? 'No email' }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-300" dir="ltr">{{ $user->username }}</td>
                    <td class="px-6 py-4 text-gray-300">{{ $user->agency->agency_name ?? 'No Agency' }}</td>
                    <td class="px-6 py-4">
                        @php
                            $roleColors = [
                                'super_admin' => 'bg-red-500/20 text-red-400',
                                'admin' => 'bg-purple-500/20 text-purple-400',
                                'accountant' => 'bg-blue-500/20 text-blue-400',
                                'agent' => 'bg-green-500/20 text-green-400',
                            ];
                        @endphp
                        <span class="px-2 py-1 text-xs rounded-full {{ $roleColors[$user->role] ?? 'bg-gray-500/20 text-gray-400' }} capitalize">
                            {{ str_replace('_', ' ', $user->role) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        @if($user->is_active)
                            <span class="px-2 py-1 text-xs rounded-full bg-green-500/20 text-green-400">Active</span>
                        @else
                            <span class="px-2 py-1 text-xs rounded-full bg-red-500/20 text-red-400">Inactive</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-gray-400 text-sm">
                        {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('platform.users.edit', $user) }}" class="p-2 rounded-lg bg-white/5 hover:bg-white/10 text-gray-400 hover:text-white" title="Edit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            <form method="POST" action="{{ route('platform.users.toggle-status', $user) }}" class="inline">
                                @csrf
                                <button type="submit" class="p-2 rounded-lg bg-white/5 hover:bg-white/10 {{ $user->is_active ? 'text-yellow-400' : 'text-green-400' }}" title="{{ $user->is_active ? 'Deactivate' : 'Activate' }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                    </svg>
                                </button>
                            </form>
                            @if(!$user->is_platform_owner)
                            <form method="POST" action="{{ route('platform.users.impersonate', $user) }}" class="inline">
                                @csrf
                                <button type="submit" class="p-2 rounded-lg bg-white/5 hover:bg-white/10 text-purple-400" title="Login as this user">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                                    </svg>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">No users found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($users->hasPages())
    <div class="px-6 py-4 border-t border-white/5">
        {{ $users->links() }}
    </div>
    @endif
</div>
@endsection

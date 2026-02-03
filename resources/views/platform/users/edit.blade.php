@extends('platform.layout')
@section('title', 'Edit User')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('platform.users') }}" class="inline-flex items-center gap-2 text-gray-400 hover:text-white mb-4">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Users
        </a>
        <h1 class="text-2xl font-bold text-white">Edit User</h1>
        <p class="text-gray-400">{{ $user->full_name }}</p>
    </div>

    @if($errors->any())
    <div class="mb-6 p-4 rounded-xl bg-red-500/10 border border-red-500/20">
        <ul class="list-disc list-inside text-red-400 text-sm">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('platform.users.update', $user) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="glass-card rounded-2xl p-6">
            <h2 class="text-lg font-semibold text-white mb-4">User Information</h2>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Full Name *</label>
                    <input type="text" name="full_name" value="{{ old('full_name', $user->full_name) }}" required
                           class="w-full px-4 py-3 rounded-xl text-white">
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Phone Number</label>
                    <input type="text" value="{{ $user->username }}" disabled
                           class="w-full px-4 py-3 rounded-xl text-gray-400 bg-gray-700/50" dir="ltr">
                    <p class="text-xs text-gray-500 mt-1">Phone number cannot be changed</p>
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}"
                           class="w-full px-4 py-3 rounded-xl text-white">
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Agency</label>
                    <select name="agency_id" class="w-full px-4 py-3 rounded-xl text-white">
                        <option value="">No Agency (Platform Level)</option>
                        @foreach($agencies as $agency)
                            <option value="{{ $agency->id }}" {{ old('agency_id', $user->agency_id) == $agency->id ? 'selected' : '' }}>
                                {{ $agency->agency_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Role *</label>
                    <select name="role" required class="w-full px-4 py-3 rounded-xl text-white">
                        <option value="agent" {{ old('role', $user->role) === 'agent' ? 'selected' : '' }}>Agent</option>
                        <option value="accountant" {{ old('role', $user->role) === 'accountant' ? 'selected' : '' }}>Accountant</option>
                        <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="super_admin" {{ old('role', $user->role) === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                    </select>
                </div>
                <div>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }}
                               class="w-5 h-5 rounded border-gray-600 bg-gray-700 text-purple-500">
                        <span class="text-gray-300">User is Active</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="glass-card rounded-2xl p-6">
            <h2 class="text-lg font-semibold text-white mb-4">Change Password</h2>
            <div>
                <label class="block text-sm text-gray-400 mb-2">New Password</label>
                <input type="password" name="password" placeholder="Leave blank to keep current"
                       class="w-full px-4 py-3 rounded-xl text-white">
                <p class="text-xs text-gray-500 mt-1">Minimum 6 characters. Leave empty to keep current password.</p>
            </div>
        </div>

        <div class="flex gap-4">
            <button type="submit" class="flex-1 gradient-btn py-3 rounded-xl text-white font-semibold">
                Update User
            </button>
            <a href="{{ route('platform.users') }}" class="px-6 py-3 rounded-xl border border-gray-600 text-gray-400 hover:text-white text-center">
                Cancel
            </a>
        </div>
    </form>

    <!-- User Activity Info -->
    <div class="mt-8 glass-card rounded-2xl p-6">
        <h2 class="text-lg font-semibold text-white mb-4">Activity Information</h2>
        <div class="grid md:grid-cols-2 gap-4 text-sm">
            <div class="flex justify-between">
                <span class="text-gray-400">Last Login</span>
                <span class="text-white">{{ $user->last_login_at ? $user->last_login_at->format('M d, Y H:i') : 'Never' }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-400">Last IP</span>
                <span class="text-white">{{ $user->last_login_ip ?? 'N/A' }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-400">Phone Verified</span>
                <span class="text-white">{{ $user->phone_verified_at ? $user->phone_verified_at->format('M d, Y') : 'Not verified' }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-400">Created</span>
                <span class="text-white">{{ $user->created_at->format('M d, Y') }}</span>
            </div>
        </div>
    </div>
</div>
@endsection

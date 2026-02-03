@extends('platform.layout')
@section('title', $agency->agency_name)

@section('content')
<div class="mb-6">
    <a href="{{ route('platform.agencies') }}" class="inline-flex items-center gap-2 text-gray-400 hover:text-white mb-4">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Back to Agencies
    </a>
    <div class="flex items-start justify-between">
        <div>
            <h1 class="text-2xl font-bold text-white">{{ $agency->agency_name }}</h1>
            <p class="text-gray-400">IATA: {{ $agency->iata_number }}</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('platform.agencies.edit', $agency) }}" class="gradient-btn px-4 py-2 rounded-xl text-white font-medium">Edit Agency</a>
            <form method="POST" action="{{ route('platform.agencies.toggle-status', $agency) }}">
                @csrf
                <button type="submit" class="px-4 py-2 rounded-xl border {{ $agency->is_active ? 'border-red-500/50 text-red-400 hover:bg-red-500/10' : 'border-green-500/50 text-green-400 hover:bg-green-500/10' }}">
                    {{ $agency->is_active ? 'Deactivate' : 'Activate' }}
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Stats -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="stat-card rounded-xl p-4">
        <p class="text-2xl font-bold text-white">{{ $agency->users->count() }}</p>
        <p class="text-sm text-gray-400">Team Members</p>
    </div>
    <div class="stat-card rounded-xl p-4">
        <p class="text-2xl font-bold text-white">{{ $stats['total_payments'] }}</p>
        <p class="text-sm text-gray-400">Total Payments</p>
    </div>
    <div class="stat-card rounded-xl p-4">
        <p class="text-2xl font-bold text-white">{{ $stats['paid_payments'] }}</p>
        <p class="text-sm text-gray-400">Successful Payments</p>
    </div>
    <div class="stat-card rounded-xl p-4">
        <p class="text-2xl font-bold text-white">{{ number_format($stats['revenue'], 3) }} KWD</p>
        <p class="text-sm text-gray-400">Total Revenue</p>
    </div>
</div>

<div class="grid lg:grid-cols-2 gap-6">
    <!-- Agency Details -->
    <div class="glass-card rounded-2xl p-6">
        <h2 class="text-lg font-semibold text-white mb-4">Agency Details</h2>
        <div class="space-y-4">
            <div class="flex justify-between">
                <span class="text-gray-400">Email</span>
                <span class="text-white">{{ $agency->company_email ?? 'Not set' }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-400">Phone</span>
                <span class="text-white" dir="ltr">{{ $agency->phone ?? 'Not set' }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-400">Address</span>
                <span class="text-white text-right max-w-xs">{{ $agency->address ?? 'Not set' }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-400">Status</span>
                @if($agency->is_active)
                    <span class="px-2 py-1 text-xs rounded-full bg-green-500/20 text-green-400">Active</span>
                @else
                    <span class="px-2 py-1 text-xs rounded-full bg-red-500/20 text-red-400">Inactive</span>
                @endif
            </div>
            <div class="flex justify-between">
                <span class="text-gray-400">Created</span>
                <span class="text-white">{{ $agency->created_at->format('M d, Y') }}</span>
            </div>
        </div>
    </div>

    <!-- MyFatoorah Credentials -->
    <div class="glass-card rounded-2xl p-6">
        <h2 class="text-lg font-semibold text-white mb-4">MyFatoorah Configuration</h2>
        @if($agency->myfatoorahCredential)
        <div class="space-y-4">
            <div class="flex justify-between">
                <span class="text-gray-400">Status</span>
                <span class="px-2 py-1 text-xs rounded-full bg-green-500/20 text-green-400">Configured</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-400">Mode</span>
                <span class="text-white">{{ $agency->myfatoorahCredential->is_test_mode ? 'Test Mode' : 'Live Mode' }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-400">API Key</span>
                <span class="text-white font-mono text-sm">{{ substr($agency->myfatoorahCredential->api_key, 0, 20) }}...</span>
            </div>
        </div>
        @else
        <div class="text-center py-8">
            <p class="text-gray-500 mb-4">MyFatoorah not configured</p>
            <a href="{{ route('platform.agencies.edit', $agency) }}" class="text-purple-400 hover:text-purple-300">Configure Now →</a>
        </div>
        @endif
    </div>
</div>

<!-- Team Members -->
<div class="mt-6 glass-card rounded-2xl p-6">
    <h2 class="text-lg font-semibold text-white mb-4">Team Members</h2>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="text-left text-xs text-gray-400 uppercase border-b border-white/10">
                    <th class="pb-3">Name</th>
                    <th class="pb-3">Phone</th>
                    <th class="pb-3">Role</th>
                    <th class="pb-3">Status</th>
                    <th class="pb-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse($agency->users as $user)
                <tr>
                    <td class="py-3">
                        <p class="text-white">{{ $user->full_name }}</p>
                        <p class="text-xs text-gray-500">{{ $user->email ?? 'No email' }}</p>
                    </td>
                    <td class="py-3 text-gray-400" dir="ltr">{{ $user->username }}</td>
                    <td class="py-3">
                        <span class="px-2 py-1 text-xs rounded-full bg-purple-500/20 text-purple-400 capitalize">{{ $user->role }}</span>
                    </td>
                    <td class="py-3">
                        @if($user->is_active)
                            <span class="text-green-400 text-sm">Active</span>
                        @else
                            <span class="text-red-400 text-sm">Inactive</span>
                        @endif
                    </td>
                    <td class="py-3 text-right">
                        <a href="{{ route('platform.users.edit', $user) }}" class="text-purple-400 hover:text-purple-300 text-sm">Edit</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-8 text-center text-gray-500">No team members</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Recent Payments -->
<div class="mt-6 glass-card rounded-2xl p-6">
    <h2 class="text-lg font-semibold text-white mb-4">Recent Payments</h2>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="text-left text-xs text-gray-400 uppercase border-b border-white/10">
                    <th class="pb-3">Amount</th>
                    <th class="pb-3">Customer</th>
                    <th class="pb-3">Status</th>
                    <th class="pb-3">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse($agency->paymentRequests as $payment)
                <tr>
                    <td class="py-3 text-white font-medium">{{ number_format($payment->amount, 3) }} {{ $payment->currency }}</td>
                    <td class="py-3 text-gray-400" dir="ltr">{{ $payment->customer_phone }}</td>
                    <td class="py-3">
                        @if($payment->status === 'paid')
                            <span class="px-2 py-1 text-xs rounded-full bg-green-500/20 text-green-400">Paid</span>
                        @elseif($payment->status === 'pending')
                            <span class="px-2 py-1 text-xs rounded-full bg-yellow-500/20 text-yellow-400">Pending</span>
                        @else
                            <span class="px-2 py-1 text-xs rounded-full bg-red-500/20 text-red-400">{{ ucfirst($payment->status) }}</span>
                        @endif
                    </td>
                    <td class="py-3 text-gray-400">{{ $payment->created_at->format('M d, Y H:i') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="py-8 text-center text-gray-500">No payments yet</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@extends('platform.layout')
@section('title', 'Payments')

@section('content')
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-2xl font-bold gradient-text">Payments</h1>
        <p class="text-gray-400 mt-1">All payment transactions across all agencies</p>
    </div>
    <a href="{{ route('platform.payments.export', request()->query()) }}" class="gradient-btn px-4 py-2 rounded-xl text-white font-medium flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
        </svg>
        Export CSV
    </a>
</div>

<!-- Stats -->
<div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
    <div class="stat-card rounded-xl p-4 text-center">
        <p class="text-2xl font-bold text-white">{{ $stats['total'] }}</p>
        <p class="text-xs text-gray-400">Total</p>
    </div>
    <div class="stat-card rounded-xl p-4 text-center">
        <p class="text-2xl font-bold text-green-400">{{ $stats['paid'] }}</p>
        <p class="text-xs text-gray-400">Paid</p>
    </div>
    <div class="stat-card rounded-xl p-4 text-center">
        <p class="text-2xl font-bold text-yellow-400">{{ $stats['pending'] }}</p>
        <p class="text-xs text-gray-400">Pending</p>
    </div>
    <div class="stat-card rounded-xl p-4 text-center">
        <p class="text-2xl font-bold text-red-400">{{ $stats['failed'] }}</p>
        <p class="text-xs text-gray-400">Failed</p>
    </div>
    <div class="stat-card rounded-xl p-4 text-center">
        <p class="text-2xl font-bold text-white">{{ number_format($stats['revenue'], 3) }}</p>
        <p class="text-xs text-gray-400">Revenue (KWD)</p>
    </div>
</div>

<!-- Filters -->
<div class="glass-card rounded-2xl p-4 mb-6">
    <form method="GET" class="flex flex-wrap gap-4">
        <div class="flex-1 min-w-[200px]">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by phone, name, invoice..."
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
        <select name="status" class="px-4 py-2 rounded-xl text-white min-w-[120px]">
            <option value="">All Status</option>
            <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid</option>
            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
            <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expired</option>
        </select>
        <input type="date" name="date_from" value="{{ request('date_from') }}" class="px-4 py-2 rounded-xl text-white">
        <input type="date" name="date_to" value="{{ request('date_to') }}" class="px-4 py-2 rounded-xl text-white">
        <button type="submit" class="gradient-btn px-6 py-2 rounded-xl text-white font-medium">Filter</button>
        <a href="{{ route('platform.payments') }}" class="px-6 py-2 rounded-xl border border-gray-600 text-gray-400 hover:text-white">Reset</a>
    </form>
</div>

<!-- Payments Table -->
<div class="glass-card rounded-2xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-white/5">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase">ID</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase">Agency</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase">Customer</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase">Amount</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase">Status</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase">Invoice ID</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse($payments as $payment)
                <tr class="table-row">
                    <td class="px-6 py-4 text-gray-400">#{{ $payment->id }}</td>
                    <td class="px-6 py-4">
                        <p class="text-white">{{ $payment->agency->agency_name ?? 'N/A' }}</p>
                        <p class="text-xs text-gray-500">{{ $payment->agent->full_name ?? 'Unknown Agent' }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-white" dir="ltr">{{ $payment->customer_phone ?? 'N/A' }}</p>
                        <p class="text-xs text-gray-500">{{ $payment->customer_name ?? '' }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-white font-semibold">{{ number_format($payment->amount, 3) }}</p>
                        <p class="text-xs text-gray-500">{{ $payment->currency }}</p>
                    </td>
                    <td class="px-6 py-4">
                        @if($payment->status === 'paid')
                            <span class="px-2 py-1 text-xs rounded-full bg-green-500/20 text-green-400">Paid</span>
                        @elseif($payment->status === 'pending')
                            <span class="px-2 py-1 text-xs rounded-full bg-yellow-500/20 text-yellow-400">Pending</span>
                        @elseif($payment->status === 'failed')
                            <span class="px-2 py-1 text-xs rounded-full bg-red-500/20 text-red-400">Failed</span>
                        @else
                            <span class="px-2 py-1 text-xs rounded-full bg-gray-500/20 text-gray-400">{{ ucfirst($payment->status) }}</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-gray-400 font-mono text-xs">
                        {{ $payment->myfatoorah_invoice_id ?? 'N/A' }}
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-gray-300 text-sm">{{ $payment->created_at->format('M d, Y') }}</p>
                        <p class="text-xs text-gray-500">{{ $payment->created_at->format('H:i:s') }}</p>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">No payments found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($payments->hasPages())
    <div class="px-6 py-4 border-t border-white/5">
        {{ $payments->links() }}
    </div>
    @endif
</div>
@endsection

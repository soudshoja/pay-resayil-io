@extends('client.layout')

@section('title', __('messages.nav.agents'))

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-white">{{ __('messages.nav.agents') }}</h1>
            <p class="text-gray-400">{{ __('messages.nav.agents') }} ({{ $agents->total() }})</p>
        </div>
        <a href="{{ route('client.agents.create') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-lg hover:from-purple-600 hover:to-pink-600 transition-all">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            {{ __('messages.common.add') }}
        </a>
    </div>

    <!-- Filters -->
    <div class="glass-card rounded-xl p-4">
        <form method="GET" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('messages.common.search') }}" class="w-full px-4 py-2 bg-dark-800 border border-gray-700 rounded-lg text-white focus:border-purple-500 focus:outline-none">
            </div>
            <div class="min-w-[150px]">
                <select name="sales_person" class="w-full px-4 py-2 bg-dark-800 border border-gray-700 rounded-lg text-white focus:border-purple-500 focus:outline-none">
                    <option value="">{{ __('messages.nav.sales_persons') }}</option>
                    @foreach($salesPersons as $sp)
                        <option value="{{ $sp->id }}" {{ request('sales_person') == $sp->id ? 'selected' : '' }}>{{ $sp->full_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="min-w-[120px]">
                <select name="status" class="w-full px-4 py-2 bg-dark-800 border border-gray-700 rounded-lg text-white focus:border-purple-500 focus:outline-none">
                    <option value="">{{ __('messages.payments.status') }}</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>{{ __('messages.active') }}</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>{{ __('messages.inactive') }}</option>
                </select>
            </div>
            <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                {{ __('messages.common.filter') }}
            </button>
        </form>
    </div>

    <!-- Agents Table -->
    <div class="glass-card rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-dark-800">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">{{ __('messages.payments.customer_name') }}</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">IATA</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">{{ __('messages.nav.sales_persons') }}</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">{{ __('messages.payments.status') }}</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-400 uppercase tracking-wider">{{ __('messages.common.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800">
                    @forelse($agents as $agent)
                        <tr class="hover:bg-dark-800/50 transition-colors">
                            <td class="px-6 py-4">
                                <div>
                                    <div class="font-medium text-white">{{ $agent->company_name }}</div>
                                    <div class="text-sm text-gray-400">{{ $agent->email }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-300 font-mono">{{ $agent->iata_number ?: '-' }}</td>
                            <td class="px-6 py-4 text-gray-300">{{ $agent->salesPerson?->full_name ?: '-' }}</td>
                            <td class="px-6 py-4">
                                @if($agent->is_active)
                                    <span class="px-2 py-1 text-xs rounded-full bg-green-500/20 text-green-400">{{ __('messages.active') }}</span>
                                @else
                                    <span class="px-2 py-1 text-xs rounded-full bg-red-500/20 text-red-400">{{ __('messages.inactive') }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('client.agents.show', $agent) }}" class="p-2 text-gray-400 hover:text-purple-400 transition-colors" title="{{ __('messages.common.view') }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    <a href="{{ route('client.agents.edit', $agent) }}" class="p-2 text-gray-400 hover:text-blue-400 transition-colors" title="{{ __('messages.common.edit') }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    <form action="{{ route('client.agents.destroy', $agent) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('messages.common.confirm_delete') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-gray-400 hover:text-red-400 transition-colors" title="{{ __('messages.common.delete') }}">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                                {{ __('messages.no_agents') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($agents->hasPages())
            <div class="px-6 py-4 border-t border-gray-800">
                {{ $agents->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

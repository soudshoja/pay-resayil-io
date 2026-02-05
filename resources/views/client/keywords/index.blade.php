@extends('client.layout')

@section('title', __('messages.nav.keywords'))

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-white">{{ __('messages.nav.keywords') }}</h1>
            <p class="text-gray-400">Configure WhatsApp trigger keywords</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Add New Keyword Form -->
        <div class="glass-card rounded-xl p-6">
            <h2 class="text-lg font-semibold text-white mb-4">Add Keyword</h2>
            <form action="{{ route('client.keywords.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Keyword *</label>
                    <input type="text" name="keyword" value="{{ old('keyword') }}" required placeholder="e.g. topup, pay, شحن" class="w-full px-4 py-3 bg-dark-800 border border-gray-700 rounded-lg text-white focus:border-purple-500 focus:outline-none @error('keyword') border-red-500 @enderror">
                    @error('keyword')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Action *</label>
                    <select name="action" required class="w-full px-4 py-3 bg-dark-800 border border-gray-700 rounded-lg text-white focus:border-purple-500 focus:outline-none">
                        <option value="payment_request">Payment Request</option>
                        <option value="balance_check">Balance Check</option>
                        <option value="status_check">Status Check</option>
                        <option value="help">Help</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Response Template</label>
                    <textarea name="response_template" rows="3" placeholder="Custom response message..." class="w-full px-4 py-3 bg-dark-800 border border-gray-700 rounded-lg text-white focus:border-purple-500 focus:outline-none @error('response_template') border-red-500 @enderror">{{ old('response_template') }}</textarea>
                    <p class="mt-1 text-sm text-gray-500">Optional. Use {amount}, {currency}, {agent_name} as placeholders.</p>
                    @error('response_template')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="w-full px-4 py-3 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-lg hover:from-purple-600 hover:to-pink-600 transition-all">
                    Add Keyword
                </button>
            </form>
        </div>

        <!-- Keywords List -->
        <div class="lg:col-span-2 glass-card rounded-xl overflow-hidden">
            <div class="p-4 border-b border-gray-800">
                <h2 class="text-lg font-semibold text-white">Active Keywords</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-dark-800">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Keyword</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Action</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">{{ __('messages.payments.status') }}</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-400 uppercase tracking-wider">{{ __('messages.common.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800">
                        @forelse($keywords as $keyword)
                            <tr class="hover:bg-dark-800/50 transition-colors">
                                <td class="px-6 py-4 font-mono font-medium text-white">{{ $keyword->keyword }}</td>
                                <td class="px-6 py-4">
                                    @php
                                        $actionLabels = [
                                            'payment_request' => ['label' => 'Payment Request', 'color' => 'bg-purple-500/20 text-purple-400'],
                                            'balance_check' => ['label' => 'Balance Check', 'color' => 'bg-blue-500/20 text-blue-400'],
                                            'status_check' => ['label' => 'Status Check', 'color' => 'bg-cyan-500/20 text-cyan-400'],
                                            'help' => ['label' => 'Help', 'color' => 'bg-gray-500/20 text-gray-400'],
                                        ];
                                        $action = $actionLabels[$keyword->action] ?? ['label' => $keyword->action, 'color' => 'bg-gray-500/20 text-gray-400'];
                                    @endphp
                                    <span class="px-2 py-1 text-xs rounded-full {{ $action['color'] }}">{{ $action['label'] }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($keyword->is_active)
                                        <span class="px-2 py-1 text-xs rounded-full bg-green-500/20 text-green-400">{{ __('messages.active') }}</span>
                                    @else
                                        <span class="px-2 py-1 text-xs rounded-full bg-red-500/20 text-red-400">{{ __('messages.inactive') }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <form action="{{ route('client.keywords.toggle', $keyword) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="p-2 text-gray-400 hover:text-yellow-400 transition-colors" title="Toggle">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"/>
                                                </svg>
                                            </button>
                                        </form>
                                        <form action="{{ route('client.keywords.destroy', $keyword) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('messages.common.confirm_delete') }}')">
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
                                <td colspan="4" class="px-6 py-12 text-center text-gray-400">
                                    No keywords configured
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($keywords->hasPages())
                <div class="px-6 py-4 border-t border-gray-800">
                    {{ $keywords->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

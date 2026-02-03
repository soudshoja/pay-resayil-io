<x-layouts.app :title="__('messages.settings.webhooks')">
    @section('page-title', __('messages.settings.webhooks'))

    <div class="max-w-4xl mx-auto">
        <div class="mb-8">
            <a href="{{ route('settings.index') }}" class="inline-flex items-center gap-2 text-gray-400 hover:text-white mb-4">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                {{ __('messages.common.back') }}
            </a>
            <h2 class="text-2xl font-bold text-white">{{ __('messages.settings.webhooks') }}</h2>
            <p class="text-gray-400">{{ __('messages.settings.webhooks_desc') }}</p>
        </div>

        <!-- Add Webhook -->
        <form method="POST" action="{{ route('settings.webhooks.store') }}" class="glass-card rounded-2xl p-6 mb-6">
            @csrf
            <div class="grid md:grid-cols-3 gap-4">
                <div>
                    <select name="webhook_type"
                            class="w-full px-4 py-3 bg-dark-700 border border-dark-500 rounded-xl text-white focus:border-purple-500">
                        <option value="n8n_trigger">n8n Trigger</option>
                        <option value="payment_callback">Payment Callback</option>
                        <option value="incoming_whatsapp">Incoming WhatsApp</option>
                        <option value="custom">Custom</option>
                    </select>
                </div>
                <div class="md:col-span-2 flex gap-4">
                    <input type="url" name="endpoint_url" placeholder="https://your-n8n.com/webhook/xxx"
                           class="flex-1 px-4 py-3 bg-dark-700 border border-dark-500 rounded-xl text-white focus:border-purple-500"
                           required>
                    <button type="submit"
                            class="px-6 py-3 rounded-xl bg-purple-500 hover:bg-purple-600 text-white font-medium transition">
                        {{ __('messages.common.add') }}
                    </button>
                </div>
            </div>
        </form>

        <!-- Webhooks List -->
        <div class="space-y-4">
            @forelse($webhooks as $webhook)
                <div class="glass-card rounded-2xl p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-3 mb-2">
                                <span class="px-3 py-1 rounded-full text-xs font-medium bg-cyan-500/20 text-cyan-400">
                                    {{ $webhook->webhook_type }}
                                </span>
                                <span class="w-2 h-2 rounded-full {{ $webhook->is_active ? 'bg-green-400' : 'bg-gray-500' }}"></span>
                            </div>
                            <p class="text-white font-mono text-sm truncate">{{ $webhook->endpoint_url }}</p>
                            <p class="text-gray-500 text-xs mt-1">
                                {{ __('messages.settings.triggered') }}: {{ $webhook->trigger_count }} times
                                @if($webhook->last_triggered_at)
                                    | {{ __('messages.settings.last_trigger') }}: {{ $webhook->last_triggered_at->diffForHumans() }}
                                @endif
                            </p>
                        </div>
                        <div class="flex items-center gap-2">
                            <form method="POST" action="{{ route('settings.webhooks.toggle', $webhook) }}">
                                @csrf
                                <button type="submit" class="p-2 rounded-lg hover:bg-dark-600 transition"
                                        title="{{ $webhook->is_active ? __('messages.common.deactivate') : __('messages.common.activate') }}">
                                    @if($webhook->is_active)
                                        <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    @else
                                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    @endif
                                </button>
                            </form>
                            <form method="POST" action="{{ route('settings.webhooks.destroy', $webhook) }}"
                                  onsubmit="return confirm('{{ __('messages.common.confirm_delete') }}')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-2 rounded-lg hover:bg-red-500/10 transition text-red-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <svg class="w-12 h-12 text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                    </svg>
                    <p class="text-gray-500">{{ __('messages.settings.no_webhooks') }}</p>
                </div>
            @endforelse
        </div>
    </div>
</x-layouts.app>

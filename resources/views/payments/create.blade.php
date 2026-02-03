<x-layouts.app :title="__('messages.payments.new')">
    @section('page-title', __('messages.payments.new'))

    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('payments.index') }}" class="inline-flex items-center gap-2 text-gray-400 hover:text-white mb-4">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                {{ __('messages.common.back') }}
            </a>
            <h2 class="text-2xl font-bold text-white">{{ __('messages.payments.create_title') }}</h2>
            <p class="text-gray-400">{{ __('messages.payments.create_subtitle') }}</p>
        </div>

        <!-- Form -->
        <form method="POST" action="{{ route('payments.store') }}" class="glass-card rounded-2xl p-8" x-data="{ loading: false }" @submit="loading = true">
            @csrf

            <!-- Amount -->
            <div class="mb-6">
                <label for="amount" class="block text-sm font-medium text-gray-300 mb-2">
                    {{ __('messages.payments.amount') }} <span class="text-red-400">*</span>
                </label>
                <div class="relative">
                    <input type="number"
                           id="amount"
                           name="amount"
                           step="0.001"
                           min="0.100"
                           max="10000"
                           value="{{ old('amount') }}"
                           placeholder="0.000"
                           class="w-full px-4 py-3 bg-dark-700 border border-dark-500 rounded-xl text-white text-xl font-semibold placeholder-gray-600 focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20"
                           required>
                    <span class="absolute inset-y-0 end-0 flex items-center pe-4 text-gray-400 font-medium">KWD</span>
                </div>
                @error('amount')
                    <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Customer Phone -->
            <div class="mb-6">
                <label for="customer_phone" class="block text-sm font-medium text-gray-300 mb-2">
                    {{ __('messages.payments.customer_phone') }} <span class="text-red-400">*</span>
                </label>
                <input type="tel"
                       id="customer_phone"
                       name="customer_phone"
                       value="{{ old('customer_phone') }}"
                       placeholder="+96512345678"
                       class="w-full px-4 py-3 bg-dark-700 border border-dark-500 rounded-xl text-white placeholder-gray-500 focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20"
                       required
                       dir="ltr">
                @error('customer_phone')
                    <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Customer Name -->
            <div class="mb-6">
                <label for="customer_name" class="block text-sm font-medium text-gray-300 mb-2">
                    {{ __('messages.payments.customer_name') }}
                </label>
                <input type="text"
                       id="customer_name"
                       name="customer_name"
                       value="{{ old('customer_name') }}"
                       placeholder="{{ __('messages.payments.customer_name_placeholder') }}"
                       class="w-full px-4 py-3 bg-dark-700 border border-dark-500 rounded-xl text-white placeholder-gray-500 focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20">
            </div>

            <!-- Description -->
            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-gray-300 mb-2">
                    {{ __('messages.payments.description') }}
                </label>
                <textarea id="description"
                          name="description"
                          rows="3"
                          placeholder="{{ __('messages.payments.description_placeholder') }}"
                          class="w-full px-4 py-3 bg-dark-700 border border-dark-500 rounded-xl text-white placeholder-gray-500 focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 resize-none">{{ old('description') }}</textarea>
            </div>

            <!-- Send WhatsApp -->
            <div class="mb-8">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox"
                           name="send_whatsapp"
                           value="1"
                           checked
                           class="w-5 h-5 rounded bg-dark-700 border-dark-500 text-purple-500 focus:ring-purple-500/20">
                    <span class="text-gray-300">{{ __('messages.payments.send_whatsapp') }}</span>
                </label>
            </div>

            <!-- Submit -->
            <div class="flex gap-4">
                <button type="submit"
                        class="flex-1 py-3 px-6 rounded-xl bg-gradient-to-r from-purple-500 to-pink-500 text-white font-semibold hover:shadow-lg hover:shadow-purple-500/30 transition flex items-center justify-center gap-2 disabled:opacity-50"
                        :disabled="loading">
                    <template x-if="loading">
                        <svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </template>
                    <template x-if="!loading">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </template>
                    <span>{{ __('messages.payments.create_button') }}</span>
                </button>

                <a href="{{ route('payments.index') }}"
                   class="px-6 py-3 rounded-xl border border-dark-500 text-gray-400 hover:text-white hover:border-gray-400 transition">
                    {{ __('messages.common.cancel') }}
                </a>
            </div>
        </form>
    </div>
</x-layouts.app>

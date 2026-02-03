<x-layouts.app :title="'Edit ' . $agency->agency_name">
    @section('page-title', 'Edit Agency')

    <div class="max-w-2xl mx-auto">
        <div class="mb-8">
            <a href="{{ route('agencies.show', $agency) }}" class="inline-flex items-center gap-2 text-gray-400 hover:text-white mb-4">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back
            </a>
            <h2 class="text-2xl font-bold text-white">Edit Agency</h2>
        </div>

        <form method="POST" action="{{ route('agencies.update', $agency) }}" class="glass-card rounded-2xl p-8">
            @csrf @method('PUT')

            <div class="grid md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Agency Name *</label>
                    <input type="text" name="agency_name" value="{{ old('agency_name', $agency->agency_name) }}"
                           class="w-full px-4 py-3 bg-dark-700 border border-dark-500 rounded-xl text-white focus:border-purple-500"
                           required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">IATA Number *</label>
                    <input type="text" name="iata_number" value="{{ old('iata_number', $agency->iata_number) }}"
                           class="w-full px-4 py-3 bg-dark-700 border border-dark-500 rounded-xl text-white focus:border-purple-500"
                           required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Company Email</label>
                    <input type="email" name="company_email" value="{{ old('company_email', $agency->company_email) }}"
                           class="w-full px-4 py-3 bg-dark-700 border border-dark-500 rounded-xl text-white focus:border-purple-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Phone</label>
                    <input type="tel" name="phone" value="{{ old('phone', $agency->phone) }}"
                           class="w-full px-4 py-3 bg-dark-700 border border-dark-500 rounded-xl text-white focus:border-purple-500" dir="ltr">
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-300 mb-2">Address</label>
                <textarea name="address" rows="3"
                          class="w-full px-4 py-3 bg-dark-700 border border-dark-500 rounded-xl text-white focus:border-purple-500">{{ old('address', $agency->address) }}</textarea>
            </div>

            <div class="flex gap-4">
                <button type="submit"
                        class="flex-1 py-3 px-6 rounded-xl bg-gradient-to-r from-purple-500 to-pink-500 text-white font-semibold hover:shadow-lg transition">
                    Update Agency
                </button>
                <a href="{{ route('agencies.show', $agency) }}"
                   class="px-6 py-3 rounded-xl border border-dark-500 text-gray-400 hover:text-white transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</x-layouts.app>

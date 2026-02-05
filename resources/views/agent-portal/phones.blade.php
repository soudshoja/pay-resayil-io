@extends('agent-portal.layout')

@section('title', 'Authorized Phones')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <!-- Header -->
    <div>
        <h1 class="text-2xl font-bold text-white">Authorized Phones</h1>
        <p class="text-gray-400">Manage phone numbers that can create payments via WhatsApp</p>
    </div>

    <!-- Add New Phone -->
    <div class="glass-card rounded-xl p-6">
        <h2 class="text-lg font-semibold text-white mb-4">Add Phone Number</h2>
        <form action="{{ route('agent.phones.store') }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Phone Number *</label>
                    <input type="text" name="phone_number" required placeholder="+965XXXXXXXX" class="w-full px-4 py-3 bg-dark-800 border border-gray-700 rounded-lg text-white focus:border-purple-500 focus:outline-none @error('phone_number') border-red-500 @enderror">
                    @error('phone_number')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Name (Optional)</label>
                    <input type="text" name="full_name" placeholder="Employee name" class="w-full px-4 py-3 bg-dark-800 border border-gray-700 rounded-lg text-white focus:border-purple-500 focus:outline-none">
                </div>
            </div>
            <button type="submit" class="px-6 py-3 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-lg hover:from-purple-600 hover:to-pink-600 transition-all font-semibold">
                Add Phone
            </button>
        </form>
    </div>

    <!-- Phone List -->
    <div class="glass-card rounded-xl p-6">
        <h2 class="text-lg font-semibold text-white mb-4">Authorized Phones ({{ $phones->count() }})</h2>
        <div class="space-y-3">
            @forelse($phones as $phone)
                <div class="flex items-center justify-between p-4 bg-dark-800 rounded-lg">
                    <div>
                        <p class="font-mono text-white text-lg">{{ $phone->phone_number }}</p>
                        @if($phone->full_name)
                            <p class="text-sm text-gray-400">{{ $phone->full_name }}</p>
                        @endif
                        <p class="text-xs text-gray-500">Added: {{ $phone->created_at->format('d M Y') }}</p>
                    </div>
                    <div class="flex items-center gap-3">
                        @if($phone->is_active)
                            <span class="px-2 py-1 text-xs rounded-full bg-green-500/20 text-green-400">{{ __('messages.active') }}</span>
                        @else
                            <span class="px-2 py-1 text-xs rounded-full bg-red-500/20 text-red-400">{{ __('messages.inactive') }}</span>
                        @endif
                        <form action="{{ route('agent.phones.destroy', $phone) }}" method="POST" onsubmit="return confirm('{{ __('messages.common.confirm_delete') }}')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 text-gray-400 hover:text-red-400 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="text-center text-gray-400 py-8">No authorized phones yet. Add your first phone number above.</p>
            @endforelse
        </div>
    </div>

    <!-- Info -->
    <div class="glass-card rounded-xl p-6 border-purple-500/30">
        <h3 class="text-white font-semibold mb-2">How it works</h3>
        <ul class="text-gray-400 text-sm space-y-2">
            <li>1. Add phone numbers of employees who need to create payment links</li>
            <li>2. They can send a WhatsApp message like "top up 100 KD" to create payments</li>
            <li>3. The system will automatically identify them and generate payment links</li>
            <li>4. Payment confirmations will be sent to your accountant WhatsApp number</li>
        </ul>
    </div>
</div>
@endsection

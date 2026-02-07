@extends('client.layout')

@section('title', 'WhatsApp')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-white flex items-center gap-3">
                <svg class="w-7 h-7 text-green-400" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/></svg>
                WhatsApp Management
            </h1>
            <p class="text-gray-400 mt-1">Manage your WhatsApp bot keywords and view conversations</p>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="glass-card rounded-xl p-4">
            <p class="text-gray-400 text-sm">WhatsApp Number</p>
            <p class="text-white text-lg font-semibold mt-1">{{ $client->whatsapp_number ?? 'Not configured' }}</p>
        </div>
        <div class="glass-card rounded-xl p-4">
            <p class="text-gray-400 text-sm">Active Keywords</p>
            <p class="text-white text-lg font-semibold mt-1">{{ $client->whatsappKeywords()->active()->count() }}</p>
        </div>
        <div class="glass-card rounded-xl p-4">
            <p class="text-gray-400 text-sm">Status</p>
            <p class="text-green-400 text-lg font-semibold mt-1 flex items-center gap-2">
                <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                Connected
            </p>
        </div>
    </div>

    <!-- Keywords Section (iframe to existing keywords page) -->
    <div class="glass-card rounded-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-800">
            <h2 class="text-lg font-semibold text-white">Bot Keywords</h2>
            <p class="text-gray-400 text-sm">Keywords that trigger automated actions when agents send messages</p>
        </div>
        <div class="p-6">
            @php
                $keywords = $client->whatsappKeywords()->orderBy('keyword')->get();
            @endphp

            @if($keywords->isEmpty())
                <div class="text-center py-8 text-gray-400">
                    <svg class="w-12 h-12 mx-auto mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                    </svg>
                    <p>No keywords configured yet.</p>
                    <a href="{{ route('client.keywords') }}" class="text-purple-400 hover:text-purple-300 mt-2 inline-block">Add Keywords</a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="text-left text-gray-400 text-sm border-b border-gray-800">
                                <th class="pb-3 font-medium">Keyword</th>
                                <th class="pb-3 font-medium">Action</th>
                                <th class="pb-3 font-medium">Response Template</th>
                                <th class="pb-3 font-medium">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-800">
                            @foreach($keywords as $keyword)
                            <tr>
                                <td class="py-3">
                                    <span class="bg-purple-500/20 text-purple-300 px-3 py-1 rounded-full text-sm font-mono">{{ $keyword->keyword }}</span>
                                </td>
                                <td class="py-3 text-gray-300">{{ $keyword->action_label }}</td>
                                <td class="py-3 text-gray-400 text-sm max-w-xs truncate">{{ $keyword->response_template ?? '—' }}</td>
                                <td class="py-3">
                                    @if($keyword->is_active)
                                        <span class="text-green-400 text-sm flex items-center gap-1">
                                            <span class="w-1.5 h-1.5 bg-green-400 rounded-full"></span> Active
                                        </span>
                                    @else
                                        <span class="text-gray-500 text-sm flex items-center gap-1">
                                            <span class="w-1.5 h-1.5 bg-gray-500 rounded-full"></span> Inactive
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-800">
                    <a href="{{ route('client.keywords') }}" class="btn-gradient text-white px-4 py-2 rounded-lg text-sm inline-flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Manage Keywords
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

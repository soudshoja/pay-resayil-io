@extends('client.layout')

@section('title', 'WhatsApp')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold text-white mb-4">WhatsApp Conversations</h1>
    <iframe
        src="https://wa.resayil.io/"
        class="w-full h-[calc(100vh-150px)] rounded-xl border border-gray-700 bg-gray-900"
        allow="microphone; camera"
    ></iframe>
</div>
@endsection

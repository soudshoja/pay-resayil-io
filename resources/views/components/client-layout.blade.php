@props(['title' => 'Dashboard'])

@extends('client.layout', ['title' => $title])

@section('title', $title)

@section('content')
{{ $slot }}
@endsection

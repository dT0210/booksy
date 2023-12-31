@extends('layout')

@section('title', $genre->name . ' Books')

@section('content')
@php
    
@endphp

<div class="w-full">
    <div class="w-[60%] mx-auto pt-5">
        <div class="border-b border-slate-300 pb-1">
            <div class="text-3xl font-serif font-bold text-cyan-900 mb-2">
                {{$genre->name}}
            </div>
            <div>
                {{$genre->description}}
            </div>
        </div>
        <div class="mt-2">
            <x-book-row title="New releases" :books="$new" id="book-row-new"/>
            <x-book-row title="Highly rated" :books="$best" id="book-row-best"/>
            <x-book-row title="Most popular" :books="$popular" id="book-row-popular"/>
        </div>
    </div>
</div>
@endsection
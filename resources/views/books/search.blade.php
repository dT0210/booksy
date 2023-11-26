@extends('layout')

@section('content')
@php
    $books = $results->paginate(10);
@endphp
<div class="w-full">
    <div class="w-[92%] mx-auto pt-5">
        <div class="font-serif text-3xl text-cyan-900 font-bold border-b border-slate-300">Results for "{{$query}}"</div>
        <div class="mt-3">
            <div class="flex flex-wrap">
                @foreach ($books as $book)
                <div class="mr-2 mb-2">
                    <x-book-card :book="$book"/>
                </div>
                @endforeach
            </div>
            <div class="">
                {{$books->links()}}
            </div>
        </div>
    </div>
    
</div>

@endsection
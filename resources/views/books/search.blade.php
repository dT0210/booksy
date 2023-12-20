@extends('layout')

@section('title', 'Search results for ' . $query)

@section('content')
@php
    $books = $results->paginate(10);
@endphp
<div class="w-full">
    <div class="w-[92%] mx-auto pt-5">
        <div class="font-serif text-3xl text-cyan-900 font-bold border-b border-slate-300">Results for "{{$query}}"</div>
        <div class="mt-3">
            @if (count($books) != 0)
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
            @else
                <div>
                    No results
                </div>
            @endif
            
        </div>
    </div>
    
</div>

@endsection
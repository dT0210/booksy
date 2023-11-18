@extends('layout')

@section('content')



<div class="mt-16 grid grid-cols-3">
    <div class="col-span-1 ml-3">
        @auth
        @php
            $readingBooks = auth()->user()->books('Reading')->get();
        @endphp
        <div class="m-3">
            <div class="font-medium mb-2">
                CURRENTLY READING
            </div>
            <div>
                @if ($readingBooks->count() == 0)
                    <div>
                        What are you reading?
                    </div>
                    <div class="mt-2">
                        <a href="/user/recommendation" class="text-small text-blue-700 hover:underline">Recommendations</a>
                    </div>
                @else
                    @foreach ($readingBooks as $book)
                        <div>
                            <x-book-card :book="$book"/>
                        </div>
                    @endforeach 
                @endif 
               
            </div>
        </div>
        @endauth
    </div>
    <div class="p-5 col-span-2 ml-3">
        <x-book-row title="New releases" :books="$new" id="book-row-new"/>
        <x-book-row title="Highly rated" :books="$best" id="book-row-best"/>
        <x-book-row title="Most popular" :books="$popular" id="book-row-popular"/>
    </div>
</div>

@endsection
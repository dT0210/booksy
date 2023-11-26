@extends('layout')

@section('content')

<div class="grid grid-cols-3">
    <div class="col-span-1 ml-3">
        @auth
        @php
            $readingBooks = auth()->user()->books('reading')->latest('shelf.date_added')->take(2)->get();
            $toReadBooks = auth()->user()->books('to-read')->latest('shelf.date_added')->take(2)->get();
        @endphp
        <div class="m-3 border-b border-slate-300 p-2 pb-4">
            <div class="flex justify-between">
                <div class="font-medium mb-2">
                    CURRENTLY READING
                </div>
                <div>
                    <a href="/shelves?shelf=reading" class="text-cyan-900 text-sm hover:underline">View all</a>
                </div>
            </div>
            <div>
                @if ($readingBooks->count() == 0)
                    <div class="flex items-center">
                        <div class="mr-3">
                            <img src="/images/book.svg" alt="">
                        </div>
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
        <div class="m-3 p-2">
            <div class="flex justify-between">
                <div class="font-medium mb-2">
                    WANT TO READ
                </div>
                <div>
                    <a href="/shelves?shelf=to-read" class="text-cyan-900 text-sm hover:underline">View all</a>
                </div>
            </div>
            <div>
                @if ($toReadBooks->count() == 0)
                    <div class="flex items-center">
                        <div class="mr-3">
                            <img src="/images/book.svg" alt="">
                        </div>
                        What do you want to read next?
                    </div>
                    <div class="mt-2">
                        <a href="/user/recommendation" class="text-small text-blue-700 hover:underline">Recommendations</a>
                    </div>
                @else
                    @foreach ($toReadBooks as $book)
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
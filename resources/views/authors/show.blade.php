@extends('layout')

@php

@endphp

@section('content')
<div class="mt-20">
    <div class="grid grid-cols-4 mx-auto w-[90%] my-5">
        <div class="col-span-1 mx-auto">
            <div class="px-5">
                <img src="{{$author->picture}}" alt="" onerror="this.src='/images/author_noimages.png';">
            </div>
        </div>
        <div class="font-serif col-span-3 mx-3 px-5">
            <div class="text-3xl mb-3 font font-semibold">
                {{$author->name}}
            </div>
            <div class="mb-10">
                {!!nl2br($author->biography)!!}
            </div>
            <div class="">
                <x-book-row title="{{$author->name}}'s books" :books="$author->contribution" id='book-row-{{$author->name}}'/>
            </div>
        </div>
    </div>
    
</div>
@endsection
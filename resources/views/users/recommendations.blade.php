@extends('layout')

@section('title', 'Recommendations for you')

@section('content')
<div class="w-full">
    <div class="w-[60%] mx-auto pt-5">
        <div class="border-b border-slate-300 pb-1">
            <div class="text-3xl font-serif font-bold text-cyan-900 mb-2">
                Recommendations
            </div>
            <div>
                Here are some books we think you'll love.
            </div>
        </div>
        <div class="mt-2">
            @auth
                
            @else
                Please <span><a href="/user/sign_in" class="hover:underline text-cyan-900">sign in</a></span> to continue
            @endauth
            {{--
                This will be multiple rows of books
                 <x-book-row title="New releases" :books="$new" id="book-row-new"/> 
                 --}}
        </div>
    </div>
</div>
@endsection
@extends('layout')

@section('content')

@php
    $contributors = $book->contributors;
    $stringContributors = [];

    foreach ($contributors as $contributor) {
        if ($contributor->contribution->role == 'author') {
            $author = $contributor;
            array_unshift($stringContributors, "<a href='/author/show/" . $contributor->id . "' class='hover:underline'>" . $contributor->name . "</a>");
        } else {
            $stringContributors[] = "<a href='/author/show/" . $contributor->id . "' class='hover:underline'>" . $contributor->name . "</a>" . ' <span class="text-gray-500">(' . $contributor['contribution']['role'] . ')</span>';
        }
    }
    $formattedContributors = implode(', ', $stringContributors);

    $genres = $book['genres']->toArray();
    $formattedGenres = '';

    foreach ($genres as $genre) {
        $formattedGenres .= "<a href='/genre/show/" . $genre['id'] . "' class='hover:underline'>" . $genre['name'] . "</a>, ";
    }

    $formattedGenres = rtrim($formattedGenres, ', ');

    $formatDate = function($date)
    {
        $dateParts = explode('-', $date);
        $year = $dateParts[0];
        $month = date('F', mktime(0, 0, 0, $dateParts[1], 1));
        $day = ltrim($dateParts[2], '0');

        return $month . ' ' . $day . ', ' . $year;
    }
@endphp
@auth
    @php
        $shelf = $book->shelf(auth()->user());
        if (isset($shelf)) {
            if ($shelf->shelf_name == 'read')
                $shelfName = 'Read';
            elseif ($shelf->shelf_name == 'reading')
                $shelfName = 'Currently Reading';
            else 
                $shelfName = 'Want to Read';
        }
        
        $rating = $book->rating(auth()->user());
    @endphp
@endauth



<div class="grid grid-cols-7 w-6/7 pt-5 min-h-screen pb-5">
    <div class="px-10 col-span-2">
        <div class="sticky top-[84px] max-h-full">
            <div class="px-10 flex items-center justify-center"><img src="{{$book->cover}}" alt="" class="h-72 shadow-[0_0_10px_10px_rgba(100,100,100,0.2)]"></div>
            <div class="w-4/5 mx-auto my-8 font-medium">
                <div class="h-10 mb-3">
                    @if (isset($shelfName))
                        <button id="shelfButton" class="w-full h-full hover:cursor-pointer flex items-center justify-center border rounded-full border-slate-900 hover:bg-slate-100">
                            <i class="w-6 inline-flex items-center justify-center mr-2">
                                <svg viewBox="0 0 24 24" class="w-full h-full fill-current">
                                    <path d="M15.5115349,3.2704653 L4.83002247,13.8642153 C4.73634481,13.9571233 4.66883362,14.0730991 4.63430284,14.2004373 L3.03529771,20.0970438 C2.88364936,20.6562732 
                                    3.39640087,21.168917 3.95559842,21.0171513 L9.51845853,19.5073957 C9.64453607,19.4731784 9.75948935,19.4066226 9.85193588,19.3143187 L20.688734,8.49424033 C20.9809777
                                    ,8.20244749 20.9823427,7.7293023 20.6917875,7.43582808 L16.5726469,3.27530562 C16.2810413,2.98077049 15.8058154,2.97860272 15.5115349,3.2704653 Z M16.034,4.864 L19.099
                                    ,7.96 L8.933,18.111 L4.826,19.226 L6.029,14.787 L16.034,4.864 Z"></path>
                                    <polygon points="13.2645351 7.42163086 14.3251953 6.36097069 17.6638153 9.69959068 16.6031551 10.7602509"></polygon>
                                    <rect transform="translate(5.369675, 18.698100) rotate(-45.000000) translate(-5.369675, -18.698100) " x="4.61967468" y="17.5211501" width="1.5" height="2.35390081"></rect>
                                </svg>
                            </i>
                            {{$shelfName}}
                        </button>
                    @else
                    <form method="POST" action="/book/shelf" class="w-full h-full">
                        @csrf
                        @auth
                            <input type="hidden" name="userId" value="{{auth()->user()->account_id}}">
                        @else
                            <input type="hidden" name="userId" value="not signed in">
                        @endauth
                        <input type="hidden" name="bookId" value="{{$book->id}}">
                        <input type="hidden" name="shelfName" value="to-read">
                        <button type="submit" class="w-full h-full hover:cursor-pointer flex items-center justify-center rounded-full text-white bg-emerald-700 hover:bg-emerald-600">
                            Want to Read
                        </button>  
                    </form>
                    @endif
                    
                </div>
                <div class="h-10 text-center">
                    <a href="{{isset($book->purchase_link) ? $book->purchase_link : 'https://www.amazon.com/s?k=' . $book->title}}" target="_blank" class="flex items-center justify-center w-full h-full hover:cursor-pointer rounded-full border border-slate-900 bg-transparent hover:bg-slate-100 ">Buy on Amazon</a>
                </div>
                <div class="flex mt-3 flex-col items-center justify-center">
                    <form method="POST" action="/book/rate" class="flex items-center">
                        @csrf
                        <style>
                            .rated {
                                color:rgb(255, 150, 0) !important;
                            }
                            .rate button {
                                color:white;
                            }
                            .rate button:hover ~ button {
                                color:white !important;
                            }
                            .rate button:hover ~ button path{
                                stroke: grey;
                            }
                            .rate button:hover path {
                                stroke: rgb(255, 150, 0);
                            }
                            .rate:hover button {
                                color:rgb(255, 150, 0);
                            }
                            .rate:hover button path {
                                stroke:rgb(255, 150, 0);
                            }
                        </style>
                        <input type="hidden" name="bookId" value="{{$book->id}}">
                        @auth
                            <input type="hidden" name="userId" value="{{auth()->user()->account_id}}">
                        @else
                            <input type="hidden" name="userId" value="not signed in">
                        @endauth
                        <div class="rate flex">
                            <button class="w-10 h-10 p-1 <?php if (isset($rating) && $rating->rating >= 1) echo "rated"; ?>" type="submit" name="ratingValue" value="1">
                                <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 1.564l2.48 7.604h7.993l-6.47 4.698 2.48 7.604-6.47-4.698-6.47 4.698 2.48-7.604-6.47-4.698h7.993z" stroke="grey" stroke-width="1"/>
                                </svg>
                            </button>
                            <button class="w-10 h-10 p-1 <?php if (isset($rating) && $rating->rating >= 2) echo "rated"; ?>" type="submit" name="ratingValue" value="2">
                                <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 1.564l2.48 7.604h7.993l-6.47 4.698 2.48 7.604-6.47-4.698-6.47 4.698 2.48-7.604-6.47-4.698h7.993z" stroke="grey" stroke-width="1"/>
                                </svg>
                            </button>
                            <button class="w-10 h-10 p-1 <?php if (isset($rating) && $rating->rating >= 3) echo "rated"; ?>" type="submit" name="ratingValue" value="3">
                                <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 1.564l2.48 7.604h7.993l-6.47 4.698 2.48 7.604-6.47-4.698-6.47 4.698 2.48-7.604-6.47-4.698h7.993z" stroke="grey" stroke-width="1"/>
                                </svg> 
                            </button>
                            <button class="w-10 h-10 p-1 <?php if (isset($rating) && $rating->rating >= 4) echo "rated"; ?>" type="submit" name="ratingValue" value="4">
                                <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 1.564l2.48 7.604h7.993l-6.47 4.698 2.48 7.604-6.47-4.698-6.47 4.698 2.48-7.604-6.47-4.698h7.993z" stroke="grey" stroke-width="1"/>
                                </svg>
                            </button>
                            <button class="w-10 h-10 p-1 <?php if (isset($rating) && $rating->rating == 5) echo "rated"; ?>" type="submit" name="ratingValue" value="5">
                                <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 1.564l2.48 7.604h7.993l-6.47 4.698 2.48 7.604-6.47-4.698-6.47 4.698 2.48-7.604-6.47-4.698h7.993z" stroke="grey" stroke-width="1"/>
                                </svg>  
                            </button>
                        </div>
                    </form>
                    <div class="text-sm text-gray-700">
                        @if (isset($rating))
                            Rated. <a href="" class="underline">Write a review</a>
                        @else
                            Rate this book
                        @endif
                    </div>
            </div>
            </div>
        </div>    
    </div>
    <div class="mr-20 col-span-5">
        <div class="">
            <div class="font-serif text-5xl my-2 font-medium">
                {{$book->title}}  
            </div>
            <div class="text-2xl my-2 font-light">
                {!!$formattedContributors!!}
            </div>
            <div class="my-2 flex items-center">
                <div class="flex">
                    <div class="mr-2">
                        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="32" height="32">
                            <linearGradient id="float1" y2="0" x2="1" x1="0" y1="0">
                                <stop id="stop1-1" stop-color="grey" offset="1"/>
                                <stop id="stop2-1" stop-color="rgb(255, 150, 0)" offset="0"/>
                            </linearGradient>
                            <path d="M12 1.564l2.48 7.604h7.993l-6.47 4.698 2.48 7.604-6.47-4.698-6.47 4.698 2.48-7.604-6.47-4.698h7.993z" fill="url(#float1)"/>
                        </svg> 
                    </div>
                    <div class="mr-2">
                        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="32" height="32">
                            <linearGradient id="float2" y2="0" x2="1"x1="0" y1="0">
                                <stop id="stop1-2" stop-color="grey" offset="1"/>
                                <stop id="stop2-2" stop-color="rgb(255, 150, 0)" offset="0"/>
                            </linearGradient>
                            <path d="M12 1.564l2.48 7.604h7.993l-6.47 4.698 2.48 7.604-6.47-4.698-6.47 4.698 2.48-7.604-6.47-4.698h7.993z" fill="url(#float2)"/>
                        </svg> 
                    </div>
                    <div class="mr-2">
                        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="32" height="32">
                            <linearGradient id="float3" y2="0" x2="1" x1="0" y1="0">
                                <stop id="stop1-3" stop-color="grey" offset="1"/>
                                <stop id="stop2-3" stop-color="rgb(255, 150, 0)" offset="0"/>
                            </linearGradient>
                            <path d="M12 1.564l2.48 7.604h7.993l-6.47 4.698 2.48 7.604-6.47-4.698-6.47 4.698 2.48-7.604-6.47-4.698h7.993z" fill="url(#float3)"/>
                        </svg> 
                    </div>
                    <div class="mr-2">
                        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="32" height="32">
                            <linearGradient id="float4" y2="0" x2="1" x1="0" y1="0">
                                <stop id="stop1-4" stop-color="grey" offset="1"/>
                                <stop id="stop2-4" stop-color="rgb(255, 150, 0)" offset="0"/>
                            </linearGradient>
                            <path d="M12 1.564l2.48 7.604h7.993l-6.47 4.698 2.48 7.604-6.47-4.698-6.47 4.698 2.48-7.604-6.47-4.698h7.993z" fill="url(#float4)"/>
                        </svg> 
                    </div>
                    <div class="mr-2">
                        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="32" height="32">
                            <linearGradient id="float5" y2="0" x2="1" x1="0" y1="0">
                                <stop id="stop1-5" stop-color="grey" offset="1"/>
                                <stop id="stop2-5" stop-color="rgb(255, 150, 0)" offset="0"/>
                            </linearGradient>
                            <path d="M12 1.564l2.48 7.604h7.993l-6.47 4.698 2.48 7.604-6.47-4.698-6.47 4.698 2.48-7.604-6.47-4.698h7.993z" fill="url(#float5)"/>
                        </svg> 
                    </div>
                </div>
                <div class="font-serif font-medium text-2xl mr-2">
                    {{number_format($book->ratings()->avg('rating'),2)}}
                </div>
                <div class="text-sm mx-1 text-slate-500">
                    {{$book->ratings()->count('rating')}} ratings
                </div>
                <div class="text-sm mx-1 text-slate-500">
                    {{$book->ratings()->count('review')}} reviews
                </div>
            </div>
            <div class="my-2 pr-32">
                <div class="overflow-hidden max-h-24" id="book-desc">
                    {!!nl2br($book->description)!!}
                </div>
                <button id="desc-button" class="font-semibold">Show more</button>
            </div>
            <div>
                <span>Genres</span>
                <span class="font-semibold ml-2">{!!$formattedGenres!!}</span>
            </div>
            <div class="text-small mt-2 text-slate-500 font-medium">
                First published {{$formatDate($book->publish_date)}}
            </div>
        </div>
        <div class="border-t-2 mt-8 pt-3">
            <div class="font-serif text-xl font-medium">About the author</div>
            <div class="flex my-5">
                <a href="/author/show/{{$author->id}}" class="w-20 h-20 mr-3">
                    <img src="{{$author->picture}}" alt="" class="w-full h-full object-cover rounded-full" onerror="this.src='/images/author_noimages.png';">
                </a>
                <div class="flex flex-col justify-center">
                    <div class="font-serif">
                        <a href="/author/show/{{$author->id}}" class="hover:underline">{{$author->name}}</a>
                    </div>
                    <div class="text-gray-500">
                        {{$author->contribution->where('contributor_id', $author->id)->count()}} books
                    </div>
                </div>
            </div>
            <div>
                <div class="overflow-hidden max-h-24" id="author-bio">
                    {!!nl2br($author->biography)!!}
                </div>
                <button id="bio-button" class="font-semibold">Show more</button>
            </div>
        </div>
        <div class="w-full border-t-2 mt-8 pt-3">
            <form action="/book/rate" method="POST" id="review_form">
                @csrf
                <input type="hidden" name="bookId" value="{{$book->id}}">
                @auth
                    <input type="hidden" name="userId" value="{{auth()->user()->account_id}}">
                @else
                    <input type="hidden" name="userId" value="not signed in">
                @endauth
                <div class="font-serif text-xl font-medium mb-1">
                    Write a review
                </div>
                <textarea 
                    name="review"
                    class="w-full h-32 border border-slate-400 rounded-md focus:outline-none focus:border-slate-600 focus:shadow-lg p-1"
                    placeholder="Enter your review (optional)"
                >@if (isset($rating->review) && $rating->review != ''){{$rating->review}}@endif</textarea>
                <div class="flex justify-end">
                    <button type="submit" class="border border-slate-600 font-semibold pl-3 pr-3 pt-1 pb-1 rounded-md">Post</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div id="overlay" class="fixed top-0 w-full h-full bg-opacity-50 bg-black z-50 hidden items-center justify-center">
    <div id="modal" class="relative w-96 h-80 bg-white rounded-lg p-5 font-medium">
        <div class="font-serif text-2xl">Choose a shelf for this book</div>
        <div class="absolute right-3 top-3">
            <button class="rounded-full w-10 h-10 hover:bg-slate-100" onclick="document.getElementById('overlay').style.display='none';">&#x2715;</button>
        </div>
        <div class="mt-5">
            <form method="POST" action="/book/shelf" class="m-3 h-12">
                @csrf
                @auth
                    <input type="hidden" name="userId" value="{{auth()->user()->account_id}}">
                @else
                    <input type="hidden" name="userId" value="not signed in">
                @endauth
                <input type="hidden" name="bookId" value="{{$book->id}}">
                <input type="hidden" name="shelfName" value="to-read">
                <button type="submit" class="w-full h-full border border-black rounded-full hover:bg-slate-100">
                    @if (isset($shelfName) && $shelfName == "Want to Read")
                        &#10003;
                    @endif
                    Want to Read
                </button>
            </form>
            <form method="POST" action="/book/shelf" class="m-3 h-12">
                @csrf
                @auth
                    <input type="hidden" name="userId" value="{{auth()->user()->account_id}}">
                @else
                    <input type="hidden" name="userId" value="not signed in">
                @endauth
                <input type="hidden" name="bookId" value="{{$book->id}}">
                <input type="hidden" name="shelfName" value="reading">
                <button type="submit" class="w-full h-full border border-black rounded-full hover:bg-slate-100">
                    @if (isset($shelfName) && $shelfName == "Reading")
                        &#10003;
                    @endif
                    Currently Reading
                </button>
            </form>
            <form method="POST" action="/book/shelf" class="m-3 h-12">
                @csrf
                @auth
                    <input type="hidden" name="userId" value="{{auth()->user()->account_id}}">
                @else
                    <input type="hidden" name="userId" value="not signed in">
                @endauth
                <input type="hidden" name="bookId" value="{{$book->id}}">
                <input type="hidden" name="shelfName" value="read">
                <button type="submit" class="w-full h-full border border-black rounded-full hover:bg-slate-100">
                    @if (isset($shelfName) && $shelfName == "Have Read")
                        &#10003;
                    @endif
                    Read
                </button>
            </form>
            <form method="POST" action="/book/shelf" class="m-3 h-12 flex items-center justify-center">
                @csrf
                @auth
                    <input type="hidden" name="userId" value="{{auth()->user()->account_id}}">
                @else
                    <input type="hidden" name="userId" value="not signed in">
                @endauth
                <input type="hidden" name="bookId" value="{{$book->id}}">
                <input type="hidden" name="shelfName" value="remove">
                <button type="submit" class="flex items-center justify-center hover:underline">
                    <i class="w-6 inline-flex items-center justify-center mr-1">
                        <svg viewBox="0 0 24 24">
                            <path d="M19.4246216,9.01159668 L4.57214355,9.01159668 C4.07823126,9.01159668 3.71916988,9.48073147 3.84818021,9.95749734 L6.69467679,20.4769065 C6.78316294,20.8039127 7.07987346,21.0310059 7.41864014,21.0310059 L16.5803972,21.0310059 C16.91922,21.0310059 17.215965,20.803839 17.3044,20.4767608 L20.1486243,9.95735162 C20.2775224,9.48062022 19.9184713,9.01159668 19.4246216,9.01159668 Z M18.444,10.511 L16.006,19.531 L7.992,19.531 L5.552,10.511 L18.444,10.511 Z"></path>
                            <path d="M20.2266846,6.00195312 C20.6408981,6.00195312 20.9766846,6.33773956 20.9766846,6.75195312 C20.9766846,7.13164889 20.6945307,7.44544409 20.3284551,7.49510651 L20.2266846,7.50195312 L3.77111816,7.50195312 C3.3569046,7.50195312 3.02111816,7.16616669 3.02111816,6.75195312 C3.02111816,6.37225736 3.30327205,6.05846216 3.66934761,6.00879974 L3.77111816,6.00195312 L20.2266846,6.00195312 Z"></path>
                            <path d="M13.9777803,2.99532538 C14.2894121,2.99532538 14.5645881,3.18732797 14.6758826,3.4710676 L14.7067363,3.56890761 L15.3719003,6.31735664 L13.9139884,6.67019219 L13.387,4.495 L10.596,4.495 L9.93685247,6.9469549 L8.4884405,6.55695135 L9.2980137,3.5503236 C9.37638712,3.25925702 9.61970127,3.04712503 9.91097318,3.003578 L10.0222197,2.99532538 L13.9777803,2.99532538 Z"></path>
                            <path d="M12.0249192,12.1673601 C12.4046149,12.1673601 12.7184101,12.449514 12.7680725,12.8155896 L12.7749192,12.9173601 L12.7749192,17.2346076 C12.7749192,17.6488212 12.4391327,17.9846076 12.0249192,17.9846076 C11.6452234,17.9846076 11.3314282,17.7024537 11.2817658,17.3363781 L11.2749192,17.2346076 L11.2749192,12.9173601 C11.2749192,12.5031466 11.6107056,12.1673601 12.0249192,12.1673601 Z"></path>
                            <path d="M8.29496062,12.1929158 C8.66171857,12.0946433 9.03784825,12.2859668 9.1805658,12.6267151 L9.21351928,12.7232459 L10.3703222,17.0404933 C10.4775286,17.4405929 10.2400917,17.8518456 9.83999216,17.959052 C9.47323421,18.0573245 9.09710453,17.8660009 8.95438699,17.5252527 L8.92143351,17.4287219 L7.76463054,13.1114744 C7.65742418,12.7113748 7.89486105,12.3001221 8.29496062,12.1929158 Z"></path>
                            <path d="M14.836319,12.7232459 C14.9435254,12.3231463 15.3547781,12.0857094 15.7548777,12.1929158 C16.1216356,12.2911883 16.3517117,12.6449442 16.3049346,13.0113996 L16.2852078,13.1114744 L15.1284048,17.4287219 C15.0211984,17.8288215 14.6099457,18.0662583 14.2098461,17.959052 C13.8430882,17.8607795 13.6130121,17.5070236 13.6597892,17.1405682 L13.6795161,17.0404933 L14.836319,12.7232459 Z"></path>
                        </svg>
                    </i>
                    Remove from my shelf
                </button>
            </form>
        </div>
    </div>
</div>
<script>
    const authorBio = document.getElementById('author-bio');
    const bookDesc = document.getElementById('book-desc');
    const bioBtn = document.getElementById('bio-button');
    const descBtn = document.getElementById('desc-button');

    bioBtn.addEventListener('click', function() {
        authorBio.classList.toggle('overflow-hidden');
        authorBio.classList.toggle('max-h-24');
        if(authorBio.classList.contains('overflow-hidden')) 
            bioBtn.innerHTML = 'Show more';
        else {
            bioBtn.innerHTML = 'Show less';
        }
    });

    descBtn.addEventListener('click', function() {
        bookDesc.classList.toggle('overflow-hidden');
        bookDesc.classList.toggle('max-h-24');
        if(bookDesc.classList.contains('overflow-hidden')) 
            descBtn.innerHTML = 'Show more';
        else {
            descBtn.innerHTML = 'Show less';
        }
    });
</script>
<script>
    const rating = {{$book->ratings()->avg('rating')}};
    const fullStars = Math.floor(rating);
    const partialStar = rating - fullStars;

    for (let i = 1; i <= fullStars; i++) {
        const stop1 = document.getElementById(`stop1-${i}`);
        const stop2 = document.getElementById(`stop2-${i}`);
        stop1.setAttribute('stop-color', 'rgb(255, 150, 0)');
        stop2.setAttribute('stop-color', 'grey');
    }
    if (partialStar > 0) {
        const stop1 = document.getElementById(`stop1-${fullStars + 1}`);
        const stop2 = document.getElementById(`stop2-${fullStars + 1}`);
        stop1.setAttribute('stop-color', 'rgb(255, 150, 0)');
        stop2.setAttribute('stop-color', 'grey');
        stop1.setAttribute('offset', partialStar.toFixed(2));
        stop2.setAttribute('offset', partialStar.toFixed(2));
    }
</script>

@endsection

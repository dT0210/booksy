@extends('layout')

@section('title', 'Your books')

@section('content')

@auth
<div class="pt-3 h-full">
    <div class="w-[80%] mx-auto">
        <div class="mb-3">
            <div class="border-b-2 border-slate-300 pb-1 text-cyan-900">
                @if (isset($shelf))
                    <span class="font-serif font-bold text-3xl">My Books:</span>
                    <span class="font-serif text-2xl bg-slate-300 pt-1 pb-1 pl-2 pr-2 rounded-md inline-flex items-center">{{$shelf}} <a href="/shelves" class="text-sm ml-2 font-bold">&#10005;</a></span>
                @else
                    <span class="font-serif font-bold text-3xl">My Books</span>
                @endif
            </div>
        </div>
        <div class="grid grid-cols-5">
            <div class="col-span-1 mr-3">
                <div class="border-b-2 border-slate-300 pb-1">
                    <div class="font-medium">Bookshelves</div>
                    <div class="">
                        <div>
                            <a href="/shelves" class="@php echo isset($shelf) ? "text-cyan-900" : "text-stone-400"@endphp hover:underline">
                                All ({{$user->books()->count()}})
                            </a>
                        </div>
                        <div>
                            <a href="/shelves?shelf=read" class="@php echo (isset($shelf) && $shelf == 'read') ? "text-stone-400" : "text-cyan-900"@endphp hover:underline">
                                Read ({{$user->books('read')->count()}})
                            </a>
                        </div>
                        <div>
                            <a href="/shelves?shelf=reading" class="@php echo (isset($shelf) && $shelf == 'reading') ? "text-stone-400" : "text-cyan-900"@endphp hover:underline">
                                Currently Reading ({{$user->books('reading')->count()}})
                            </a>
                        </div>
                        <div>
                            <a href="/shelves?shelf=to-read" class="@php echo (isset($shelf) && $shelf == 'to-read') ? "text-stone-400" : "text-cyan-900"@endphp hover:underline">
                                Want to Read ({{$user->books('to-read')->count()}})
                            </a>
                        </div>
                    </div>
                </div>
                <div class="border-b-2 border-slate-300 pb-1 mt-3">
                    <div class="font-medium">Add books</div>
                    <div><a href="/recommendations" class="text-cyan-900 hover:underline">Recommendations</a></div>
                </div>
            </div>
            <div class="col-span-4">
                <table class="w-full text-sm">
                    <tr class="border-b-2 border-slate-300">
                        <th class="w-20">cover</th>
                        <th class="w-36">title</th>
                        <th class="w-20">author</th>
                        <th class="w-16">avg rating</th>
                        <th class="w-28">your rating</th>
                        <th class="w-24">shelf</th>
                        <th>review</th>
                        <th class="w-12">date added</th>
                        <th class="w-10"></th>
                    </tr>
                    @if (count($books) != 0)
                        @foreach ($books as $book)
                        @php
                            $date = new DateTime($book->shelf($user)->date_added);
                            $formattedDate = $date->format('F j, Y');
                        @endphp
                        <tr class="border-b-[1px] border-slate-100 text-center">
                            <td class="p-2"><img src="{{$book->cover}}" alt="/images/no-image.png" class="w-16"></td>
                            <td><a href="/book/show/{{$book->id}}" class="text-cyan-900 hover:underline">{{$book->title}}</a></td>
                            <td><a href="/author/show/{{$book->authors()->first()->id}}" class="text-cyan-900 hover:underline">{{$book->authors()->first()->name}}</a></td>
                            <td>{{number_format($book->ratings()->avg('rating'),2)}}</td>
                            <td>
                                <form method="POST" action="/book/rate" class="flex items-center">
                                    @csrf
                                    <style>
                                        .rated {
                                            color:rgb(255, 150, 0) !important;
                                        }
                                        .rate button {
                                            color:grey;
                                        }
                                        .rate button:hover ~ button {
                                            color:grey !important;
                                        }
                                        .rate:hover button {
                                            color:rgb(255, 150, 0);
                                        }
                                        
                                    </style>
                                    <input type="hidden" name="bookId" value="{{$book->id}}">
                                    <input type="hidden" name="userId" value="{{$user->account_id}}">
                                    <span class="rate">
                                        @php
                                            $rating = $book->rating($user);
                                        @endphp
                                        <button class="w-4 h-4 <?php if (isset($rating) && $rating->rating >= 1) echo "rated"; ?>" type="submit" name="ratingValue" value="1">
                                            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 1.564l2.48 7.604h7.993l-6.47 4.698 2.48 7.604-6.47-4.698-6.47 4.698 2.48-7.604-6.47-4.698h7.993z" stroke="grey" stroke-width="1"/>
                                            </svg>
                                        </button>
                                        <button class="w-4 h-4 <?php if (isset($rating) && $rating->rating >= 2) echo "rated"; ?>" type="submit" name="ratingValue" value="2">
                                            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 1.564l2.48 7.604h7.993l-6.47 4.698 2.48 7.604-6.47-4.698-6.47 4.698 2.48-7.604-6.47-4.698h7.993z" stroke="grey" stroke-width="1"/>
                                            </svg>
                                        </button>
                                        <button class="w-4 h-4 <?php if (isset($rating) && $rating->rating >= 3) echo "rated"; ?>" type="submit" name="ratingValue" value="3">
                                            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 1.564l2.48 7.604h7.993l-6.47 4.698 2.48 7.604-6.47-4.698-6.47 4.698 2.48-7.604-6.47-4.698h7.993z" stroke="grey" stroke-width="1"/>
                                            </svg> 
                                        </button>
                                        <button class="w-4 h-4 <?php if (isset($rating) && $rating->rating >= 4) echo "rated"; ?>" type="submit" name="ratingValue" value="4">
                                            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 1.564l2.48 7.604h7.993l-6.47 4.698 2.48 7.604-6.47-4.698-6.47 4.698 2.48-7.604-6.47-4.698h7.993z" stroke="grey" stroke-width="1"/>
                                            </svg>
                                        </button>
                                        <button class="w-4 h-4 <?php if (isset($rating) && $rating->rating == 5) echo "rated"; ?>" type="submit" name="ratingValue" value="5">
                                            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 1.564l2.48 7.604h7.993l-6.47 4.698 2.48 7.604-6.47-4.698-6.47 4.698 2.48-7.604-6.47-4.698h7.993z" stroke="grey" stroke-width="1"/>
                                            </svg>  
                                        </button>
                                    </span>
                                </form>
                            </td>
                            <td><a href="/shelves?shelf={{$book->shelf($user)->shelf_name}}" class="text-cyan-900 hover:underline">{{$book->shelf($user)->shelf_name}}</a></td>
                            <td>
                                @if (isset($rating->review) && $rating->review != '')
                                    <div >
                                        @if (strlen($rating->review) > 90)
                                            {{substr($rating->review, 0, 90)}}...
                                        @else
                                            {{$rating->review}}
                                        @endif
                                        <span class="text-xs"><a href="/book/show/{{$book->id}}#review_form" class="text-cyan-900 hover:underline">[edit]</a></span>
                                    </div>
                                @else
                                    <div class="">
                                        <a href="/book/show/{{$book->id}}#review_form" class="text-cyan-900 hover:underline">Write a review</a>
                                    </div>
                                @endif
                            </td>
                            <td>
                                {{$formattedDate}}
                            </td>
                            <td class="w-10">
                                <form method="POST" action="/book/shelf">
                                    @csrf
                                    <input type="hidden" name="userId" value="{{auth()->user()->account_id}}">
                                    <input type="hidden" name="bookId" value="{{$book->id}}">
                                    <input type="hidden" name="shelfName" value="remove">
                                    <button type="submit" 
                                            class="font-extrabold" 
                                            onclick="return confirm('Do you really want to remove this book from shelves? (This will also remove your rating and review)')">
                                        &#10005;
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    @else
                        <tr><td colspan="8"><div class="w-full flex justify-center mt-3">No matching items!</div></td></tr>
                    @endif
                </table>
                <div class="w-full">
                    {{$books->links()}}
                </div>
            </div>
        </div>
    </div>
</div>
<script>

</script>
@endauth
@endsection
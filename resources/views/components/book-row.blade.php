@props(['title', 'books', 'id'])

<style>
    .row-item:hover .poster {
        cursor: pointer;
        transform: scale(1.05);
    }

    .row-item:hover .title {
        text-decoration: underline;
    }
</style>

<div class="relative w-[816px]">
    <div class="mb-2 text-2xl font-semibold">
        {{$title}}
    </div>
    <div class="absolute top-1 right-5 flex">
        <div class="flex justify-center h-full hover:bg-slate-200 hover:bg-opacity-50 w-8 rounded-full">
            <button class="w-full h-full p-2" onclick="left('{{$id}}')">
                <i>
                    <svg viewBox="0 0 24 24">
                        <path d="M 0 7 l 1.2 -1.371 L 12 16.429 l 10.8 -10.8 l 1.2 1.371 l -12 12 z" transform="rotate(90 12 12)"></path>
                    </svg>
                </i>
            </button>
        </div>
        <div class="flex justify-center h-full hover:bg-slate-200 hover:bg-opacity-50 w-8 rounded-full">
            <button class="w-full h-full p-2" onclick="right('{{$id}}')">
                <i>
                    <svg viewBox="0 0 24 24">
                        <path d="M 0 7 l 1.2 -1.371 L 12 16.429 l 10.8 -10.8 l 1.2 1.371 l -12 12 z" transform="rotate(270 12 12)"></path>
                    </svg>
                </i>
            </button>
        </div>
    </div>
    <div class="h-[450px]">
        <div class="flex overflow-x-auto [scroll-behavior:smooth] w-full h-full no-scrollbar" id="{{$id}}">
            @foreach ($books as $book)
                @php
                    $contributors = $book['contributors'];
                    $author = null;

                    foreach ($contributors as $contributor) {
                        if ($contributor['contribution']['role'] == 'author') {
                            $author = $contributor;
                            break;
                        }
                    }
                @endphp
                <div class="flex-shrink-0 mr-7">
                    <div class="row-item w-44">
                        <div class="poster w-full h-72 my-5 transition-transform">
                            <a href="/book/show/{{$book->id}}" class="w-full h-full">
                                <img src="{{$book->cover}}" alt="" class="w-full h-full object-cover">
                            </a>
                        </div>
                        <div>
                            <div class="title font-medium font-serif">
                                <a href="/book/show/{{$book->id}}" class="w-full h-full">
                                    {{$book->title}}
                                </a>
                            </div>
                            <div class="text-sm">
                                <a href="/author/show/{{$author->id}}" class="hover:underline">{{$author->name}}</a>
                            </div>
                            <div class="flex text-sm my-1">
                                <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20">
                                    <path d="M12 1.564l2.48 7.604h7.993l-6.47 4.698 2.48 7.604-6.47-4.698-6.47 4.698 2.48-7.604-6.47-4.698h7.993z" fill="rgb(255, 150, 0)"/>
                                </svg>
                                <div class="ml-1 font-medium">{{number_format($book->ratings()->avg('rating'),2)}}</div>
                                <div class="ml-3 text-slate-500">{{$book->ratings()->count('rating')}} ratings</div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
    </div>
</div>

<script>
    function left(rowId) {
        const bookRow = document.getElementById(rowId);
        bookRow.scrollLeft -= 816;
    }

    function right(rowId) {
        const bookRow = document.getElementById(rowId);
        console.log(rowId);
        bookRow.scrollLeft += 816;
    }
</script>
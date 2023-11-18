@props(['book'])

@php
    $contributors = $book->contributors;
    $author = null;

    foreach ($contributors as $contributor) {
        if ($contributor['contribution']['role'] == 'author') {
            $author = $contributor;
            break;
        }
    }
@endphp

<div class="flex h-[180px] w-[400px] p-3 bg-teal-50 border border-solid border-gray-200">
    <div class="mr-3 h-full w-1/4">
        <a href="/book/show/{{$book->id}}" class="">
            <img class="h-full" src="{{$book->cover}}" alt="No image" onerror="this.src='/images/no-image.jpg';" >
        </a>
    </div>
    <div class="w-3/4 h-full">
        <div><a href="/book/show/{{$book->id}}" class="font-bold font-serif hover:underline text-md">{{$book->title}}</a></div>
        <div class="text-sm">by <a href="/author/show/{{$author->id}}" class="hover:underline">{{$author->name}}</a></div>
        <div class="flex text-sm my-1">
            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20">
                <path d="M12 1.564l2.48 7.604h7.993l-6.47 4.698 2.48 7.604-6.47-4.698-6.47 4.698 2.48-7.604-6.47-4.698h7.993z" fill="rgb(255, 150, 0)"/>
            </svg>
            <div class="ml-1 font-medium">{{number_format($book->ratings()->avg('rating'),2)}}</div>
            <div class="ml-3 text-slate-500">{{$book->ratings()->count('rating')}} ratings</div>
        </div>
        <div name="card-description" data-id="{{$book->id}}" class=" text-xs">
            <span class="text-neutral-500">{{$book->description}}</span>
        </div>
    </div>
</div>
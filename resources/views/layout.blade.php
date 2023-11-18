<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Booksy</title>
    @vite('resources/css/app.css')
    <style>
        #welcome {
            background-image: linear-gradient(to bottom, rgba(255, 255, 255, 0.5), rgba(0, 0, 0, 0.5)), url(/images/welcome-bg.jpg);
        }
    </style>
    <script type="text/javascript" src="{{ URL::asset('js/app.js') }}"></script>
</head>
<body class="relative">
    @if(session()->has('message'))
        <div id='alert' class="fixed bottom-1 left-1 px-10 py-3 bg-black text-white font-medium z-20">
            {{ session()->get('message') }}
        </div>
        <script>
            setTimeout(function() {
                document.getElementById("alert").style.display = "none";
            }, 3000);
        </script>
    @endif
    <div class="flex fixed top-0 h-16 w-full items-center px-5 bg-slate-400 shadow z-10">
        <a href="/"><img class="w-36" src="/images/Logo2.png" alt=""></a>
        <ul class="flex justify-between items-center px-5 h-full">
            <li class="cursor-pointer hover:text-neutral-50 hover:bg-cyan-900 h-full flex items-center"><a href="/" class="font-medium p-5 w-full h-full">Home</a></li>
            <li class="cursor-pointer hover:text-neutral-50 hover:bg-cyan-900 h-full flex items-center"><a href="/undetermined" class="font-medium p-5 w-full h-full">My books</a></li>
            <li class="cursor-pointer relative hover:text-neutral-50 hover:bg-cyan-900 h-full px-5 flex items-center " onclick="toggleBrowse()">
                <span class="font-medium">Browse <span class="text-xs">&#x25BC;</span></span>
                <div id="browse" class="hidden absolute z-10 bg-gray-200 text-black top-full left-0 p-3 shadow-md hover:cursor-default">
                    <ul>
                        <li><a href="" class="hover:underline">Recommendations</a></li>
                        <li><a href="" class="hover:underline">New Releases</a></li>
                    </ul>
                </div>
            </li>
        </ul>
        <div  class="w-1/3">
            <form action="/search" method="GET">   
                <label for="default-search" class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Search</label>
                <div class="relative">
                    <input type="search" id="query" name="query" autocomplete="off" class="block w-full p-2 text-sm text-gray-900 border border-sky-600 focus:border-sky-950 bg-gray-50 focus:shadow-lg focus:outline-none" placeholder="Search Books, Authors ..." required>
                    <button type="submit" class="text-white absolute right-2.5 bottom-[3px] bg-transparent focus:ring-4 focus:outline-none font-medium rounded-lg text-sm px-3 py-2">
                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                        </svg>
                    </button>
                </div>
            </form>
        </div>
        <ul class="flex justify-between items-center px-5 h-full ml-auto">
            @auth
            <li class="font-serif cursor-pointer relative w-16 hover:text-neutral-50 hover:bg-cyan-900 h-full flex items-center">
                <a href="/user/show/not_finished" class="w-full p-4" onclick="toggleUser()">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="34px" height="34px" viewBox="0 0 50 50" version="1.1">
                        <g id="surface1">
                        <path style="fill:none;stroke-width:30;stroke-linecap:butt;stroke-linejoin:miter;stroke:currentColor;stroke-opacity:1;stroke-miterlimit:4;" d="M 564.984375 300 C 564.984375 446.34375 446.34375 564.984375 300 564.984375 C 153.65625 564.984375 35.015625 446.34375 35.015625 300 C 35.015625 153.65625 153.65625 35.015625 300 35.015625 C 446.34375 35.015625 564.984375 153.65625 564.984375 300 Z M 564.984375 300 " transform="matrix(0.0833333,0,0,0.0833333,0,0)"/>
                        <path style="fill:none;stroke-width:30;stroke-linecap:butt;stroke-linejoin:miter;stroke:currentColor;stroke-opacity:1;stroke-miterlimit:4;" d="M 414.984375 230.015625 C 414.984375 293.53125 363.515625 345 300 345 C 236.484375 345 185.015625 293.53125 185.015625 230.015625 C 185.015625 166.5 236.484375 114.984375 300 114.984375 C 363.515625 114.984375 414.984375 166.5 414.984375 230.015625 Z M 414.984375 230.015625 " transform="matrix(0.0833333,0,0,0.0833333,0,0)"/>
                        <path style="fill:none;stroke-width:30;stroke-linecap:butt;stroke-linejoin:miter;stroke:currentColor;stroke-opacity:1;stroke-miterlimit:4;" d="M 106.828125 481.40625 C 135.84375 399.609375 213.234375 345 300 345 C 386.765625 345 464.15625 399.609375 493.171875 481.40625 " transform="matrix(0.0833333,0,0,0.0833333,0,0)"/>
                        </g>
                    </svg>
                </a>
                <div id="userMenu" class="hidden absolute z-10 w-36 bg-gray-200 text-black top-full right-0 p-3 shadow-md hover:cursor-default">
                    <ul>
                        <li class="my-2 text-md font-semibold">{{auth()->user()->name}}</li>
                        <li><a href="" class="hover:underline">Profile</a></li>
                        <li><a href="" class="hover:underline">Account settings</a></li>
                        <li>
                            <form class="inline" method="POST" action="/user/sign_out">
                                @csrf
                                <button type="submit" class="hover:underline">Sign out</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </li>
            
            @else
            <li class="font-serif cursor-pointer hover:text-neutral-50 hover:bg-cyan-900 h-full px-5 flex items-center"><a href="/user/sign_in" class="font-bold">Sign in</a></li>
            <li class="font-serif cursor-pointer hover:text-neutral-50 hover:bg-cyan-900 h-full px-5 flex items-center"><a href="/user/sign_up" class="font-bold">Join</a></li>
            @endauth
        </ul>
    </div>
    <div class="">
        @yield('content')
    </div>
    
    <script>
        function truncateText(text, length) {
            if (text.length <= length) {
                return text;
            }

            const truncatedText = text.slice(0, length - 3) + "...";
            return truncatedText;
        }

        const maxLength = 180;
        const descriptions = document.getElementsByName("card-description");
        Array.from(descriptions).forEach((description) => {
            const descriptionText = description.textContent;
            var id = description.getAttribute("data-id");
            if (descriptionText.length > maxLength) {
                const truncatedDescription = truncateText(descriptionText, maxLength);
                description.innerHTML = `
                <span class="text-neutral-500">${truncatedDescription}</span>
                <a href="/book/show/${id}" class="hover:underline text-blue-600">Continue reading</a>
                `;
            }
        });

        function toggleBrowse() {
            var browseDetails = document.getElementById("browse");
            browseDetails.classList.toggle('hidden');
            event.stopPropagation();
        }

        function toggleUser() {
            event.preventDefault();
            var userMenu = document.getElementById("userMenu");
            userMenu.classList.toggle('hidden');
            event.stopPropagation();
        }

        document.addEventListener('click', function(event) {
            var targetElement = event.target;
            var browseDetails = document.getElementById("browse");
            var userMenu = document.getElementById("userMenu");

            var isClickInsideBrowse = browseDetails.contains(targetElement);
            var isClickInsideUserMenu = userMenu.contains(targetElement);

            if (!isClickInsideBrowse) {
                browseDetails.classList.add('hidden');
            }
            if (!isClickInsideUserMenu) {
                userMenu.classList.add('hidden');
            }

        });

        function shelfClick() {
        var overlay = document.getElementById('overlay');
            overlay.style.display = "flex";
        }

        document.addEventListener('click', function(event) {
            var targetElement = event.target;
            var modal = document.getElementById("modal");
            var overlay = document.getElementById("overlay");
            var shelfButton = document.getElementById("shelfButton");

            var isClickInsideModal = modal.contains(targetElement);
            var isOverlayVisible = window.getComputedStyle(overlay).display !== "none";
            var isClickOnButton = targetElement === shelfButton;
            if (!isClickInsideModal && isOverlayVisible ) {
                overlay.style.display = "none";
            }
            if (isClickOnButton) {
                shelfClick();
            }
        });
    </script>
</body>
</html>
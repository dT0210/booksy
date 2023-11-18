<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sign in</title>
    @vite('resources/css/app.css')
    @vite('resources/css/nav.css')
</head>
<body>
    <div class="w-full h-full">
        <div class="block h-1/7 pt-5">
            <a href="/" class="block mx-auto w-48"><img src="/images/Logo2.png" alt=""></a>
        </div>
        <div class="block w-[700px] m-auto my-5">
            @error('record')
                
            @enderror
            <div class="text-4xl font-semibold font-serif text-center">Sign in</div>
            <form method="POST" action="/user/authenticate" class="flex flex-col items-center mt-5">
                @csrf
                <div class="my-2">
                    <label class="block mb-1" for="email">Email</label>
                    <input type="email" id="email" name="email" class="w-80 px-3 py-1 border rounded-full border-gray-400 bg-slate-50 hover:bg-slate-100 focus:bg-transparent">
                </div>
                <div class="my-2">
                    <div>
                        <label class="block mb-1" for="password">Password</label>
                        <input type="password" id="password" name="password" class="w-80 px-3 py-1 border rounded-full border-gray-400 bg-slate-50 hover:bg-slate-100 focus:bg-transparent">
                    </div>
                </div>
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{$message}}</p> 
                @enderror
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{$message}}</p> 
                @enderror
                <button type="submit" class="w-80 mt-5 px-3 py-2 rounded-full bg-slate-950 hover:bg-slate-900">
                    <span class="text-white">Sign in</span>
                </button>
                <div class="mt-4 w-80">
                    <input type="checkbox" name="remember" id="remember">
                    <label for="remember">Keep me signed in.</label>
                </div>
            </form>
            <div class="text-center mt-3">
                <div>---- New to Booksy? ----</div>
                <a href="/user/sign_up">
                    <button type="submit" class="w-80 mt-1 px-3 py-2 rounded-full border border-gray-400 bg-transparent hover:bg-slate-100">
                        <span class="">Sign up</span>
                    </button>
                </a>
            </div>
        </div>
    </div>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sign up</title>
    @vite('resources/css/app.css')
</head>
<body>
    <div class="w-full h-full">
        <div class="block h-1/7 pt-5">
            <a href="/" class="block mx-auto w-48"><img src="/images/Logo2.png" alt=""></a>
        </div>
        <div class="block w-[700px] m-auto my-5">
            @error('record')
                
            @enderror
            <div class="text-4xl font-semibold font-serif text-center">Create Account</div>
            <form method="POST" action="/user/sign_up_handle" class="flex flex-col items-center mt-5">
                @csrf
                <div class="my-2">
                    <label class="block mb-1" for="name">Your name</label>
                    <input type="text" id="name" name="name" placeholder="First and last name" class="w-80 px-3 py-1 border rounded-full border-gray-400 bg-slate-50 hover:bg-slate-100 focus:bg-transparent">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{$message}}</p> 
                    @enderror
                </div>
                <div class="my-2">
                    <label class="block mb-1" for="email">Email</label>
                    <input type="email" id="email" name="email" class="w-80 px-3 py-1 border rounded-full border-gray-400 bg-slate-50 hover:bg-slate-100 focus:bg-transparent">
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{$message}}</p> 
                    @enderror
                </div>
                <div class="my-2">
                    <div>
                        <label class="block mb-1" for="password">Password</label>
                        <input type="password" id="password" name="password" placeholder="At least 6 characters" class="w-80 px-3 py-1 border rounded-full border-gray-400 bg-slate-50 hover:bg-slate-100 focus:bg-transparent">
                        @error('password')
                            <p class="text-red-500 text-sm mt-1">{{$message}}</p> 
                        @enderror
                    </div>
                    
                    <div class="mt-4">
                        <label class="block mb-1" for="password_confirmation">Re-enter password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="w-80 px-3 py-1 border rounded-full border-gray-400 bg-slate-50 hover:bg-slate-100 focus:bg-transparent">
                        @error('password_confirmation')
                            <p class="text-red-500 text-sm mt-1">{{$message}}</p> 
                        @enderror
                    </div>
                </div>
                <button type="submit" class="w-80 mt-5 px-3 py-2 rounded-full bg-slate-950 hover:bg-slate-900">
                    <span class="text-white">Create account</span>
                </button>
            </form>
            <div class="text-center mt-5">
                Already have an account? <a href="/user/sign_in" class="underline">Sign in</a>
            </div>
        </div>
    </div>
</body>
</html>
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
    <div id="welcome" class="block w-full min-h-full bg-cover bg-center bg-no-repeat bg-fixed">
        <div class="block h-1/7 pt-5">
            <a href="/" class="block mx-auto w-48"><img src="/images/Logo2.png" alt=""></a>
        </div>
        <div class="flex">
            <div class="w-1/3 font-mono font-semibold text-4xl mt-20 ml-10 pt-2 border-t-8 [border-image:linear-gradient(to_right,#000_30%,transparent_30%)_100%_1]">
                Explore and discover your new favorite books
            </div>
            <div class="block w-96 h-64 mx-auto mt-20 bg-amber-50 rounded-lg">
                <div class="my-5 text-center text-2xl font-semibold">
                    Join now!
                </div>
                <a href="/user/sign_up" class="h-20 my-8 mx-10 bg-slate-500 flex items-center justify-center text-2xl font-semibold">
                    <span class="text-white">SIGN UP</span>
                </a>
                <div class="text-lg mx-10">
                    Already have an account? <a href="/user/sign_in"><span class="text-blue-700 underline">Sign in.</span></a>
                </div>
            </div>
            <div class="w-1/3 font-mono font-semibold text-4xl text-right mt-60 mr-12 border-b-8 [border-image:linear-gradient(to_left,#000_30%,transparent_30%)_100%_1]">
                Cozy corner for book enthusiasts
            </div>
        </div>
        
    </div>
</body>
</html>
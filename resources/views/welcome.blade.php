<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        <link href="https://fonts.bunny.net/css?family=albert-sans:300" rel="stylesheet" />

        <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            @vite(['resources/css/app.css', 'resources/js/app.js'])
            @endif
    </head>
    
    <body class="bg-white text-[#1b1b18] flex p-6 lg:p-8 items-center lg:justify-center min-h-screen flex-col font-albert">
        
        <header class="w-full lg:max-w-4xl max-w-[335px] text-sm mb-6 not-has-[nav]:hidden">
            @if (Route::has('login'))
                <nav class="flex items-center justify-end gap-4">
                    <a href="{{ url ("/") }}">
 <x-application-logo class="block h-9 w-auto text-gray-900" />
                    </a>

                    @auth
                        <a href="{{ url('/dashboard') }}" class="inline-block px-5 py-1.5 text-[#1b1b18] border-[#19140035] hover:border-[#1915014a] border rounded-sm text-sm leading-normal">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="inline-block px-5 py-1.5 text-[#1b1b18] border border-transparent hover:border-[#19140035] rounded-sm text-sm leading-normal">
                            Log in
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="inline-block px-5 py-1.5 text-[#1b1b18] border-[#19140035] hover:border-[#1915014a] border rounded-sm text-sm leading-normal">
                                Register
                            </a>
                        @endif
                    @endauth
                </nav>
            @endif
        </header>

        <main class="w-full lg:max-w-4xl max-w-[335px] flex-grow flex items-center justify-center">
            <section class="w-full text-center px-6 py-12 bg-white rounded-lg">
                
                <h1 class="text-6xl sm:text-8xl font-semibold leading-tight mb-4" data-aos="fade-right">
                    Welcome to Tasky
                </h1>
                
                <p data-aos="fade-up" class="text-lg sm:text-xl text-[#706f6c] mb-8" data-aos-offset="0" data-aos-duration="900">
                    create tasks, make accomplish
                </p>

            

            </section>
        </main>

        @if (Route::has('login'))
            <div class="h-14.5 hidden lg:block"></div>
        @endif


        <script>
     
        </script>
    </body>
</html>
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <!-- Theme Script -->
        <script>
            if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark')
            }
            
            // Global toggle function
            window.toggleTheme = function() {
                if (document.documentElement.classList.contains('dark')) {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('color-theme', 'light');
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('color-theme', 'dark');
                }
            }
        </script>
    </head>
    <body class="font-sans text-gray-900 dark:text-gray-100 antialiased bg-gray-100 dark:bg-gray-950 transition-colors duration-300">
        
        <!-- Background Effects -->
        <div class="fixed inset-0 z-[-1] overflow-hidden">
            <div class="absolute top-0 -left-4 w-72 h-72 bg-purple-300 dark:bg-purple-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob"></div>
            <div class="absolute top-0 -right-4 w-72 h-72 bg-yellow-300 dark:bg-yellow-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-2000"></div>
            <div class="absolute -bottom-8 left-20 w-72 h-72 bg-pink-300 dark:bg-pink-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-4000"></div>
            <div class="absolute inset-0 bg-white/40 dark:bg-black/40 backdrop-blur-[100px]"></div>
        </div>

        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 relative z-10">
            
            <!-- Theme Toggle for Guest -->
            <div class="absolute top-4 right-4">
                <button onclick="window.toggleTheme()" type="button" class="text-gray-500 dark:text-gray-400 hover:bg-gray-200/50 dark:hover:bg-gray-700/50 backdrop-blur-md focus:outline-none rounded-lg text-sm p-2.5">
                    <svg class="w-5 h-5 hidden dark:block" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path></svg>
                    <svg class="w-5 h-5 block dark:hidden" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
                </button>
            </div>

            <div class="mb-8 animate-fade-in-up">
                <a href="/" wire:navigate class="flex flex-col items-center gap-2 group">
                    <div class="p-3 bg-white/70 dark:bg-gray-900/60 backdrop-blur-xl border border-white/20 dark:border-gray-700/30 shadow-lg rounded-2xl group-hover:scale-105 transition-transform duration-300">
                        <x-application-logo class="w-16 h-16 fill-current text-indigo-600 dark:text-indigo-400" />
                    </div>
                    <span class="text-2xl font-black text-transparent bg-clip-text bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 tracking-wider uppercase">Habits</span>
                </a>
            </div>

            <div class="w-full sm:max-w-md px-8 py-8 bg-white/70 dark:bg-gray-900/60 backdrop-blur-xl border border-white/20 dark:border-gray-700/30 shadow-2xl overflow-hidden sm:rounded-3xl animate-fade-in-up" style="animation-delay: 0.1s;">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>

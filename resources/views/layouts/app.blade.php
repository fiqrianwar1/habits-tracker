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
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Theme setup function
            const initTheme = () => {
                if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark')
                }
            };
            
            // Run on initial load
            initTheme();
            
            // Run after Livewire navigations (SPA)
            document.addEventListener('livewire:navigated', initTheme);
            
            // Global toggle function
            window.toggleTheme = function() {
                const isDark = document.documentElement.classList.contains('dark');
                const newTheme = isDark ? 'light' : 'dark';
                
                if (newTheme === 'dark') {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('color-theme', 'dark');
                } else {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('color-theme', 'light');
                }
                
                // Update chart colors instantly if Chart.js is loaded
                if (typeof Chart !== 'undefined') {
                    const textColor = newTheme === 'dark' ? '#f3f4f6' : '#111827';
                    const gridColor = newTheme === 'dark' ? 'rgba(255,255,255,0.1)' : 'rgba(0,0,0,0.1)';
                    
                    Chart.defaults.color = textColor;
                    Chart.defaults.borderColor = gridColor;
                    
                    // Loop through all active charts and force update
                    for (let id in Chart.instances) {
                        let chart = Chart.instances[id];
                        // Also update point border color for line charts if needed
                        if (chart.config.type === 'line' && chart.data.datasets.length > 0) {
                            chart.data.datasets[0].pointBorderColor = newTheme === 'dark' ? '#111827' : '#ffffff';
                        }
                        
                        // Update axis colors if they exist
                        if (chart.options.scales) {
                            if (chart.options.scales.x) {
                                if (!chart.options.scales.x.ticks) chart.options.scales.x.ticks = {};
                                chart.options.scales.x.ticks.color = textColor;
                                if (!chart.options.scales.x.grid) chart.options.scales.x.grid = {};
                                chart.options.scales.x.grid.color = gridColor;
                            }
                            if (chart.options.scales.y) {
                                if (!chart.options.scales.y.ticks) chart.options.scales.y.ticks = {};
                                chart.options.scales.y.ticks.color = textColor;
                                if (!chart.options.scales.y.grid) chart.options.scales.y.grid = {};
                                chart.options.scales.y.grid.color = gridColor;
                            }
                        }
                        
                        // Update legend color
                        if (chart.options.plugins && chart.options.plugins.legend && chart.options.plugins.legend.labels) {
                            chart.options.plugins.legend.labels.color = textColor;
                        }
                        
                        chart.update();
                    }
                }
            }
        </script>
    </head>
    <body class="font-sans antialiased bg-gray-100 dark:bg-gray-950 text-gray-900 dark:text-gray-100 transition-colors duration-300">
        
        <!-- Background Effects -->
        <div class="fixed inset-0 z-[-1] overflow-hidden">
            <div class="absolute top-0 -left-4 w-72 h-72 bg-purple-300 dark:bg-purple-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob"></div>
            <div class="absolute top-0 -right-4 w-72 h-72 bg-yellow-300 dark:bg-yellow-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-2000"></div>
            <div class="absolute -bottom-8 left-20 w-72 h-72 bg-pink-300 dark:bg-pink-900 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-4000"></div>
            <div class="absolute inset-0 bg-white/40 dark:bg-black/40 backdrop-blur-[100px]"></div>
        </div>

        <div class="min-h-screen relative z-10">
            <livewire:layout.navigation />

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white/40 dark:bg-gray-900/40 backdrop-blur-lg border-b border-white/20 dark:border-gray-700/30 sticky top-16 z-40">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        @auth
            <livewire:chatbot-widget />
        @endauth
    </body>
</html>

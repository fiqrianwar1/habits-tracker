<x-app-layout>


    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900 dark:text-white leading-tight tracking-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="pt-8 pb-24 z-10 relative">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Top Section: Stats & Charts -->
            <div class="animate-fade-in-up opacity-0" style="animation-delay: 0.1s;">
                <livewire:dashboard-stats />
            </div>

            <!-- Middle Section: Pomodoro Focus Timer -->
            <div class="animate-fade-in-up opacity-0" style="animation-delay: 0.15s;">
                <livewire:pomodoro-timer />
            </div>

            <!-- Bottom Section: Form & History -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">
                
                <!-- Left: Form -->
                <div class="flex flex-col h-full animate-fade-in-up opacity-0" style="animation-delay: 0.2s;">
                    <livewire:activity-form />
                </div>

                <!-- Right: History -->
                <div class="h-full flex flex-col animate-fade-in-up opacity-0" style="animation-delay: 0.3s;">
                    <livewire:activity-history />
                </div>

            </div>

        </div>
    </div>
</x-app-layout>

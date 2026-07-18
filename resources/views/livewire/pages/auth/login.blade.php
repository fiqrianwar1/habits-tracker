<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    <!-- Header Text -->
    <div class="mb-8 text-center">
        <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Welcome Back</h2>
        <p class="text-sm text-gray-600 dark:text-gray-400">Silakan login untuk melanjutkan ke dashboard.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="login" class="space-y-6">
        <!-- Email Address -->
        <div class="relative group">
            <div class="absolute -inset-0.5 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-2xl blur opacity-0 group-hover:opacity-30 transition duration-500"></div>
            <div class="relative">
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email Address</label>
                <input wire:model="form.email" id="email" type="email" name="email" required autofocus autocomplete="username"
                    class="block w-full rounded-2xl bg-white/50 dark:bg-black/20 border-gray-200 dark:border-white/10 shadow-inner focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all text-gray-900 dark:text-white px-4 py-3" />
                <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
            </div>
        </div>

        <!-- Password -->
        <div class="relative group mt-4">
            <div class="absolute -inset-0.5 bg-gradient-to-r from-purple-500 to-pink-500 rounded-2xl blur opacity-0 group-hover:opacity-30 transition duration-500"></div>
            <div class="relative">
                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Password</label>
                <input wire:model="form.password" id="password" type="password" name="password" required autocomplete="current-password"
                    class="block w-full rounded-2xl bg-white/50 dark:bg-black/20 border-gray-200 dark:border-white/10 shadow-inner focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all text-gray-900 dark:text-white px-4 py-3" />
                <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
            </div>
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between mt-4">
            <label for="remember" class="inline-flex items-center cursor-pointer">
                <input wire:model="form.remember" id="remember" type="checkbox" class="rounded dark:bg-black/40 border-gray-300 dark:border-gray-700 text-indigo-500 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 focus:ring-offset-0 transition-colors w-5 h-5" name="remember">
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400 font-medium">Remember me</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm font-bold text-transparent bg-clip-text bg-gradient-to-r from-indigo-500 to-purple-500 hover:from-indigo-400 hover:to-purple-400 transition-all" href="{{ route('password.request') }}" wire:navigate>
                    Forgot password?
                </a>
            @endif
        </div>

        <!-- Login Button -->
        <div class="mt-6">
            <button type="submit" class="w-full relative group flex items-center justify-center px-8 py-4 font-bold text-white transition-all duration-300 bg-gray-900 dark:bg-white dark:text-gray-900 rounded-2xl hover:scale-[1.02] active:scale-95 shadow-lg overflow-hidden">
                <div class="absolute inset-0 w-full h-full bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <span class="relative z-10 flex items-center gap-2 group-hover:text-white transition-colors">
                    Log in
                    <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                </span>
            </button>
        </div>
        
        <!-- Register Link -->
        <div class="mt-6 text-center text-sm text-gray-600 dark:text-gray-400">
            Belum punya akun? 
            <a href="{{ route('register') }}" wire:navigate class="font-bold text-indigo-500 hover:text-indigo-400 transition-colors">Daftar sekarang</a>
        </div>
    </form>
</div>

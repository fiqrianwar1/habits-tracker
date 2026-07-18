<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered($user = User::create($validated)));

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    <!-- Header Text -->
    <div class="mb-8 text-center">
        <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Create Account</h2>
        <p class="text-sm text-gray-600 dark:text-gray-400">Daftar sekarang dan mulai catat kebiasaan lo.</p>
    </div>

    <form wire:submit="register" class="space-y-5">
        <!-- Name -->
        <div class="relative group">
            <div class="absolute -inset-0.5 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-2xl blur opacity-0 group-hover:opacity-30 transition duration-500"></div>
            <div class="relative">
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Full Name</label>
                <input wire:model="name" id="name" type="text" name="name" required autofocus autocomplete="name"
                    class="block w-full rounded-2xl bg-white/50 dark:bg-black/20 border-gray-200 dark:border-white/10 shadow-inner focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-gray-900 dark:text-white px-4 py-3" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>
        </div>

        <!-- Email Address -->
        <div class="relative group">
            <div class="absolute -inset-0.5 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-2xl blur opacity-0 group-hover:opacity-30 transition duration-500"></div>
            <div class="relative">
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email Address</label>
                <input wire:model="email" id="email" type="email" name="email" required autocomplete="username"
                    class="block w-full rounded-2xl bg-white/50 dark:bg-black/20 border-gray-200 dark:border-white/10 shadow-inner focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all text-gray-900 dark:text-white px-4 py-3" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>
        </div>

        <!-- Password -->
        <div class="relative group">
            <div class="absolute -inset-0.5 bg-gradient-to-r from-purple-500 to-pink-500 rounded-2xl blur opacity-0 group-hover:opacity-30 transition duration-500"></div>
            <div class="relative">
                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Password</label>
                <input wire:model="password" id="password" type="password" name="password" required autocomplete="new-password"
                    class="block w-full rounded-2xl bg-white/50 dark:bg-black/20 border-gray-200 dark:border-white/10 shadow-inner focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all text-gray-900 dark:text-white px-4 py-3" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>
        </div>

        <!-- Confirm Password -->
        <div class="relative group">
            <div class="absolute -inset-0.5 bg-gradient-to-r from-pink-500 to-rose-500 rounded-2xl blur opacity-0 group-hover:opacity-30 transition duration-500"></div>
            <div class="relative">
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Confirm Password</label>
                <input wire:model="password_confirmation" id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                    class="block w-full rounded-2xl bg-white/50 dark:bg-black/20 border-gray-200 dark:border-white/10 shadow-inner focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition-all text-gray-900 dark:text-white px-4 py-3" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>
        </div>

        <!-- Register Button -->
        <div class="pt-4">
            <button type="submit" class="w-full relative group flex items-center justify-center px-8 py-4 font-bold text-white transition-all duration-300 bg-gray-900 dark:bg-white dark:text-gray-900 rounded-2xl hover:scale-[1.02] active:scale-95 shadow-lg overflow-hidden">
                <div class="absolute inset-0 w-full h-full bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <span class="relative z-10 flex items-center gap-2 group-hover:text-white transition-colors">
                    Register Now
                    <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                </span>
            </button>
        </div>

        <!-- Login Link -->
        <div class="mt-6 text-center text-sm text-gray-600 dark:text-gray-400">
            Sudah punya akun? 
            <a href="{{ route('login') }}" wire:navigate class="font-bold text-indigo-500 hover:text-indigo-400 transition-colors">Login di sini</a>
        </div>
    </form>
</div>

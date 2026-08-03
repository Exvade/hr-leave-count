@extends('layouts.app')

@section('title', 'Akses Sistem')

@section('content')
<div class="min-h-screen flex bg-gray-50 dark:bg-gray-900 selection:bg-brand-light selection:text-white">
    <!-- Left: Decorative background -->
    <div class="hidden lg:flex lg:w-1/2 relative bg-brand-dark overflow-hidden items-center justify-center">
        <!-- Abstract background shapes -->
        <div class="absolute inset-0 bg-gradient-to-br from-brand-dark via-[#1e293b] to-black opacity-90"></div>
        <div class="absolute -top-32 -left-32 w-96 h-96 rounded-full bg-brand-light opacity-30 blur-[100px]"></div>
        <div class="absolute bottom-12 right-12 w-80 h-80 rounded-full bg-blue-500 opacity-20 blur-[80px]"></div>
        <div class="absolute top-1/2 left-1/4 w-64 h-64 rounded-full bg-purple-500 opacity-10 blur-[80px]"></div>
        
        <div class="relative z-10 text-center px-12 max-w-2xl">
            <img src="{{ asset('assets/images/logo/logo-landscape.webp') }}" alt="MWT Logo" class="h-20 mx-auto mb-10 drop-shadow-2xl filter brightness-0 invert">
            <h1 class="text-4xl font-extrabold text-white mb-6 tracking-tight leading-tight">
                Sistem Penghitung Cuti
            </h1>
            <p class="text-lg text-gray-300 font-medium leading-relaxed">
                Platform internal canggih untuk mengelola data karyawan dan alokasi cuti tahunan dengan mudah, cepat, dan akurat.
            </p>
        </div>
    </div>

    <!-- Right: Login Form -->
    <div class="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-12 lg:p-24 relative overflow-hidden">
        <!-- Decorative blob for mobile only -->
        <div class="absolute -top-32 -right-32 w-96 h-96 rounded-full bg-brand-light opacity-10 blur-[80px] lg:hidden"></div>
        
        <div class="w-full max-w-md relative z-10">
            <!-- Mobile Logo -->
            <div class="lg:hidden flex justify-center mb-10">
                <img src="{{ asset('assets/images/logo/logo-square.webp') }}" alt="MWT Logo" class="h-20 w-auto drop-shadow-md">
            </div>

            <div class="text-center lg:text-left mb-10">
                <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                    Selamat Datang
                </h2>
                <p class="mt-3 text-base text-gray-500 dark:text-gray-400">
                    Silakan masukkan kata sandi akses sistem untuk melanjutkan.
                </p>
            </div>

            <!-- Glassmorphism Card -->
            <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl border border-white/20 dark:border-gray-700/50 shadow-2xl rounded-2xl p-8 sm:p-10 transform transition-all duration-300 hover:shadow-brand-light/10">
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-brand-light to-blue-500 rounded-t-2xl"></div>
                
                <form class="space-y-6" action="{{ route('login.post') }}" method="POST">
                    @csrf

                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Kata Sandi Akses
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-brand-light transition-colors duration-300">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            </div>
                            <input id="password" name="password" type="password" required 
                                class="w-full pl-11 pr-4 py-3.5 bg-gray-50/50 dark:bg-gray-900/50 border border-gray-200 dark:border-gray-700 focus:ring-2 focus:ring-brand-light focus:border-transparent rounded-xl transition-all duration-300 outline-none shadow-sm dark:text-white" 
                                placeholder="••••••••" autofocus />
                        </div>
                        @error('password')
                            <p class="mt-3 text-sm text-red-500 font-medium flex items-center gap-1.5 animate-pulse" id="password-error">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full flex justify-center items-center gap-2 bg-brand-light hover:bg-brand-dark text-white text-base font-semibold py-3.5 rounded-xl shadow-lg shadow-brand-light/30 hover:shadow-brand-light/50 transform hover:-translate-y-1 transition-all duration-300">
                            Masuk ke Sistem
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </button>
                    </div>
                </form>
            </div>
            
            <p class="text-center text-sm text-gray-500 dark:text-gray-500 mt-10 font-medium">
                &copy; {{ date('Y') }} MWT. All rights reserved.
            </p>
        </div>
    </div>
</div>
@endsection

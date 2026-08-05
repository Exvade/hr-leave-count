<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.name', 'MWT Starter Kit'))</title>
    
    <link rel="icon" type="image/webp" href="{{ asset('assets/images/logo/logo-square.webp') }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Prevent Dark Mode FOUC (Flash of Unstyled Content) -->
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        :root {
            --font-sans: 'Inter', sans-serif;
            --font-heading: 'Outfit', sans-serif;
        }
        body { font-family: var(--font-sans); }
        h1, h2, h3, h4, h5, h6, .font-heading { font-family: var(--font-heading); }
    </style>
</head>
<body x-data="{ 
        darkMode: localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)
      }" 
      x-init="$watch('darkMode', val => { localStorage.setItem('theme', val ? 'dark' : 'light'); if(val) { document.documentElement.classList.add('dark'); } else { document.documentElement.classList.remove('dark'); } })" 
      :class="{ 'dark': darkMode }"
      class="bg-gray-50 dark:bg-gray-900 font-sans text-brand-text dark:text-gray-100 flex flex-col min-h-screen transition-colors duration-500 selection:bg-brand-light selection:text-white relative overflow-x-hidden">
    
    <!-- Background Animated Orbs -->
    <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden">
        <div class="absolute -top-32 -left-32 w-96 h-96 rounded-full bg-brand-light opacity-20 blur-[100px] animate-pulse"></div>
        <div class="absolute top-1/2 right-0 w-80 h-80 rounded-full bg-blue-500 opacity-10 blur-[120px] mix-blend-multiply dark:mix-blend-screen"></div>
        <div class="absolute -bottom-32 left-1/4 w-96 h-96 rounded-full bg-purple-500 opacity-10 blur-[100px]"></div>
    </div>

    <!-- Global SweetAlert2 Toast Mixin -->
    <script>
        window.SwalToast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        });

        window.addEventListener('notify', event => {
            let detail = event.detail;
            if (Array.isArray(detail) && detail.length > 0) { detail = detail[0]; }
            window.SwalToast.fire({
                icon: detail.type || 'success',
                title: detail.title || 'Pemberitahuan',
                text: detail.message || ''
            });
        });
    </script>

    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                window.SwalToast.fire({ icon: 'success', title: 'Berhasil!', text: '{{ session('success') }}' });
            });
        </script>
    @endif

    @if(session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({ icon: 'error', title: 'Terjadi Kesalahan!', text: '{{ session('error') }}', confirmButtonColor: '#14532d' });
            });
        </script>
    @endif

    <!-- Floating Navbar -->
    <header class="sticky top-0 z-50 w-full backdrop-blur-xl bg-white/70 dark:bg-gray-900/70 border-b border-gray-200/50 dark:border-gray-800/50 shadow-sm transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex items-center gap-4">
                    <img class="h-10 w-auto drop-shadow-sm dark:brightness-0 dark:invert transition-all duration-300 hover:scale-105" src="{{ asset('assets/images/logo/logo-landscape.webp') }}" alt="MWT Logo">
                    <span class="hidden md:block h-6 w-px bg-gray-300 dark:bg-gray-700 mx-2"></span>
                    <span class="hidden md:block font-bold text-lg bg-clip-text text-transparent bg-gradient-to-r from-brand-dark to-brand-light dark:from-white dark:to-gray-400">
                        Penghitung Cuti
                    </span>
                </div>
                
                <div class="flex items-center gap-4 sm:gap-6">
                    <div class="text-sm font-semibold text-gray-500 dark:text-gray-400 hidden lg:block bg-gray-100/50 dark:bg-gray-800/50 px-4 py-1.5 rounded-full backdrop-blur-sm border border-gray-200/50 dark:border-gray-700/50">
                        {{ now()->format('l, d F Y') }}
                    </div>
                    
                    <!-- Dark Mode Toggle -->
                    <button @click="darkMode = !darkMode" class="p-2.5 rounded-full bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 hover:text-brand-light dark:hover:text-white hover:bg-gray-200 dark:hover:bg-gray-700 transition-all duration-300 shadow-sm focus:outline-none focus:ring-2 focus:ring-brand-light">
                        <!-- Sun Icon (shows in dark mode) -->
                        <svg x-show="darkMode" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        <!-- Moon Icon (shows in light mode) -->
                        <svg x-show="!darkMode" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                    </button>

                    @if(Session::has('authenticated'))
                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-bold rounded-xl text-red-600 bg-red-50 hover:bg-red-100 dark:text-red-400 dark:bg-red-900/20 dark:hover:bg-red-900/40 transition-all duration-300 border border-transparent hover:border-red-200 dark:hover:border-red-800 shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            Keluar
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </header>

    <main class="flex-grow relative w-full py-10">
        @yield('content')
    </main>

</body>
</html>

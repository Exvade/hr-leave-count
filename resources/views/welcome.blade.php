@extends('layouts.app')

@section('title', 'MWT Starter Kit')

@section('content')
    <div class="min-h-screen flex flex-col bg-gray-50 dark:bg-gray-900">
        <!-- Navbar / Header -->
        <header
            class="w-full py-4 px-6 md:px-10 border-b border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 flex justify-between items-center shadow-sm">
            <div class="flex items-center gap-3">
                <img src="{{ asset('assets/images/logo/logo-landscape.webp') }}" alt="MWT Logo"
                    class="h-8 w-auto dark:hidden block">
                <img src="{{ asset('assets/images/logo/logo-landscape-light.webp') }}" alt="MWT Logo"
                    class="h-8 w-auto hidden dark:block">
                <div class="h-6 w-px bg-gray-300 dark:bg-gray-700 mx-2 hidden sm:block"></div>
                <span class="font-bold text-gray-700 dark:text-gray-300 hidden sm:block">Starter Kit</span>
            </div>

            <!-- Dark Mode Toggle -->
            <button @click="darkMode = !darkMode"
                class="p-2 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors border border-gray-200 dark:border-gray-700">
                <svg x-show="!darkMode" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                </svg>
                <svg x-show="darkMode" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z">
                    </path>
                </svg>
            </button>
        </header>

        <!-- Main Content -->
        <main class="flex-grow max-w-6xl mx-auto w-full px-6 py-10 flex flex-col gap-10">

            <!-- Header Title -->
            <section class="border-b border-gray-200 dark:border-gray-800 pb-8">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-3">Selamat Datang di MWT Starter
                    Kit</h2>
                <p class="text-gray-600 dark:text-gray-400 text-lg">Kerangka proyek standar PT Mada Wikri Tunggal.
                    Dilengkapi dengan Tailwind v4, komponen bawaan, dan panduan dasar.</p>
            </section>

            <!-- Quick Start Grid -->
            <section class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <x-card class="flex flex-col h-full">
                    <div class="flex items-center gap-3 mb-4">
                        <div
                            class="w-10 h-10 bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg flex items-center justify-center text-gray-700 dark:text-gray-300">
                            <span class="font-bold">1</span>
                        </div>
                        <h4 class="font-bold text-lg">Konfigurasi Database</h4>
                    </div>
                    <p class="text-gray-600 dark:text-gray-400 text-sm flex-grow">Atur konfigurasi pada file
                        <code>.env</code> untuk menyambungkan aplikasi dengan database lokal Anda sebelum memulai
                        pengembangan.</p>
                </x-card>

                <x-card class="flex flex-col h-full">
                    <div class="flex items-center gap-3 mb-4">
                        <div
                            class="w-10 h-10 bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg flex items-center justify-center text-gray-700 dark:text-gray-300">
                            <span class="font-bold">2</span>
                        </div>
                        <h4 class="font-bold text-lg">Jalankan Migrasi</h4>
                    </div>
                    <p class="text-gray-600 dark:text-gray-400 text-sm flex-grow">Gunakan perintah <code>php artisan
                            migrate</code>. Sistem ini telah disematkan fitur audit trail otomatis untuk pencatatan
                        aktivitas pengguna.</p>
                </x-card>

                <x-card class="flex flex-col h-full">
                    <div class="flex items-center gap-3 mb-4">
                        <div
                            class="w-10 h-10 bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg flex items-center justify-center text-gray-700 dark:text-gray-300">
                            <span class="font-bold">3</span>
                        </div>
                        <h4 class="font-bold text-lg">Pelajari Komponen</h4>
                    </div>
                    <p class="text-gray-600 dark:text-gray-400 text-sm flex-grow">Gunakan komponen UI standar MWT yang telah
                        disediakan agar desain aplikasi selaras dengan identitas perusahaan.</p>
                </x-card>
            </section>

            <!-- Components Showcase -->
            <section>
                <h3
                    class="text-xl font-bold mb-6 text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-800 pb-2">
                    Komponen Dasar (UI)</h3>

                <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl p-6 lg:p-8">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">

                        <!-- Buttons & Inputs -->
                        <div class="space-y-6">
                            <div>
                                <h4 class="text-sm font-semibold text-gray-500 mb-3 uppercase tracking-wide">Buttons</h4>
                                <div class="flex flex-wrap gap-3">
                                    <x-button variant="primary">Simpan</x-button>
                                    <x-button variant="secondary">Batal</x-button>
                                    <x-button variant="outline">Kembali</x-button>
                                    <x-button variant="danger">Hapus</x-button>
                                </div>
                            </div>

                            <div>
                                <h4 class="text-sm font-semibold text-gray-500 mb-3 uppercase tracking-wide">Input Kolom
                                </h4>
                                <div class="space-y-4 max-w-sm">
                                    <div>
                                        <label class="block text-sm font-medium mb-1">Nama Lengkap</label>
                                        <x-input placeholder="Contoh: Budi Santoso" />
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium mb-1 text-red-600 dark:text-red-400">Email
                                            Perusahaan</label>
                                        <x-input placeholder="email@mw-tunggal.co.id" :error="true" />
                                        <span class="text-xs text-red-600 dark:text-red-400 mt-1 block">Domain email tidak
                                            valid.</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Alerts -->
                        <div class="space-y-6">
                            <div>
                                <h4 class="text-sm font-semibold text-gray-500 mb-3 uppercase tracking-wide">Alert Boxes
                                </h4>
                                <div class="space-y-3">
                                    <x-alert type="success" title="Sukses">Data berhasil ditambahkan ke database.</x-alert>
                                    <x-alert type="danger" title="Peringatan">Terjadi kesalahan pada sistem
                                        koneksi.</x-alert>
                                </div>
                            </div>

                            <div>
                                <h4 class="text-sm font-semibold text-gray-500 mb-3 uppercase tracking-wide">Notifikasi
                                    Global (SweetAlert2)</h4>
                                <x-button variant="primary"
                                    onclick="Swal.fire({icon: 'success', title: 'Berhasil', text: 'SweetAlert2 telah terintegrasi di sistem!', confirmButtonColor: '#14532d'})">
                                    Test Notifikasi
                                </x-button>
                            </div>
                        </div>

                    </div>
                </div>
            </section>

        </main>

        <!-- Footer -->
        <footer
            class="py-6 text-center text-sm text-gray-500 bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-800">
            &copy; {{ date('Y') }} PT Mada Wikri Tunggal. All Rights Reserved.
        </footer>
    </div>
@endsection

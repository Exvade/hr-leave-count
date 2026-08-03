<x-layout>
    <div class="min-h-[80vh] flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <!-- Logo MWT -->
            <div class="flex justify-center mb-6">
                <img src="{{ asset('assets/images/logo/logo-square.webp') }}" alt="MWT Logo" class="h-16 w-auto">
            </div>
            
            <h2 class="mt-2 text-center text-3xl font-extrabold text-gray-900 dark:text-white">
                Akses Sistem
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600 dark:text-gray-400">
                Silakan masukkan kata sandi untuk melanjutkan.
            </p>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <x-card>
                <form class="space-y-6" action="{{ route('login.post') }}" method="POST">
                    @csrf

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Kata Sandi
                        </label>
                        <div class="mt-1 relative">
                            <x-input id="password" name="password" type="password" required class="w-full pl-10" placeholder="••••••••" />
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            </div>
                        </div>
                        @error('password')
                            <p class="mt-2 text-sm text-red-600" id="password-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <x-button type="submit" variant="primary" class="w-full justify-center text-lg">
                            Masuk
                        </x-button>
                    </div>
                </form>
            </x-card>
        </div>
    </div>
</x-layout>

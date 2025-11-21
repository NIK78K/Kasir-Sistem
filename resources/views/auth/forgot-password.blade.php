<x-guest-layout>
        <div class="max-w-md w-full">
            <!-- Card Container -->
            <div class="bg-white rounded-xl lg:rounded-2xl shadow-xl overflow-hidden">
                <!-- Header Section -->
                <div class="bg-gradient-to-r from-slate-800 to-slate-900 px-6 py-8 lg:px-8 lg:py-10 text-center">
                    <div class="inline-flex items-center justify-center w-14 h-14 lg:w-16 lg:h-16 bg-white rounded-full mb-3 lg:mb-4 shadow-lg">
                        <svg class="w-7 h-7 lg:w-8 lg:h-8 text-slate-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                        </svg>
                    </div>
                    <h2 class="text-2xl lg:text-3xl font-bold text-white mb-2">Lupa Password?</h2>
                    <p class="text-slate-100 text-xs lg:text-sm">Jangan khawatir, kami akan bantu Anda</p>
                </div>

                <!-- Content Section -->
                <div class="px-6 py-6 lg:px-8 lg:py-8">
                    <div class="mb-5 lg:mb-6">
                        <div class="flex items-start space-x-2 lg:space-x-3 p-3 lg:p-4 bg-blue-50 rounded-lg border border-blue-100">
                            <svg class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                            <p class="text-sm text-gray-700 leading-relaxed">
                                Masukkan email Anda dan kami akan mengirimkan link reset password untuk membuat password baru.
                            </p>
                        </div>
                    </div>

                    <!-- Session Status -->
                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    <form method="POST" action="{{ route('password.email') }}" class="space-y-5 lg:space-y-6">
                        @csrf

                        <!-- Email Address -->
                        <div>
                            <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                                Email Address
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <input 
                                    id="email" 
                                    type="email" 
                                    name="email" 
                                    value="{{ old('email') }}"
                                    required 
                                    autofocus
                                    class="block w-full pl-10 pr-3 py-2.5 lg:py-3 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:border-transparent transition duration-150 ease-in-out text-xs lg:text-sm"
                                    placeholder="nama@email.com"
                                />
                            </div>
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Submit Button -->
                        <div>
                            <button
                                type="submit"
                                class="w-full flex justify-center items-center py-2.5 lg:py-3 px-4 border border-transparent rounded-lg shadow-sm text-xs lg:text-sm font-semibold text-white bg-slate-800 hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500 transition duration-150 ease-in-out transform hover:scale-[1.02]"
                            >
                                <svg class="w-4 h-4 lg:w-5 lg:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                Kirim Link Reset Password
                            </button>
                        </div>

                        <!-- Back to Login -->
                        <div class="text-center pt-4 border-t border-gray-100">
                            <a href="{{ route('login') }}" class="inline-flex items-center text-sm font-medium text-slate-700 hover:text-slate-900 transition duration-150 ease-in-out">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                Kembali ke Login
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Footer Info -->
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-500">
                    Sistem Kasir
                    <a href="#" class="font-medium text-slate-700 hover:text-slate-900">AMKAS</a>
                </p>
            </div>
        </div>
</x-guest-layout>
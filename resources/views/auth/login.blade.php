<x-guest-layout>
    <div class="min-h-screen bg-gradient-to-br from-slate-700 to-slate-800 flex items-center justify-center px-4 py-10">
        <!-- Main Container -->
        <div class="flex w-full max-w-4xl bg-white rounded-3xl shadow-2xl overflow-hidden">
            <!-- Left Side - Brand Section -->
            <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-slate-800 to-slate-900 flex-col items-center justify-center px-12 py-16 relative overflow-hidden">
                <!-- Decorative Background Elements -->
                <div class="absolute -bottom-20 -left-20 w-72 h-72 bg-white/5 rounded-full"></div>
                <div class="absolute top-10 right-10 w-32 h-32 bg-white/5 rounded-full"></div>
                <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-white/5 rounded-full"></div>

                <!-- Logo Circle -->
                <div class="w-32 h-32 bg-white rounded-full flex items-center justify-center mb-8 relative z-10 shadow-2xl">
                    <svg class="w-16 h-16 fill-slate-800" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                    </svg>
                </div>
                
                <!-- Login Title -->
                <div class="text-white text-6xl font-bold uppercase tracking-[0.3em] relative z-10 mb-4">LOGIN</div>
                
                <!-- Subtitle -->
                <p class="text-slate-300 text-base relative z-10 text-center">Selamat datang kembali di AMKAS</p>
            </div>

            <!-- Right Side - Form Section -->
            <div class="w-full lg:w-1/2 bg-white flex items-center justify-center px-8 py-12 lg:px-12 lg:py-16">
                <div class="w-full max-w-md">
                    <!-- Mobile Logo (visible only on mobile) -->
                    <div class="lg:hidden flex flex-col items-center mb-8">
                        <div class="w-20 h-20 bg-gradient-to-br from-slate-700 to-slate-900 rounded-full flex items-center justify-center mb-4 shadow-xl">
                            <svg class="w-10 h-10 fill-white" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-slate-800 tracking-wider">LOGIN</h2>
                    </div>

                    <!-- Tab Header -->
                    <div class="flex justify-end mb-10 gap-2">
                        <button class="px-4 py-2 text-slate-400 text-xs font-semibold uppercase cursor-default transition-all">
                            ←
                        </button>
                        <button class="px-6 py-2 bg-slate-800 text-white text-xs font-semibold uppercase border-none rounded-lg cursor-default shadow-md">
                            LOGIN
                        </button>
                    </div>

                    <!-- Session Status -->
                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    <form method="POST" action="{{ route('login') }}" class="space-y-5">
                        @csrf

                        <!-- Email Address -->
                        <div class="relative">
                            <div class="relative flex items-stretch border-2 border-slate-200 rounded-xl transition-all duration-300 focus-within:border-slate-700 focus-within:shadow-lg overflow-hidden">
                                <div class="w-14 flex items-center justify-center bg-slate-800 flex-shrink-0">
                                    <svg class="w-5 h-5 fill-white" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                                    </svg>
                                </div>
                                <input
                                    id="email"
                                    class="flex-1 border-none px-5 py-4 text-sm outline-none bg-transparent placeholder:text-slate-400 placeholder:uppercase placeholder:text-xs placeholder:tracking-wider focus:ring-0"
                                    type="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    required
                                    autofocus
                                    autocomplete="username"
                                    placeholder="EMAIL"
                                />
                            </div>
                            <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs text-red-600" />
                        </div>

                        <!-- Password -->
                        <div class="relative">
                            <div class="relative flex items-stretch border-2 border-slate-200 rounded-xl transition-all duration-300 focus-within:border-slate-700 focus-within:shadow-lg overflow-hidden">
                                <div class="w-14 flex items-center justify-center bg-slate-800 flex-shrink-0">
                                    <svg class="w-5 h-5 fill-white" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/>
                                    </svg>
                                </div>
                                <input
                                    id="password"
                                    class="flex-1 border-none px-5 py-4 text-sm outline-none bg-transparent placeholder:text-slate-400 placeholder:uppercase placeholder:text-xs placeholder:tracking-wider focus:ring-0"
                                    type="password"
                                    name="password"
                                    required
                                    autocomplete="current-password"
                                    placeholder="PASSWORD"
                                />
                            </div>
                            <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs text-red-600" />
                        </div>

                        <!-- Remember Me & Terms -->
                        <div class="flex items-start pt-2">
                            <input 
                                id="remember_me" 
                                type="checkbox" 
                                name="remember" 
                                class="w-4 h-4 mt-0.5 mr-3 cursor-pointer accent-slate-800 rounded border-slate-300 focus:ring-slate-700"
                            >
                            <label for="remember_me" class="text-xs text-slate-600 cursor-pointer select-none leading-relaxed">
                                I have accepted the terms and conditions
                            </label>
                        </div>

                        <!-- Login Button -->
                        <button 
                            type="submit" 
                            class="w-full py-4 bg-slate-800 text-white border-none rounded-xl text-sm font-bold uppercase tracking-[0.2em] cursor-pointer transition-all duration-300 hover:bg-slate-700 hover:shadow-xl hover:shadow-slate-800/30 active:translate-y-0.5 mt-6"
                        >
                            LOGIN
                        </button>

                        <!-- Forgot Password -->
                        @if (Route::has('password.request'))
                            <div class="text-center mt-6">
                                <a 
                                    href="{{ route('password.request') }}" 
                                    class="text-slate-700 text-sm no-underline font-medium hover:text-slate-900 hover:underline transition-all duration-200"
                                >
                                    Forgot your password?
                                </a>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
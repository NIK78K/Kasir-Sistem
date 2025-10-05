<x-guest-layout>
    <style>
        .login-wrapper {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 24px;
            padding: 48px 40px;
            max-width: 440px;
            width: 100%;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: fadeIn 0.6s ease-out;
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 32px;
        }
        
        .login-logo {
            font-size: 36px;
            font-weight: 600;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 8px;
        }
        
        .login-subtitle {
            font-size: 16px;
            color: #666;
        }
        
        /* Override default input styles */
        input[type="email"],
        input[type="password"] {
            border: 2px solid #e0e0e0 !important;
            border-radius: 12px !important;
            padding: 12px 16px !important;
            font-size: 15px !important;
            transition: all 0.3s ease !important;
        }
        
        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: #667eea !important;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1) !important;
            outline: none !important;
        }
        
        input[type="checkbox"] {
            width: 18px !important;
            height: 18px !important;
            border-radius: 6px !important;
            cursor: pointer !important;
        }
        
        label {
            font-weight: 500 !important;
            color: #333 !important;
        }
        
        /* Button styling */
        button[type="submit"] {
            width: 100%;
            padding: 14px !important;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            border: none !important;
            border-radius: 12px !important;
            font-size: 16px !important;
            font-weight: 600 !important;
            transition: all 0.3s ease !important;
            margin-top: 8px;
        }
        
        button[type="submit"]:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(102, 126, 234, 0.4) !important;
        }
        
        /* Links */
        a {
            color: #667eea !important;
            font-weight: 500 !important;
        }
        
        a:hover {
            color: #764ba2 !important;
        }
        
        /* Error messages */
        .text-sm.text-red-600 {
            color: #ef4444 !important;
            font-size: 13px !important;
        }
        
        @media (max-width: 640px) {
            .login-card {
                padding: 32px 24px;
            }
            
            .login-logo {
                font-size: 28px;
            }
        }
    </style>

    <div class="login-wrapper">
        <div class="login-card">
            <div class="login-header">
                <div class="login-logo">AMKas</div>
                <div class="login-subtitle">Silakan masuk ke akun Anda</div>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email Address -->
                <div>
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <x-input-label for="password" :value="__('Password')" />

                    <x-text-input id="password" class="block mt-1 w-full"
                                    type="password"
                                    name="password"
                                    required autocomplete="current-password" />

                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Remember Me -->
                <div class="block mt-4">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                        <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                    </label>
                </div>

                <div class="flex items-center justify-end mt-4">
                    @if (Route::has('password.request'))
                        <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                            {{ __('Forgot your password?') }}
                        </a>
                    @endif

                    <x-primary-button class="ms-3">
                        {{ __('Log in') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
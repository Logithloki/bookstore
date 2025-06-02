{{-- resources/views/auth/login.blade.php --}}
{{-- This file overrides the default Jetstream login view --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Book Hive</title>
    {{-- Include Tailwind CSS - Ensure this is correctly set up in your Laravel project --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body class="bg-gray-100 font-sans antialiased min-h-screen flex flex-col">
    <!-- Navbar -->
    {{-- Pass 'login' as the currentPage --}}
    <x-navbar currentPage="login" />

    <!-- Main Section -->
    <main class="relative flex-grow flex items-center justify-center py-16 px-4 sm:px-6 lg:px-8">
        <!-- Background Image with Parallax-like Effect -->
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('img/login.jpg') }}" alt="Bookshelf" 
                class="w-full h-full object-cover ">
            <div class="absolute inset-0 bg-gradient-to-br from-black/80 via-black/75 to-black/80"></div>
        </div>

        <!-- Login Form Container -->
        <div class="relative z-10 w-full max-w-md animate-fade-in ">
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold text-white mb-2 transform hover:scale-105 transition-transform duration-300">Welcome Back</h1>
                <p class="text-gray-300 text-sm">Sign in to your BookHive account</p>
            </div>

            <!-- Form Card -->
            <div class="bg-black/40 rounded-2xl shadow-2xl p-8 border border-white/10">
                @if ($errors->any())
                    <div class="mb-6 bg-red-500/90 text-white px-4 py-3 rounded-lg transform transition-all duration-300 hover:scale-[1.02]">
                        <ul class="list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <!-- Email Field -->
                    <div class="space-y-2">
                        <label for="email" class="block text-sm font-medium text-gray-200">Email Address</label>
                        <div class="relative">
                            <input type="email" id="email" name="email" required
                                class="w-full px-4 py-3 bg-black/30 border border-gray-600/50 rounded-lg text-white placeholder-gray-400
                                    focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent
                                    transition-all duration-300 hover:bg-black/40"
                                value="{{ old('email') }}"
                                placeholder="Enter your email">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                <i class="fa fa-envelope text-gray-400"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Password Field -->
                    <div class="space-y-2">
                        <label for="password" class="block text-sm font-medium text-gray-200">Password</label>
                        <div class="relative">
                            <input type="password" id="password" name="password" required
                                class="w-full px-4 py-3 bg-black/30 border border-gray-600/50 rounded-lg text-white placeholder-gray-400
                                    focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent
                                    transition-all duration-300 hover:bg-black/40"
                                placeholder="Enter your password">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                <i class="fa fa-lock text-gray-400"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between">
                        <label class="flex items-center">
                            <input type="checkbox" name="remember" class="rounded border-gray-300 text-orange-500 
                                focus:ring-orange-500 transition-all duration-300">
                            <span class="ml-2 text-sm text-gray-300">Remember me</span>
                        </label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" 
                                class="text-sm text-orange-400 hover:text-orange-300 transition-colors duration-300">
                                Forgot password?
                            </a>
                        @endif
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                        class="w-full bg-orange-500 text-white py-3 px-4 rounded-lg font-medium
                            transform transition-all duration-300 hover:bg-orange-600 hover:scale-[1.02]
                            focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 focus:ring-offset-gray-900
                            disabled:opacity-50 disabled:cursor-not-allowed">
                        Sign In
                    </button>
                </form>

                <!-- Register Link -->
                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-300">
                        Don't have an account?
                        <a href="{{ route('register') }}" 
                            class="font-medium text-orange-400 hover:text-orange-300 transition-colors duration-300">
                            Create one now
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <div class="mt-auto">
        <x-footer />
    </div>

</body>

</html>

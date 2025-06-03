<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email - Book Hive</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body class="bg-gray-100 font-sans">
    <!-- Navbar -->
    <x-navbar currentPage="account" />

    <div class="relative min-h-screen">
        <!-- Main Section -->
        <main class="container mx-auto py-16 px-6 relative z-10">
            <div class="max-w-lg mx-auto p-8 rounded-lg shadow-lg bg-white bg-opacity-90 relative">
                <div class="text-center mb-8">
                    <img src="{{ asset('img/logo.png') }}" alt="BookHive Logo" class="h-24 mx-auto">
                    <h1 class="text-3xl font-bold text-gray-800 mt-4">Verify Your Email</h1>
                </div>

                <div class="mb-6 text-gray-700">
                    <p>Before continuing, could you verify your email address by clicking on the link we just emailed to you? If you didn't receive the email, we will gladly send you another.</p>
                </div>

                @if (session('status') == 'verification-link-sent')
                    <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg text-green-700">
                        <i class="fa fa-check-circle mr-2"></i> A new verification link has been sent to the email address you provided in your profile settings.
                    </div>
                @endif

                <div class="space-y-4">
                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <button type="submit" class="w-full bg-orange-500 text-white px-6 py-3 rounded-lg shadow hover:bg-orange-600 focus:ring focus:ring-orange-300 transition duration-200">
                            <i class="fa fa-envelope mr-2"></i> Resend Verification Email
                        </button>
                    </form>

                    <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                        <a href="{{ route('profile.show') }}" class="text-blue-500 hover:underline">
                            <i class="fa fa-user mr-1"></i> Edit Profile
                        </a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-gray-600 hover:text-gray-900">
                                <i class="fa fa-sign-out mr-1"></i> Log Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Footer -->
    <x-footer />
</body>
</html>

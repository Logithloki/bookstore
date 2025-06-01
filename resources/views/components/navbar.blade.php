<!-- resources/views/components/navbar.blade.php -->
<header class="bg-white shadow-md">
    <div class="container mx-auto flex items-center justify-between py-4 px-6">
        <!-- Logo Section -->
        <div class="flex flex-col items-center">
            <img src="{{ asset('img/logo.jpg') }}" alt="Book Hive Logo" class="h-10">
            <span class="text-gray-800 font-bold text-lg mt-1">BOOK HIVE</span>
        </div>
        <!-- Navigation Links -->
        <nav class="flex space-x-6">
            <a href="{{ url('/homepage') }}"
                class="text-orange-500 font-medium @if ($currentPage === 'home') hidden @else hover:underline @endif">HOME</a>
            <a href="{{ url('/about-us') }}"
                class="text-orange-500 font-medium @if ($currentPage === 'about') hidden @else hover:underline @endif">ABOUT</a>
        </nav>
        <!-- Search Bar -->
        <div class="relative">
            <form action="{{ url('/search') }}" method="GET" class="flex items-center">
                <input type="text" name="query" placeholder="Search"
                    class="border rounded-full py-2 px-4 w-64 focus:outline-none focus:ring-2 focus:ring-orange-400">
                <button type="submit" class="absolute right-3 top-3">
                    <i class="fa fa-search" aria-hidden="true"></i>
                </button>
            </form>
        </div>
        <!-- Buttons Section -->
        <div class="flex items-center space-x-4">
            {{-- Check if user is logged in using Laravel's Auth facade --}}
            @auth
                <a href="{{ url('/account') }}"
                    class="bg-orange-500 text-white px-4 py-2 rounded-lg text-sm shadow hover:bg-orange-600 focus:ring focus:ring-orange-300 @if ($currentPage === 'account') hidden @endif">
                    Account
                </a>                <a href="{{ url('/cart') }}" class="text-gray-600 hover:text-orange-500">
                    <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                </a>
                <a href="{{ route('add-book') }}"
                    class="bg-orange-500 text-white px-4 py-2 rounded-lg text-sm shadow hover:bg-orange-600 focus:ring focus:ring-orange-300 @if ($currentPage === 'account') hidden @endif">
                    Add Book
                </a>
                {{-- Use a form for logout as per good practice, though a link can work too --}}
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="bg-red-500 text-white px-4 py-2 rounded-lg text-sm shadow hover:bg-red-600 focus:ring focus:ring-red-300">
                        Logout
                    </button>
                </form>
            @else
                <a href="{{ url('/login') }}"
                    class="bg-orange-500 text-white px-4 py-2 rounded-lg text-sm shadow hover:bg-orange-600 focus:ring focus:ring-orange-300 @if ($currentPage === 'login') hidden @endif">
                    Login
                </a>
                <a href="{{ url('/cart') }}" class="text-gray-600 hover:text-orange-500">
                    <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                </a>
                <a href="{{ url('/login') }}"
                    class="bg-orange-500 text-white px-4 py-2 rounded-lg text-sm shadow hover:bg-orange-600 focus:ring focus:ring-orange-300 @if ($currentPage === 'login') hidden @endif">
                    Add Book
                </a>
            @endauth
        </div>
    </div>
</header>
<!-- resources/views/components/footer.blade.php -->
<footer class="bg-gray-100 py-2 px-5">
    <div class="container  flex flex-col md:flex-row justify-evenly items-center">
        <!-- Left section: Logo and Name -->
        <div class="flex flex-col items-center mb-6 md:mb-0">
            <img src="{{ asset('img/logo.jpg') }}" alt="Book Hive Logo" class="w-16 h-16 mb-2">
            <h2 class="text-2xl font-bold text-customBlue">BOOK HIVE</h2> {{-- Note: customBlue class needs to be defined in your Tailwind config or CSS --}}
        </div>

        {{-- Check if user is logged in using Laravel's Auth facade --}}
        @auth
            <div class="text-center mb-6 md:mb-0 text-customBlue">
                <h3 class="text-xl font-semibold text-customBlue">NEWSLETTER</h3>
                <p class="text-sm font-light text-gray-700">SUBSCRIPTION</p>
                <form action="{{ url('/newsletter') }}" method="POST" class="mt-2">
                    @csrf {{-- Laravel CSRF token --}}
                    <input type="email" name="email" placeholder="Enter your email" required
                        class="border rounded-full py-2 px-4 w-64 focus:outline-none focus:ring-2 focus:ring-orange-400">
                    <button type="submit"
                        class="bg-gray-800 text-white py-2 px-6 mt-2 rounded-full hover:bg-gray-700">JOIN</button>
                </form>
                <p class="mt-4 text-sm text-gray-600 max-w-xl">Join our BookHive Newsletter and stay updated with the
                    latest book deals, exclusive offers, and community updates! Be the first to know about new arrivals,
                    special promotions, and upcoming book exchanges. Sign up today and never miss a great book again!
                </p>
            </div>
        @else
            <div class="text-center mb-6 md:mb-0 text-customBlue">
                <h3 class="text-xl font-semibold text-customBlue">NEWSLETTER</h3>
                <p class="text-sm font-light text-gray-700">SUBSCRIPTION</p>
                <button onclick="window.location.href='{{ url('/login') }}'"
                    class="bg-gray-800 text-white py-2 px-6 mt-2 rounded-full hover:bg-gray-700">JOIN</button>
                <p class="mt-4 text-sm text-gray-600 max-w-xl">Join our BookHive Newsletter and stay updated with the
                    latest book deals, exclusive offers, and community updates! Be the first to know about new arrivals,
                    special promotions and upcoming book exchanges. Sign up today and never miss a great book again!</p>
            </div>
        @endauth

        <!-- Right section: Social Media and Links -->
        <div class="flex flex-col items-center">
            <div class="flex space-x-4 mb-4">
                <a href="https://www.instagram.com" class="text-gray-800 hover:text-customBlue" target="_blank"><i
                        class="fa fa-instagram" aria-hidden="true"></i></a>
                <a href="https://www.twitter.com" class="text-gray-800 hover:text-customBlue" target="_blank"><i
                        class="fa fa-twitter" aria-hidden="true"></i></a>
                <a href="https://www.facebook.com" class="text-gray-800 hover:text-customBlue" target="_blank"><i
                        class="fa fa-facebook" aria-hidden="true"></i></a>
            </div>
            <ul class="text-center text-bases space-y-2 text-gray-600">
                <li><a href="{{ url('/homepage') }}" class="hover:text-customBlue">HOME</a></li>
                <li><a href="{{ url('/about-us') }}" class="hover:text-customBlue">ABOUT US</a></li>
                <li><a href="{{ url('/packages') }}" class="hover:text-customBlue">PACKAGES</a></li>
                {{-- Assuming package.php maps to /packages --}}
            </ul>
        </div>
    </div>

    <!-- Bottom section: Copyrights -->
    <div class="border-t border-gray-300 mt-6">
        <p class="text-center text-sm text-gray-600 py-4"> COPYRIGHTS 2025</p>
    </div>
</footer>
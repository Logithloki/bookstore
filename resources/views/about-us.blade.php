{{-- resources/views/about-us.blade.php --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Book Hive</title>
    {{-- Use Vite to include compiled CSS and JS --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body class="bg-gray-100 font-sans antialiased">
    <!-- Navbar -->
    <x-navbar currentPage="about" />

    <!-- Main Section -->
    <main class="container mx-auto py-16 px-6 animate-fade-in">
        <h1 class="text-4xl md:text-6xl font-bold text-orange-500 mb-8">About Us</h1>

        <section class="relative h-[500px] md:h-[600px] rounded-lg overflow-hidden shadow-lg animate-fade-in" style="animation-delay: 0.3s;">
            <img src="{{ asset('img/About.jpg') }}" alt="Library" class="absolute inset-0 w-full h-full object-cover">

            <!-- Textbox on top of the Image -->
            <div class="absolute inset-0 flex items-center justify-center md:justify-end p-6">
                <div class="bg-black bg-opacity-50 text-white p-6 md:p-10 rounded-lg shadow-xl max-w-lg md:mr-6 animate-fade-in" style="animation-delay: 0.6s;">
                    <p class="text-lg leading-relaxed font-medium mb-4">
                        Welcome to BookHive, your ultimate platform for discovering, sharing, and trading books! At BookHive, we connect book lovers,
                        making it easy to buy, sell, trade, or rent books while promoting sustainability through reuse. Our platform caters to all readers, from casual
                        enthusiasts to collectors, offering a wide range of books across genres. With a subscription model for sellers and renters, we provide a fair, transparent,
                        and user-friendly marketplace to keep books circulating and accessible.
                    </p>
                    <p class="text-lg leading-relaxed font-medium">
                        Join BookHive to be part of a thriving community where stories are shared, knowledge is passed on, and the love for books continues to grow!
                    </p>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <x-footer />

</body>

</html>

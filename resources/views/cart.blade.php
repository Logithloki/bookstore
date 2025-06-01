<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - Book Hive</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    @livewireStyles
</head>

<body class="bg-gray-100 font-sans antialiased min-h-screen flex flex-col">
    <!-- Navbar -->
    <x-navbar currentPage="cart" />

    <!-- Main Content -->
    <main class="relative flex-grow flex items-center justify-center py-16 px-4 sm:px-6 lg:px-8">

        <div class="relative z-10 w-full max-w-4xl animate-fade-in">
            <div class="text-center mb-8">
                 <h1 class="text-4xl font-bold text-white mb-2 transform hover:scale-105 transition-transform duration-300">Shopping Cart</h1>
                 <p class="text-gray-300 text-lg">Review your selected items</p>
            </div>

            <!-- Livewire Cart Component -->
            <livewire:cart-manager />
        </div>
    </main>

    <!-- Footer -->
    <div class="mt-auto">
        <x-footer />
    </div>

    @livewireScripts
</body>

</html> 
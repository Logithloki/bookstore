<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Books - Book Hive</title>
    <link rel="stylesheet" href="{{ asset('book-hive/dist/output.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    @livewireStyles
</head>

<body class="bg-gray-100">
    <!-- Navbar -->
    <x-navbar currentPage="search" />

    <!-- Main Content -->
    <main class="container mx-auto px-6 py-8 animate-fade-in">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">Search Books</h1>
        
        <!-- Livewire Search Component -->
        <livewire:search-books />
    </main>

    <!-- Footer -->
    <x-footer />

    @livewireScripts
</body>

</html> 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account - Book Hive</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body class="bg-gray-100 font-sans">
    <!-- Navbar -->
    <x-navbar currentPage="account" />

    <div class="relative min-h-screen">
        <!-- Background Image -->
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('img/About.jpg') }}" alt="Library Background" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-black bg-opacity-50"></div>
        </div>

        <!-- Main Section -->
        <main class="container mx-auto py-16 px-6 relative z-10">
            <!-- Profile Management Section -->
            <div class="max-w-lg mx-auto p-8 rounded-lg shadow-lg bg-white bg-opacity-90 relative mb-8">
                <h1 class="text-4xl font-bold mb-6 text-center text-gray-800">Account</h1>
                
                <div class="text-center mb-6">
                    <div class="mb-4">
                        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                            <img src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" class="rounded-full size-20 object-cover mx-auto mb-4">
                        @endif
                        <h2 class="text-2xl font-semibold text-gray-800">{{ Auth::user()->name }}</h2>
                        <p class="text-gray-600">{{ Auth::user()->email }}</p>
                        @if(Auth::user()->phonenumber)
                            <p class="text-gray-600">📞 {{ Auth::user()->phonenumber }}</p>
                        @endif
                        @if(Auth::user()->location)
                            <p class="text-gray-600">📍 {{ Auth::user()->location }}</p>
                        @endif
                    </div>
                    
                    <a href="{{ route('profile.show') }}" class="bg-orange-500 text-white px-6 py-3 rounded-lg shadow hover:bg-orange-600 focus:ring focus:ring-orange-300 transition duration-200 inline-block">
                        <i class="fa fa-edit mr-2"></i>Edit Profile
                    </a>
                </div>
            </div>

            <!-- My Books Section -->
            <div class="bg-white bg-opacity-90 p-8 rounded-lg shadow-lg">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-3xl font-bold text-gray-800">My Books</h2>
                    <a href="{{ route('admin.books.index') }}" class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg text-sm transition duration-200">
                        <i class="fa fa-cogs mr-1"></i>Manage Books
                    </a>
                </div>
                
                @php
                    $userBooks = \App\Models\Book::where('user_id', Auth::user()->_id)->get();
                @endphp
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse ($userBooks as $book)
                        <div class="text-center p-4 border rounded-lg shadow-sm bg-gray-50 hover:shadow-md transition duration-200">
                            @if($book->cover)
                                <img src="{{ asset('storage/' . $book->cover) }}" alt="{{ $book->title }}" class="h-48 w-32 mx-auto object-cover rounded mb-2">
                            @else
                                <div class="h-48 w-32 mx-auto bg-gray-200 rounded mb-2 flex items-center justify-center">
                                    <i class="fa fa-book text-gray-400 text-3xl"></i>
                                </div>
                            @endif
                            <h3 class="font-bold mt-2 text-lg text-gray-800">{{ $book->title }}</h3>
                            <p class="text-gray-600 mb-4 text-sm">by {{ $book->author }}</p>
                            <div class="flex justify-center space-x-2">
                                <a href="{{ route('books.show', $book->_id) }}" class="text-blue-500 hover:underline text-sm">
                                    <i class="fa fa-eye mr-1"></i>View
                                </a>
                                <a href="{{ route('books.edit', $book->_id) }}" class="text-orange-500 hover:underline text-sm">
                                    <i class="fa fa-edit mr-1"></i>Edit
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-8">
                            <i class="fa fa-book text-gray-300 text-6xl mb-4"></i>
                            <p class="text-gray-500 text-lg mb-4">No books uploaded yet.</p>
                            <a href="{{ route('add-book') }}" class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-3 rounded-lg transition duration-200">
                                <i class="fa fa-plus mr-2"></i>Add Your First Book
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>
        </main>
    </div>

    <!-- Footer -->
    <x-footer />
</body>
</html>
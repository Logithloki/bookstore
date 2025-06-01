{{-- resources/views/books/show.blade.php --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $book->title }} - Book Hive</title>
    {{-- Use Vite to include compiled CSS and JS --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="bg-gray-50 font-sans antialiased min-h-screen">
    <!-- Navbar -->
    <x-navbar currentPage="books" />

    <!-- Main Content -->
    <main class="container mx-auto py-8">
        <div class="max-w-7xl mx-auto">            <!-- Back Button -->
            <div class="mb-6">
                <a href="{{ url()->previous() }}" class="inline-flex items-center text-orange-600 hover:text-orange-800 transition-colors duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Books
                </a>
            </div>

            <!-- Success/Error Messages -->
            @if (session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Main Book Card -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
                <div class="lg:flex">
                    <!-- Book Cover Section -->
                    <div class="lg:w-1/3 p-8 bg-gradient-to-br from-orange-50 to-orange-100">
                        <div class="flex justify-center">
                            @if($book->cover)
                                <img src="{{ asset('storage/' . $book->cover) }}" 
                                     alt="{{ $book->title }}" 
                                     class="w-full max-w-sm h-auto rounded-lg shadow-lg object-cover">
                            @else
                                <div class="w-full max-w-sm h-96 bg-white rounded-lg shadow-lg flex items-center justify-center border-2 border-dashed border-gray-300">
                                    <div class="text-center text-gray-400">
                                        <i class="fas fa-book text-6xl mb-4"></i>
                                        <p class="text-lg">No cover available</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Book Information Section -->
                    <div class="lg:w-2/3 p-8">
                        <!-- Title and Author -->
                        <div class="mb-6">
                            <h1 class="text-4xl font-bold text-gray-900 mb-2">{{ $book->title }}</h1>
                            <p class="text-xl text-gray-600">by <span class="font-semibold text-orange-600">{{ $book->author }}</span></p>
                        </div>

                        <!-- Price/Rental/Exchange Info -->
                        <div class="mb-6 p-4 bg-orange-50 rounded-lg border-l-4 border-orange-500">
                            @if($book->for === 'Sell')
                                <div class="flex items-center">
                                    <i class="fas fa-tag text-orange-500 mr-3 text-xl"></i>
                                    <div>
                                        <p class="text-sm text-gray-600 uppercase tracking-wide">Price</p>
                                        <p class="text-3xl font-bold ">LKR {{ number_format($book->price, 2) }}</p>
                                    </div>
                                </div>
                            @elseif($book->for === 'Rental')
                                <div class="flex items-center">
                                    <i class="fas fa-clock text-orange-500 mr-3 text-xl"></i>
                                    <div>
                                        <p class="text-sm text-gray-600 uppercase tracking-wide">Rental Price</p>
                                        <p class="text-3xl font-bold">LKR {{ number_format($book->price, 2) }}</p>
                                        <p class="text-sm text-gray-600">for {{ $book->rental_days }} days</p>
                                    </div>
                                </div>
                            @elseif($book->for === 'Exchange')
                                <div class="flex items-center">
                                    <i class="fas fa-exchange-alt text-orange-500 mr-3 text-xl"></i>
                                    <div>
                                        <p class="text-sm text-gray-600 uppercase tracking-wide">Exchange Category</p>
                                        <p class="text-xl font-semibold ">{{ $book->exchange_category }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Book Details Grid -->
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-6">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-500 uppercase tracking-wide mb-1">Category</p>
                                <p class="font-semibold text-gray-900">{{ $book->category }}</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-500 uppercase tracking-wide mb-1">Condition</p>
                                <p class="font-semibold text-gray-900">{{ $book->condition }}</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-500 uppercase tracking-wide mb-1">Pages</p>
                                <p class="font-semibold text-gray-900">{{ $book->pages }}</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-500 uppercase tracking-wide mb-1">Language</p>
                                <p class="font-semibold text-gray-900">{{ $book->language }}</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-500 uppercase tracking-wide mb-1">Year</p>
                                <p class="font-semibold text-gray-900">{{ $book->year }}</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-500 uppercase tracking-wide mb-1">Type</p>
                                <p class="font-semibold text-gray-900">{{ $book->for }}</p>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-col sm:flex-row gap-4">
                            @auth
                                @if(auth()->id() && $book->user_id != auth()->id())
                                    <form action="{{ route('cart.add', $book->_id) }}" method="POST" class="flex-1">
                                        @csrf
                                        <button type="submit" class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 px-6 rounded-lg transition duration-200 flex items-center justify-center">
                                            <i class="fas fa-heart mr-2"></i>
                                            Add to Wishlist
                                        </button>
                                    </form>
                                @elseif(auth()->id() && $book->user_id == auth()->id())
                                    <a href="{{ route('books.edit', $book->_id) }}" class="flex-1 bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-6 rounded-lg transition duration-200 flex items-center justify-center">
                                        <i class="fas fa-edit mr-2"></i>
                                        Edit Book
                                    </a>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="flex-1 bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 px-6 rounded-lg transition duration-200 flex items-center justify-center">
                                    <i class="fas fa-sign-in-alt mr-2"></i>
                                    Login to Interact
                                </a>
                            @endauth

                            @if ($book->user_id != (auth()->id() ?? null))
                                <button id="show-contact" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 px-6 rounded-lg transition duration-200 flex items-center justify-center">
                                    <i class="fas fa-user mr-2"></i>
                                    Show Contact
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Seller Contact Information -->
            @if ($book->user_id != (auth()->id() ?? null))
                <div id="seller-contact-info" class="hidden bg-white rounded-xl shadow-lg p-8 mb-8">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <i class="fas fa-user-circle text-orange-500 mr-3"></i>
                        Seller Information
                    </h3>
                    @if($book->user)
                        <div class="grid md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                                    <i class="fas fa-user text-orange-500 mr-4 text-xl"></i>
                                    <div>
                                        <p class="text-sm text-gray-500 uppercase tracking-wide">Name</p>
                                        <p class="font-semibold text-gray-900">{{ $book->user->name }}</p>
                                    </div>
                                </div>
                                @if($book->user->location)
                                    <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                                        <i class="fas fa-map-marker-alt text-orange-500 mr-4 text-xl"></i>
                                        <div>
                                            <p class="text-sm text-gray-500 uppercase tracking-wide">Location</p>
                                            <p class="font-semibold text-gray-900">{{ $book->user->location }}</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="space-y-4">
                                @if($book->user->phonenumber)
                                    <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                                        <i class="fas fa-phone text-orange-500 mr-4 text-xl"></i>
                                        <div>
                                            <p class="text-sm text-gray-500 uppercase tracking-wide">Phone</p>
                                            <a href="tel:{{ $book->user->phonenumber }}" class="font-semibold text-orange-600 hover:text-orange-800 transition-colors">{{ $book->user->phonenumber }}</a>
                                        </div>
                                    </div>
                                @endif
                            
                            </div>
                        </div>
                    @else
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                            <p class="text-red-700 flex items-center">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                Seller information not available.
                            </p>
                        </div>
                    @endif
                </div>
            @endif            <!-- Related Books Section -->
            <div class="bg-white rounded-xl shadow-lg p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-book-open text-orange-500 mr-3"></i>
                    Other Books You Might Like
                </h2>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
                    @forelse ($relatedBooks ?? [] as $relatedBook)
                        <a href="{{ route('books.show', $relatedBook->_id) }}" class="group block">
                            <div class="bg-gray-50 rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-all duration-300 group-hover:scale-105">
                                @if($relatedBook->cover)
                                    <img src="{{ asset('storage/' . $relatedBook->cover) }}" 
                                         alt="{{ $relatedBook->title }}" 
                                         class="w-full h-48 object-cover">
                                @else
                                    <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                        <i class="fas fa-book text-gray-400 text-3xl"></i>
                                    </div>
                                @endif
                                <div class="p-4">
                                    <h4 class="font-semibold text-sm mb-1 line-clamp-2 text-gray-900 group-hover:text-orange-600">{{ $relatedBook->title }}</h4>
                                    <p class="text-gray-600 text-xs mb-2">{{ $relatedBook->author }}</p>
                                    <div class="flex justify-between items-center">
                                        <span class="text-xs bg-orange-100 text-orange-800 px-2 py-1 rounded-full">{{ $relatedBook->category }}</span>
                                        @if($relatedBook->for === 'Sell')
                                            <span class="font-bold text-orange-600 text-sm">{{ number_format($relatedBook->price, 0) }} LKR</span>
                                        @elseif($relatedBook->for === 'Rental')
                                            <span class="font-bold text-orange-600 text-sm">{{ number_format($relatedBook->price, 0) }} LKR/day</span>
                                        @else
                                            <span class="font-bold text-orange-600 text-sm">Exchange</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="col-span-full text-center py-8">
                            <i class="fas fa-book-open text-gray-300 text-4xl mb-4"></i>
                            <p class="text-gray-500">No related books found.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <x-footer />

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const showContactButton = document.getElementById('show-contact');
            const sellerContactInfo = document.getElementById('seller-contact-info');

            if (showContactButton && sellerContactInfo) {
                showContactButton.addEventListener('click', function () {
                    sellerContactInfo.classList.toggle('hidden');
                    const icon = showContactButton.querySelector('i');
                    const text = showContactButton.querySelector('span') || showContactButton;
                    
                    if (sellerContactInfo.classList.contains('hidden')) {
                        icon.className = 'fas fa-user mr-2';
                        showContactButton.innerHTML = '<i class="fas fa-user mr-2"></i>Show Contact';
                    } else {
                        icon.className = 'fas fa-user-times mr-2';
                        showContactButton.innerHTML = '<i class="fas fa-user-times mr-2"></i>Hide Contact';
                    }
                });
            }
        });
    </script>

</body>

</html>
<div>
    @if(count($cartItems) > 0)
        <div class="bg-white/90 rounded-2xl shadow-xl overflow-hidden border border-white backdrop-filter backdrop-blur-lg">
            <!-- Cart Header -->
            <div class="bg-gray-100 px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-xl font-semibold text-gray-800">My Wishlist (<span class="text-orange-700">{{ $itemCount }}</span> items)</h2>
                <button wire:click="clearCart" class="text-red-600 hover:text-red-800 text-sm font-medium transition-colors duration-200">
                    Clear Wishlist
                </button>
            </div>

            <!-- Cart Items -->
            <div class="divide-y divide-gray-200">
                @foreach($cartItems as $item)
                    <div class="p-6 flex flex-col sm:flex-row items-start sm:items-center gap-6 bg-white hover:bg-gray-50 transition-colors duration-200">
                        <!-- Book Image -->
                        <a href="{{ route('books.show', $item['book_id']) }}" class="block flex-shrink-0">
                            @if(isset($item['cover']) && $item['cover'])
                                <img src="{{ asset('storage/' . $item['cover']) }}" alt="{{ $item['title'] }}" 
                                    class="w-24 h-28 object-cover rounded-lg shadow-md transition-transform duration-200 hover:scale-105">
                            @else
                                <div class="w-24 h-28 bg-gray-200 rounded-lg shadow-md flex items-center justify-center">
                                    <i class="fa fa-book text-gray-400 text-xl"></i>
                                </div>
                            @endif
                        </a>

                        <!-- Book Details -->
                        <div class="flex-1 min-w-0">
                            <h3 class="text-lg font-medium text-gray-900 truncate mb-1">
                                <a href="{{ route('books.show', $item['book_id']) }}" class="hover:text-orange-600 transition-colors duration-200">
                                    {{ $item['title'] }}
                                </a>
                            </h3>
                            <p class="text-sm text-gray-600 mb-2">by {{ $item['author'] }}</p>
                            <p class="text-sm text-gray-600 mt-1">
                                @if($item['type'] === 'Sell')
                                    For Sale
                                @elseif($item['type'] === 'Rental')
                                    For Rent
                                @else
                                    For Exchange
                                @endif
                            </p>
                        </div>

                        <!-- Price and Quantity -->
                        <div class="flex flex-col sm:flex-row items-end sm:items-center gap-4 text-gray-800">
                            <div class="text-right">
                                <p class="text-xl font-semibold text-orange-700">
                                    {{ number_format($item['price'], 2) }} LKR
                                    @if($item['type'] === 'Rental')
                                        <span class="text-sm text-gray-600">/day</span>
                                    @endif
                                </p>
                                <p class="text-sm text-gray-600">Price: {{ number_format($item['price'], 2) }} LKR</p>
                            </div>

                            <!-- Remove Button -->
                            <button wire:click="removeItem('{{ $item['id'] }}')"
                                class="text-red-600 hover:text-red-800 p-2 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-red-400">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Cart Footer -->
            <div class="bg-gray-100 px-6 py-4 border-t border-gray-200 flex justify-between items-center">
                <div class="text-lg font-semibold text-gray-900">
                    Total: <span class="text-orange-700">{{ number_format($total, 2) }} LKR</span>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('search') }}" 
                        class="bg-gray-500 text-white py-3 px-6 rounded-lg font-medium
                            transform transition-all duration-300 hover:bg-gray-600 hover:scale-[1.02]
                            focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 focus:ring-offset-gray-100">
                        Continue Shopping
                    </a>
                    <button wire:click="clearCart" 
                        class="bg-red-500 text-white py-3 px-6 rounded-lg font-medium
                            transform transition-all duration-300 hover:bg-red-600 hover:scale-[1.02]
                            focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 focus:ring-offset-gray-100">
                        Clear Wishlist
                    </button>
                </div>
            </div>
        </div>
    @else
        <div class="bg-white/90 rounded-2xl shadow-xl p-8 text-center border border-white backdrop-filter backdrop-blur-lg">
            <div class="text-gray-500 mb-4">
                <i class="fa fa-heart text-4xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Your wishlist is empty</h3>
            <p class="text-gray-600 mb-6">Add some books to your wishlist to save them for later!</p>
            <a href="{{ route('search') }}" 
                class="inline-block bg-orange-500 text-white py-3 px-6 rounded-lg font-medium
                    transform transition-all duration-300 hover:bg-orange-600 hover:scale-[1.02]
                    focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 focus:ring-offset-white">
                Browse Books
            </a>
        </div>
    @endif
</div> 
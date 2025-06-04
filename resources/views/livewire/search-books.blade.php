<div>
    @if(isset($error))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <strong>Error:</strong> {{ $error }}
        </div>
    @endif

    <!-- Search and Filters Section -->
    <div class="bg-white rounded-2xl shadow-xl p-6 mb-8 border border-gray-200 backdrop-filter backdrop-blur-lg">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 items-center">
            <!-- Search Input -->
            <div class="lg:col-span-2">
                <div class="relative">
                    <input wire:model.live.debounce.300ms="search" type="text" 
                        placeholder="Search by title, author, or category..."
                        class="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-500
                            focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500
                            transition-all duration-300 hover:bg-gray-200">
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                        <i class="fa fa-search text-gray-500"></i>
                    </div>
                </div>
            </div>

            <!-- Category Filter -->
            <div>
                <select wire:model.live="category" class="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-500
                    focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500
                    transition-all duration-300 hover:bg-gray-200 appearance-none custom-select-dark">
                    <option value="" class="text-gray-900 bg-white">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" class="text-gray-900 bg-white">{{ $cat }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Type Filter -->
            <div>
                <select wire:model.live="type" class="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-500
                    focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500
                    transition-all duration-300 hover:bg-gray-200 appearance-none custom-select-dark">
                    <option value="" class="text-gray-900 bg-white">All Types</option>
                    @foreach($types as $type)
                        <option value="{{ $type }}" class="text-gray-900 bg-white">{{ $type }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Condition Filter -->
            <div>
                <select wire:model.live="condition" class="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-500
                    focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500
                    transition-all duration-300 hover:bg-gray-200 appearance-none custom-select-dark">
                    <option value="" class="text-gray-900 bg-white">All Conditions</option>
                    @foreach($conditions as $condition)
                        <option value="{{ $condition }}" class="text-gray-900 bg-white">{{ $condition }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- Results Section -->
    <div class="bg-white rounded-2xl shadow-xl p-6 border border-gray-200 backdrop-filter backdrop-blur-lg">
        <!-- Sort Options -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-6 space-y-4 md:space-y-0">
            <div class="text-gray-800 text-sm">
                Showing {{ $books->firstItem() ?? 0 }} to {{ $books->lastItem() ?? 0 }} of {{ $books->total() }} results
            </div>
            <div class="flex space-x-4 text-sm items-center">
                <span class="text-gray-800">Sort by:</span>
                <button wire:click="sortBy('created_at')" 
                        class="text-orange-700 hover:text-orange-600 font-semibold transition-colors duration-200 focus:outline-none 
                                {{ $sortBy === 'created_at' ? 'underline underline-offset-2 text-orange-600' : '' }}">
                    Latest
                    @if($sortBy === 'created_at')
                        <i class="fa fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1 text-orange-700"></i>
                    @endif
                </button>
                <button wire:click="sortBy('price')" 
                        class="text-orange-700 hover:text-orange-600 font-semibold transition-colors duration-200 focus:outline-none 
                                {{ $sortBy === 'price' ? 'underline underline-offset-2 text-orange-600' : '' }}">
                    Price
                    @if($sortBy === 'price')
                        <i class="fa fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1 text-orange-700"></i>
                    @endif
                </button>
            </div>
        </div>

        <!-- Books Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
            @forelse($books as $index => $book)
                <a href="{{ route('books.show', $book->_id) }}" 
                   class="block bg-white rounded-lg shadow-md overflow-hidden border border-gray-300
                          hover:shadow-lg transition-all duration-300 ease-in-out transform hover:-translate-y-1 hover:border-orange-500">
                    @if($book->cover)
                        <img src="{{ asset('storage/' . $book->cover) }}" alt="{{ $book->title }}" 
                             class="w-full h-48 object-cover">
                    @else
                        <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                            <i class="fa fa-book text-gray-400 text-4xl"></i>
                        </div>
                    @endif
                    <div class="p-4">
                        <h3 class="font-semibold text-lg mb-1 truncate text-gray-900">{{ $book->title }}</h3>
                        <p class="text-gray-700 text-sm mb-2">by {{ $book->author }}</p>
                        <div class="flex justify-between items-center mt-3">
                            <span class="text-xs text-gray-800 bg-gray-200 px-2 py-1 rounded-full">{{ $book->category }}</span>
                            @if($book->type === 'Sell')
                                <span class="font-bold text-orange-700 text-lg">{{ number_format($book->price, 2) }} LKR</span>
                            @elseif($book->type === 'Rental')
                                <span class="font-bold text-orange-700 text-lg">{{ number_format($book->price, 2) }} LKR/day</span>
                            @else
                                <span class="font-bold text-orange-700 text-lg">Exchange</span>
                            @endif
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-full text-center py-8">
                    <p class="text-gray-800 text-lg">No books found matching your criteria.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $books->links('vendor.livewire.simple-tailwind') }} {{-- Using the correct simple-tailwind pagination view --}}
        </div>
    </div>

<style>
    /* Custom styling for select dropdown arrow color for light backgrounds */
    .custom-select-dark {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%234b5563' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
        background-size: 1.5em 1.5em;
        padding-right: 2.5rem;
    }
</style>
</div>
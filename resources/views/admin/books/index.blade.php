@extends('layouts.admin')

@section('content')
<div class="container mx-auto p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">My Books</h1>
        <a href="{{ route('admin.books.create') }}" class="bg-orange-600 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded transition duration-200">
            <i class="fa fa-plus mr-2"></i>Add New Book
        </a>
    </div>

    @if (session('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('message') }}</span>
        </div>
    @endif

    @if($userBooks->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($userBooks as $book)
                <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200 hover:shadow-lg transition-shadow duration-300">
                    @if($book->cover)
                        <img src="{{ asset('storage/' . $book->cover) }}" alt="{{ $book->title }}" class="w-full h-48 object-cover">
                    @else
                        <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                            <i class="fa fa-book text-gray-400 text-4xl"></i>
                        </div>
                    @endif
                    
                    <div class="p-4">
                        <h3 class="font-semibold text-lg mb-2 text-gray-800 truncate">{{ $book->title }}</h3>
                        <p class="text-gray-600 text-sm mb-2">by {{ $book->author }}</p>
                        <p class="text-gray-500 text-xs mb-3">{{ $book->category }}</p>
                        
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-xs px-2 py-1 rounded-full 
                                @if($book->condition === 'New') bg-green-100 text-green-800
                                @elseif($book->condition === 'Good') bg-blue-100 text-blue-800
                                @elseif($book->condition === 'Fair') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ $book->condition }}
                            </span>                            
                            @if($book->type === 'Sell')
                                <span class="font-bold text-orange-600">{{ number_format($book->price, 2) }} LKR</span>
                            @elseif($book->type === 'Rental')
                                <span class="font-bold text-blue-600">{{ number_format($book->price, 2) }} LKR/day</span>
                            @else
                                <span class="font-bold text-green-600">Exchange</span>
                            @endif
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <a href="{{ route('books.show', $book->_id) }}" 
                               class="text-orange-600 hover:text-orange-800 text-sm font-medium transition duration-200">
                                <i class="fa fa-eye mr-1"></i>View
                            </a>
                            
                            <div class="flex space-x-2">
                                <a href="{{ route('books.edit', $book->_id) }}" 
                                   class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm transition duration-200">
                                    <i class="fa fa-edit mr-1"></i>Edit
                                </a>
                                
                                <form action="{{ route('admin.books.destroy', $book->_id) }}" method="POST" 
                                      onsubmit="return confirm('Are you sure you want to delete this book?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm transition duration-200">
                                        <i class="fa fa-trash mr-1"></i>Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-12">
            <div class="mb-4">
                <i class="fa fa-book text-gray-400 text-6xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">No books found</h3>
            <p class="text-gray-500 mb-4">You haven't added any books yet.</p>
            <a href="{{ route('admin.books.create') }}" 
               class="bg-orange-600 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded transition duration-200">
                <i class="fa fa-plus mr-2"></i>Add Your First Book
            </a>
        </div>
    @endif
</div>
@endsection

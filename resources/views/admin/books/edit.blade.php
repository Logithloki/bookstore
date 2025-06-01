@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">Edit Book</h1>

    @if (session('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('message') }}</span>
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Whoops!</strong>
            <span class="block sm:inline">There were some problems with your input.</span>
            <ul class="mt-3 list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.books.update', $book->_id) }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow-md">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="title" class="block text-gray-700 text-sm font-bold mb-2">Title:</label>
            <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="title" name="title" value="{{ old('title', $book->title) }}" required>
        </div>

        <div class="mb-4">
            <label for="author" class="block text-gray-700 text-sm font-bold mb-2">Author:</label>
            <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="author" name="author" value="{{ old('author', $book->author) }}" required>
        </div>

        <div class="mb-4">
            <label for="category" class="block text-gray-700 text-sm font-bold mb-2">Category:</label>
            <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="category" name="category" value="{{ old('category', $book->category) }}" required>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <div>
                <label for="year" class="block text-gray-700 text-sm font-bold mb-2">Publication Year:</label>
                <input type="number" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="year" name="year" value="{{ old('year', $book->year) }}" required>
            </div>
            <div>
                <label for="pages" class="block text-gray-700 text-sm font-bold mb-2">Pages:</label>
                <input type="number" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="pages" name="pages" value="{{ old('pages', $book->pages) }}" required>
            </div>
            <div>
                <label for="language" class="block text-gray-700 text-sm font-bold mb-2">Language:</label>
                <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="language" name="language" value="{{ old('language', $book->language) }}" required>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label for="condition" class="block text-gray-700 text-sm font-bold mb-2">Condition:</label>
                <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="condition" name="condition" required>
                    <option value="New" {{ old('condition', $book->condition) == 'New' ? 'selected' : '' }}>New</option>
                    <option value="Good" {{ old('condition', $book->condition) == 'Good' ? 'selected' : '' }}>Good</option>
                    <option value="Fair" {{ old('condition', $book->condition) == 'Fair' ? 'selected' : '' }}>Fair</option>
                    <option value="Poor" {{ old('condition', $book->condition) == 'Poor' ? 'selected' : '' }}>Poor</option>
                </select>
            </div>
            <div>
                <label for="for" class="block text-gray-700 text-sm font-bold mb-2">For:</label>
                <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="for" name="for" required>
                    <option value="Sell" {{ old('for', $book->for) == 'Sell' ? 'selected' : '' }}>Sell</option>
                    <option value="Rental" {{ old('for', $book->for) == 'Rental' ? 'selected' : '' }}>Rental</option>
                    <option value="Exchange" {{ old('for', $book->for) == 'Exchange' ? 'selected' : '' }}>Exchange</option>
                </select>
            </div>
        </div>

        <div class="mb-4" id="price-field" style="display: {{ old('for', $book->for) == 'Sell' ? 'block' : 'none' }};">
            <label for="price" class="block text-gray-700 text-sm font-bold mb-2">Price (LKR):</label>
            <input type="number" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="price" name="price" value="{{ old('price', $book->price) }}">
        </div>

        <div class="mb-4" id="rental-days-field" style="display: {{ old('for', $book->for) == 'Rental' ? 'block' : 'none' }};">
            <label for="rental_days" class="block text-gray-700 text-sm font-bold mb-2">Rental Days:</label>
            <input type="number" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="rental_days" name="rental_days" value="{{ old('rental_days', $book->rental_days) }}">
        </div>

        <div class="mb-4" id="exchange-category-field" style="display: {{ old('for', $book->for) == 'Exchange' ? 'block' : 'none' }};">
            <label for="exchange_category" class="block text-gray-700 text-sm font-bold mb-2">Exchange Category:</label>
            <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="exchange_category" name="exchange_category" value="{{ old('exchange_category', $book->exchange_category) }}">
        </div>

        <div class="mb-4">
            <label for="cover" class="block text-gray-700 text-sm font-bold mb-2">Book Cover:</label>
            <input type="file" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="cover" name="cover" accept="image/*">
            @if ($book->cover)
                <p class="text-gray-600 text-sm mt-2">Current cover: <a href="{{ asset('storage/' . $book->cover) }}" target="_blank">View Current Cover</a></p>
            @endif
        </div>

        <div class="flex items-center justify-between">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Update Book
            </button>
        </div>
    </form>
</div>

<script>
    document.getElementById('for').addEventListener('change', function() {
        const selectedValue = this.value;
        document.getElementById('price-field').style.display = selectedValue === 'Sell' ? 'block' : 'none';
        document.getElementById('rental-days-field').style.display = selectedValue === 'Rental' ? 'block' : 'none';
        document.getElementById('exchange-category-field').style.display = selectedValue === 'Exchange' ? 'block' : 'none';

        // Clear values of hidden fields if changing type
        if (selectedValue !== 'Sell') document.getElementById('price').value = '';
        if (selectedValue !== 'Rental') document.getElementById('rental_days').value = '';
        if (selectedValue !== 'Exchange') document.getElementById('exchange_category').value = '';
    });

    // Initialize display based on current value on page load
    document.addEventListener('DOMContentLoaded', function() {
        const selectedValue = document.getElementById('for').value;
        document.getElementById('price-field').style.display = selectedValue === 'Sell' ? 'block' : 'none';
        document.getElementById('rental-days-field').style.display = selectedValue === 'Rental' ? 'block' : 'none';
        document.getElementById('exchange-category-field').style.display = selectedValue === 'Exchange' ? 'block' : 'none';
    });
</script>
@endsection 
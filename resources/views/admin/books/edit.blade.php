{{-- resources/views/admin/books/edit.blade.php --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Book - Book Hive</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body class="bg-gray-50 font-sans antialiased">
    <!-- Navbar -->
    <x-navbar currentPage="edit-book" />

    <!-- Main Content -->
    <main class="min-h-screen py-12">
        <div class="max-w-4xl mx-auto px-6">
            <!-- Header Section -->
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold text-gray-800 mb-4">Edit Your Book</h1>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Update your book details and keep your listing current with the BookHive community.
                </p>
            </div>

            <!-- Success Message -->
            @if (session('message'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-8 rounded-lg shadow-sm" role="alert">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fa fa-check-circle text-green-500"></i>
                        </div>
                        <div class="ml-3">
                            <p class="font-medium">Success!</p>
                            <p class="text-sm">{{ session('message') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-8 rounded-lg shadow-sm" role="alert">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fa fa-exclamation-triangle text-red-500"></i>
                        </div>
                        <div class="ml-3">
                            <p class="font-medium">Please correct the following errors:</p>
                            <ul class="mt-2 list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Edit Book Form -->
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <div class="bg-orange-500 px-8 py-6">
                    <h2 class="text-2xl font-bold text-white flex items-center">
                        <i class="fa fa-edit mr-3"></i>
                        Update Book Details
                    </h2>
                </div>

                <form action="{{ route('admin.books.update', $book->_id) }}" method="POST" enctype="multipart/form-data" class="p-8">                    @csrf
                    @method('PUT')

                    <!-- Basic Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fa fa-book text-orange-500 mr-2"></i>Book Title *
                            </label>
                            <input type="text" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-400 focus:border-transparent transition duration-200" 
                                   id="title" 
                                   name="title" 
                                   value="{{ old('title', $book->title) }}" 
                                   placeholder="Enter book title"
                                   required>
                        </div>

                        <div>
                            <label for="author" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fa fa-user text-orange-500 mr-2"></i>Author *
                            </label>
                            <input type="text" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-400 focus:border-transparent transition duration-200" 
                                   id="author" 
                                   name="author" 
                                   value="{{ old('author', $book->author) }}" 
                                   placeholder="Enter author name"
                                   required>
                        </div>

                        <div>
                            <label for="category" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fa fa-tags text-orange-500 mr-2"></i>Category *
                            </label>
                            <input type="text" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-400 focus:border-transparent transition duration-200" 
                                   id="category" 
                                   name="category" 
                                   value="{{ old('category', $book->category) }}" 
                                   placeholder="e.g., Fiction, Science, History"
                                   required>
                        </div>

                        <div>
                            <label for="language" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fa fa-globe text-orange-500 mr-2"></i>Language *
                            </label>
                            <input type="text" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-400 focus:border-transparent transition duration-200" 
                                   id="language" 
                                   name="language" 
                                   value="{{ old('language', $book->language) }}" 
                                   placeholder="e.g., English, Spanish"
                                   required>
                        </div>
                    </div>

                    <!-- Publication Details -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div>
                            <label for="year" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fa fa-calendar text-orange-500 mr-2"></i>Publication Year *
                            </label>
                            <input type="number" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-400 focus:border-transparent transition duration-200" 
                                   id="year" 
                                   name="year" 
                                   value="{{ old('year', $book->year) }}" 
                                   min="1000" 
                                   max="9999"
                                   placeholder="2023"
                                   required>
                        </div>

                        <div>
                            <label for="pages" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fa fa-file-text text-orange-500 mr-2"></i>Pages *
                            </label>
                            <input type="number" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-400 focus:border-transparent transition duration-200" 
                                   id="pages" 
                                   name="pages" 
                                   value="{{ old('pages', $book->pages) }}" 
                                   min="1"
                                   placeholder="200"
                                   required>
                        </div>

                        <div>
                            <label for="condition" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fa fa-star text-orange-500 mr-2"></i>Condition *
                            </label>
                            <select class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-400 focus:border-transparent transition duration-200" 
                                    id="condition" 
                                    name="condition" 
                                    required>
                                <option value="">Select condition</option>
                                <option value="New" {{ old('condition', $book->condition) == 'New' ? 'selected' : '' }}>New</option>
                                <option value="Good" {{ old('condition', $book->condition) == 'Good' ? 'selected' : '' }}>Good</option>
                                <option value="Fair" {{ old('condition', $book->condition) == 'Fair' ? 'selected' : '' }}>Fair</option>
                                <option value="Poor" {{ old('condition', $book->condition) == 'Poor' ? 'selected' : '' }}>Poor</option>
                            </select>
                        </div>
                    </div>                    <!-- Listing Type -->
                    <div class="mb-8">
                        <label for="type" class="block text-sm font-semibold text-gray-700 mb-4">
                            <i class="fa fa-handshake-o text-orange-500 mr-2"></i>What would you like to do with this book? *
                        </label>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="relative">
                                <input type="radio" 
                                       id="type_sell" 
                                       name="type" 
                                       value="Sell" 
                                       class="sr-only" 
                                       {{ old('type', $book->type) == 'Sell' ? 'checked' : '' }}>
                                <label for="type_sell" 
                                       class="flex items-center justify-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-orange-400 transition duration-200 radio-label">
                                    <i class="fa fa-dollar text-green-500 text-2xl mr-3"></i>
                                    <span class="font-medium">Sell</span>
                                </label>
                            </div>
                            <div class="relative">
                                <input type="radio" 
                                       id="type_rental" 
                                       name="type" 
                                       value="Rental" 
                                       class="sr-only" 
                                       {{ old('type', $book->type) == 'Rental' ? 'checked' : '' }}>
                                <label for="type_rental" 
                                       class="flex items-center justify-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-orange-400 transition duration-200 radio-label">
                                    <i class="fa fa-clock-o text-blue-500 text-2xl mr-3"></i>
                                    <span class="font-medium">Rent</span>
                                </label>
                            </div>
                            <div class="relative">
                                <input type="radio" 
                                       id="type_exchange" 
                                       name="type" 
                                       value="Exchange" 
                                       class="sr-only" 
                                       {{ old('type', $book->type) == 'Exchange' ? 'checked' : '' }}>
                                <label for="type_exchange" 
                                       class="flex items-center justify-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-orange-400 transition duration-200 radio-label">
                                    <i class="fa fa-exchange text-purple-500 text-2xl mr-3"></i>
                                    <span class="font-medium">Exchange</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Dynamic Fields -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <!-- Price Field (for Sell) -->
                        <div id="price-field" style="display: {{ old('type', $book->type) == 'Sell' ? 'block' : 'none' }};">
                            <label for="price" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fa fa-money text-green-500 mr-2"></i>Price ($)
                            </label>
                            <input type="number" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-400 focus:border-transparent transition duration-200" 
                                   id="price" 
                                   name="price" 
                                   value="{{ old('price', $book->price) }}" 
                                   step="0.01" 
                                   min="0"
                                   placeholder="25.99">
                        </div>

                        <!-- Rental Days Field (for Rental) -->
                        <div id="rental-days-field" style="display: {{ old('type', $book->type) == 'Rental' ? 'block' : 'none' }};">
                            <label for="rental_days" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fa fa-calendar-check-o text-blue-500 mr-2"></i>Rental Period (days)
                            </label>
                            <input type="number" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-400 focus:border-transparent transition duration-200" 
                                   id="rental_days" 
                                   name="rental_days" 
                                   value="{{ old('rental_days', $book->rental_days) }}" 
                                   min="1"
                                   placeholder="7">
                        </div>

                        <!-- Exchange Category Field (for Exchange) -->
                        <div id="exchange-category-field" style="display: {{ old('type', $book->type) == 'Exchange' ? 'block' : 'none' }};">
                            <label for="exchange_category" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fa fa-tags text-purple-500 mr-2"></i>Looking for Category
                            </label>
                            <input type="text" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-400 focus:border-transparent transition duration-200" 
                                   id="exchange_category" 
                                   name="exchange_category" 
                                   value="{{ old('exchange_category', $book->exchange_category) }}" 
                                   placeholder="What type of book are you looking for?">
                        </div>
                    </div>                    <!-- Book Cover Upload -->
                    <div class="mb-8">
                        <label for="cover" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fa fa-image text-orange-500 mr-2"></i>Book Cover (Optional)
                        </label>
                        @if ($book->cover)
                            <div class="mb-4">
                                <p class="text-sm text-gray-600 mb-2">Current cover:</p>
                                <img src="{{ asset('storage/' . $book->cover) }}" alt="Current book cover" class="max-h-48 rounded-lg shadow-md">
                            </div>
                        @endif
                        <div class="flex items-center justify-center w-full">
                            <label for="cover" 
                                   class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition duration-200">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <i class="fa fa-cloud-upload text-gray-400 text-4xl mb-4"></i>
                                    <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                                    <p class="text-xs text-gray-500">PNG, JPG or JPEG (MAX. 2MB)</p>
                                    @if ($book->cover)
                                        <p class="text-xs text-gray-500 mt-2">Leave empty to keep current cover</p>
                                    @endif
                                </div>
                                <input id="cover" name="cover" type="file" class="hidden" accept="image/*">
                            </label>
                        </div>
                        <div id="cover-preview" class="mt-4 hidden">
                            <img id="preview-image" src="" alt="Cover preview" class="max-h-48 rounded-lg shadow-md">
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-center space-x-4">
                        <a href="{{ route('admin.books.index') }}" 
                           class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-4 px-8 rounded-lg text-lg shadow-lg transform hover:scale-105 transition duration-200 focus:outline-none focus:ring-4 focus:ring-gray-300">
                            <i class="fa fa-arrow-left mr-2"></i>
                            Cancel
                        </a>
                        <button type="submit" 
                                class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-4 px-12 rounded-lg text-lg shadow-lg transform hover:scale-105 transition duration-200 focus:outline-none focus:ring-4 focus:ring-orange-300">
                            <i class="fa fa-save mr-2"></i>
                            Update Book
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <x-footer />

    <!-- JavaScript for dynamic form fields and image preview -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const typeRadios = document.querySelectorAll('input[name="type"]');
            const priceField = document.getElementById('price-field');
            const rentalDaysField = document.getElementById('rental-days-field');
            const exchangeCategoryField = document.getElementById('exchange-category-field');

            // Handle radio button styling
            typeRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    // Remove active styling from all labels
                    document.querySelectorAll('.radio-label').forEach(label => {
                        label.classList.remove('border-orange-500', 'bg-orange-50', 'text-orange-700');
                        label.classList.add('border-gray-300');
                    });
                    
                    // Add active styling to selected label
                    if (this.checked) {
                        const label = document.querySelector(`label[for="${this.id}"]`);
                        label.classList.add('border-orange-500', 'bg-orange-50', 'text-orange-700');
                        label.classList.remove('border-gray-300');
                    }
                    
                    // Show/hide relevant fields
                    toggleFields(this.value);
                });
            });

            function toggleFields(selectedValue) {
                // Hide all dynamic fields
                priceField.style.display = 'none';
                rentalDaysField.style.display = 'none';
                exchangeCategoryField.style.display = 'none';

                // Show relevant field
                if (selectedValue === 'Sell') {
                    priceField.style.display = 'block';
                } else if (selectedValue === 'Rental') {
                    rentalDaysField.style.display = 'block';
                } else if (selectedValue === 'Exchange') {
                    exchangeCategoryField.style.display = 'block';
                }

                // Clear values of hidden fields
                if (selectedValue !== 'Sell') document.getElementById('price').value = '';
                if (selectedValue !== 'Rental') document.getElementById('rental_days').value = '';
                if (selectedValue !== 'Exchange') document.getElementById('exchange_category').value = '';
            }

            // Initialize form state
            const checkedRadio = document.querySelector('input[name="type"]:checked');
            if (checkedRadio) {
                checkedRadio.dispatchEvent(new Event('change'));
            }

            // Image preview functionality
            const coverInput = document.getElementById('cover');
            const coverPreview = document.getElementById('cover-preview');
            const previewImage = document.getElementById('preview-image');

            coverInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImage.src = e.target.result;
                        coverPreview.classList.remove('hidden');
                    };
                    reader.readAsDataURL(file);
                } else {
                    coverPreview.classList.add('hidden');
                }
            });
        });
    </script>
</body>

</html>
{{-- resources/views/homepage.blade.php --}}
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Book Hive</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body class="bg-white font-sans antialiased">
  <!-- Navbar -->
  <x-navbar currentPage="home" />

  <!-- Hero Section -->
  <section class="bg-orange-500 text-black py-20 overflow-hidden">
    <div class="container mx-auto flex flex-col md:flex-row items-center gap-12 px-6">
      <div class="md:w-1/2 w-full animate-fade-in">
        <h1 class="text-5xl md:text-7xl font-extrabold mb-8 leading-tight text-white">BOOK HIVE</h1>
        <p class="text-lg md:text-xl max-w-xl leading-relaxed text-gray-800">
          BookHive is a community-driven platform where users can buy, sell, trade, and rent new or used books.
          Whether you're looking to pass on your favorite reads, find a rare title, or rent textbooks, BookHive makes
          it easy to connect with book lovers. Discover a sustainable way to share knowledge and explore a wide range of books—all in one hive!
        </p>
      </div>
      <div class="md:w-1/2 w-full flex justify-center animate-fade-in" style="animation-delay: 0.2s;">
        <img src="{{ asset('img/homepage.jpg') }}" alt="Bookshelf" class="rounded-lg shadow-xl w-full max-w-2xl object-cover h-72 md:h-96 border-4 border-orange-400 transform hover:scale-105 transition duration-300 ease-in-out">
      </div>
    </div>
  </section>
  <!-- Latest Books Section -->
  <section class="mx-auto px-6 py-16 animate-fade-in" style="animation-delay: 0.4s;">
    <h2 class="text-3xl font-extrabold text-gray-800 mb-10">LATEST BOOKS</h2>    <div class="flex flex-wrap gap-6 justify-left">
      @forelse ($latestBooks as $book)
        <div class="w-36 sm:w-44 cursor-pointer animate-fade-in" style="animation-delay: {{ $loop->index * 0.1 + 0.6 }}s;" onclick="openBook('{{ $book->id }}')">
          @if($book->cover)
            <img src="{{ asset('storage/' . $book->cover) }}" alt="{{ $book->title }}" class="w-full h-52 sm:h-60 object-cover shadow-md hover:shadow-xl transition duration-300 rounded">
          @else
            <div class="w-full h-52 sm:h-60 bg-gray-200 rounded shadow-md hover:shadow-xl transition duration-300 flex items-center justify-center">
              <i class="fa fa-book text-gray-400 text-2xl"></i>
            </div>
          @endif
          <h4 class="font-bold mt-3 text-center text-sm sm:text-base text-gray-900 line-clamp-2">{{ $book->title }}</h4>
          <p class="text-gray-600 text-xs sm:text-sm text-center">{{ $book->author }}</p>
        </div>
      @empty
        <p class="w-full text-gray-500 text-center">No books found.</p>
      @endforelse
    </div>
    <div class="mt-12 text-center">
      <a href="{{ route('search') }}" class="bg-orange-500 text-white py-3 px-8 rounded-lg shadow-lg hover:bg-orange-600 transition transform hover:scale-105 duration-200 ease-in-out">See More</a>
    </div>
  </section>
  <!-- Exchange Books Section -->
  <section class="mx-auto px-6 py-16 bg-gray-50">
    <h2 class="text-3xl font-extrabold text-gray-800 mb-10">EXCHANGE BOOKS</h2>    <div class="flex flex-wrap gap-6 justify-left">
      @forelse ($exchangeBooks as $book)
        <div class="w-36 sm:w-44 cursor-pointer" onclick="openBook('{{ $book->id }}')">
          @if($book->cover)
            <img src="{{ asset('storage/' . $book->cover) }}" alt="{{ $book->title }}" class="w-full h-52 sm:h-60 object-cover shadow-md hover:shadow-xl transition duration-300 rounded">
          @else
            <div class="w-full h-52 sm:h-60 bg-gray-200 rounded shadow-md hover:shadow-xl transition duration-300 flex items-center justify-center">
              <i class="fa fa-book text-gray-400 text-2xl"></i>
            </div>
          @endif
          <h4 class="font-bold mt-3 text-center text-sm sm:text-base text-gray-900 line-clamp-2">{{ $book->title }}</h4>
          <p class="text-gray-600 text-xs sm:text-sm text-center">{{ $book->author }}</p>
        </div>
      @empty
        <p class="w-full text-gray-500 text-center">No books found.</p>
      @endforelse
    </div>
    <div class="mt-12 text-center">
      <a href="{{ route('search') }}?type=Exchange" class="bg-orange-500 text-white py-3 px-8 rounded-lg shadow-lg hover:bg-orange-600 transition transform hover:scale-105 duration-200 ease-in-out">See More</a>
    </div>
  </section>
  <!-- Used Books Section -->
  <section class="mx-auto px-6 py-16">
    <h2 class="text-3xl font-extrabold text-gray-800 mb-10">USED BOOKS</h2>    <div class="flex flex-wrap gap-6 justify-left">
      @forelse ($usedBooks as $book)
        <div class="w-36 sm:w-44 cursor-pointer" onclick="openBook('{{ $book->id }}')">
          <img src="{{ asset($book->cover) }}" alt="{{ $book->title }}" class="w-full h-52 sm:h-60 object-cover shadow-md hover:shadow-xl transition duration-300 rounded">
          <h4 class="font-bold mt-3 text-center text-sm sm:text-base text-gray-900 line-clamp-2">{{ $book->title }}</h4>
          <p class="text-gray-600 text-xs sm:text-sm text-center">{{ $book->author }}</p>
        </div>
      @empty
        <p class="w-full text-gray-500 text-center">No books found.</p>
      @endforelse
    </div>
    <div class="mt-12 text-center">
      <a href="{{ route('search') }}?condition=Used" class="bg-orange-500 text-white py-3 px-8 rounded-lg shadow-lg hover:bg-orange-600 transition transform hover:scale-105 duration-200 ease-in-out">See More</a>
    </div>
  </section>

  <!-- Footer -->
  <x-footer />  <script>
    function openBook(bookId) {
      const baseUrl = '{{ url('books') }}';
      window.location.href = baseUrl + '/' + bookId;
    }
  </script>
</body>

</html>
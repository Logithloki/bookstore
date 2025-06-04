<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Book;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SearchBooks extends Component
{
    use WithPagination;

    public $search = '';
    public $category = '';
    public $type = '';
    public $condition = '';
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';

    protected $queryString = [
        'search' => ['except' => ''],
        'category' => ['except' => ''],
        'type' => ['except' => ''],
        'condition' => ['except' => ''],
        'sortBy' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategory()
    {
        $this->resetPage();
    }

    public function updatingType()
    {
        $this->resetPage();
    }

    public function updatingCondition()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function render()
    {
        try {
            // Test database connection first
            DB::connection('mongodb')->getPdo();
            
            $query = Book::query()
                ->with('user')
                ->when($this->search, function ($query) {
                    $query->where(function ($q) {
                        $q->where('title', 'like', '%' . $this->search . '%')
                            ->orWhere('author', 'like', '%' . $this->search . '%')
                            ->orWhere('category', 'like', '%' . $this->search . '%');
                    });
                })
                ->when($this->category, function ($query) {
                    $query->where('category', $this->category);
                })
                ->when($this->type, function ($query) {
                    $query->where('type', $this->type);
                })
                ->when($this->condition, function ($query) {
                    $query->where('condition', $this->condition);
                });

            $books = $query->orderBy($this->sortBy, $this->sortDirection)
                ->paginate(12);

            $categories = Book::distinct()->pluck('category');
            $types = ['Sell', 'Rental', 'Exchange'];
            $conditions = ['New', 'Good', 'Fair', 'Poor'];

            return view('livewire.search-books', [
                'books' => $books,
                'categories' => $categories,
                'types' => $types,
                'conditions' => $conditions,
            ]);
            
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Database connection error in SearchBooks: ' . $e->getMessage());
            
            // Return a view with empty data and error message
            return view('livewire.search-books', [
                'books' => collect(),
                'categories' => collect(),
                'types' => ['Sell', 'Rental', 'Exchange'],
                'conditions' => ['New', 'Good', 'Fair', 'Poor'],
                'error' => 'Database connection failed. Please try again later.',
            ]);
        }
    }
} 
<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Book;
use Livewire\WithPagination;

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
                $query->where('for', $this->type);
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
    }
} 
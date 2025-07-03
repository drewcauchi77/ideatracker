<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Idea;
use App\Models\Status;
use App\Models\Vote;
use Livewire\Component;
use Livewire\WithPagination;

class IdeasIndex extends Component
{
    use WithPagination;

    public $status;
    public $category;
    public $filter;
    public $search;

    protected $queryString = ['status', 'category', 'filter', 'search'];

    protected $listeners = ['queryStringUpdatedStatus'];

    public function mount()
    {
        $this->status = request()->status ?? 'All';
        $this->category = request()->category ?? 'All Categories';
        $this->filter = request()->filter ?? 'No Filter';
    }

    public function updatingCategory() {
        $this->resetPage();
    }

    public function updatingFilter() {
        $this->resetPage();
    }

    public function updatingSearch() {
        $this->resetPage();
    }

    public function updatedFilter() {
        if ($this->filter == 'My Ideas') {
            if (!auth()->check()) {
                return redirect()->route('login');
            }
        }
    }

    public function queryStringUpdatedStatus($newStatus) {
        $this->status = $newStatus;
        $this->resetPage();
    }

    public function render()
    {
        $statuses = Status::all()->pluck('id', 'name');
        $categories = Category::all();

        return view('livewire.ideas-index', [
            'ideas' => Idea::with('user', 'category', 'status')
                ->when($this->status && $this->status !== 'All', function ($q) use ($statuses) {
                    return $q->where('status_id', $statuses->get($this->status));
                })
                ->when($this->category && $this->category !== 'All Categories', function ($q) use ($categories) {
                    return $q->where('category_id', $categories->pluck('id', 'name')->get($this->category));
                })
                ->when($this->filter == 'Top Voted', function ($q) {
                    return $q->orderByDesc('votes_count');
                })
                ->when($this->filter == 'My Ideas', function ($q) {
                    return $q->where('user_id', auth()->id());
                })
                ->when(strlen($this->search) >= 3, function ($q) {
                    return $q->where('title', 'like', '%' . $this->search . '%');
                })
                ->addSelect(['voted_by_user' => Vote::select('id')
                    ->where('user_id', auth()->id())
                    ->whereColumn('idea_id', 'ideas.id')
                ])
                ->withCount('votes')
                ->orderBy('id', 'desc')
                ->paginate(Idea::PAGINATION_COUNT),
            'categories' => $categories,
        ]);
    }
}

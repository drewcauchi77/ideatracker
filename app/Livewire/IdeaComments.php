<?php

namespace App\Livewire;

use App\Models\Idea;
use Livewire\Component;
use Livewire\WithPagination;

class IdeaComments extends Component
{
    use WithPagination;

    public $idea;

    protected $listeners = ['commentWasAdded', 'commentWasDeleted'];
    protected $perPage = 5;

    public function commentWasAdded() {
        $this->idea->refresh();
        $this->gotoPage($this->idea->comments()->paginate($this->perPage)->lastPage());
    }

    public function commentWasDeleted() {
        $this->idea->refresh();
    }

    public function mount(Idea $idea) {
        $this->idea = $idea;
    }

    public function render()
    {
        return view('livewire.idea-comments', [
            'comments' => $this->idea->comments()->with('user')->paginate($this->perPage)->withQueryString(),
            // 'comments' => Comment::with('user')->where('idea_id', $this->idea->id)->paginate()->withQueryString()
        ]);
    }
}

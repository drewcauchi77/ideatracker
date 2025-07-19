<?php

namespace App\Livewire;

use Livewire\Component;

class IdeaComment extends Component
{
    public $comment;
    public $ideaUserId;

    public function mount($comment, $ideaUserId) {
        $this->comment = $comment;
        $this->ideaUserId = $ideaUserId;
    }

    public function render()
    {
        return view('livewire.idea-comment');
    }
}

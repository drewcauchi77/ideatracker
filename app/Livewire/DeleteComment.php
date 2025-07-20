<?php

namespace App\Livewire;

use App\Models\Comment;
use App\Models\Idea;
use Livewire\Component;
use Symfony\Component\HttpFoundation\Response;

class DeleteComment extends Component
{
    public $comment;

    public function mount(Comment $comment) {
        $this->comment = $comment;
    }

    public function deleteComment() {
        if (auth()->guest() || auth()->user()->cannot('delete', $this->comment)) {
            abort(Response::HTTP_FORBIDDEN);
        }

        Comment::destroy($this->comment->id);
        $this->comment = null;
        $this->dispatch('commentWasDeleted', 'Comment has been deleted');
    }

    public function render()
    {
        return view('livewire.delete-comment');
    }
}

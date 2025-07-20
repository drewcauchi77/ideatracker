<?php

namespace App\Livewire;

use App\Models\Comment;
use Livewire\Component;
use Symfony\Component\HttpFoundation\Response;

class MarkCommentAsSpam extends Component
{
    public $comment;

    public function mount(Comment $comment) {
        $this->comment = $comment;
    }

    public function markAsSpam() {
        if (auth()->guest()) {
            abort(Response::HTTP_FORBIDDEN);
        }

        $this->comment->spam_reports++;
        $this->comment->save();

        $this->dispatch('commentWasMarkedAsSpam', 'Comment marked as spam.');
    }

    public function render()
    {
        return view('livewire.mark-comment-as-spam');
    }
}

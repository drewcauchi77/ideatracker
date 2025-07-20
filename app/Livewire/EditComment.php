<?php

namespace App\Livewire;

use App\Models\Comment;
use Livewire\Component;
use Symfony\Component\HttpFoundation\Response;

class EditComment extends Component
{
    public $comment;
    public $body;

    protected $rules = [
        'body' => 'required|min:4',
    ];

    public function mount(Comment $comment) {
        $this->comment = $comment;
        $this->body = $comment->body;
    }

//    Using a modal to open other comments, check AlpineJS to trigger listeners with Livewire
//    public function setEditComment($commentId) {
//        $this->comment = Comment::findOrFail($commentId);
//        dd($this->comment->id);
//    }

    public function updateComment() {
        if (auth()->guest() || auth()->user()->cannot('update', $this->comment)) {
            abort(Response::HTTP_FORBIDDEN);
        }

        $this->validate();

        $this->comment->body = $this->body;
        $this->comment->save();

        $this->dispatch('commentWasUpdated', 'Comment was updated.');
    }

    public function render()
    {
        return view('livewire.edit-comment');
    }
}

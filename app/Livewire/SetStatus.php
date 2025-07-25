<?php

namespace App\Livewire;

use App\Jobs\NotifyAllVoters;
use App\Models\Comment;
use App\Models\Idea;
use App\Models\Status;
use Symfony\Component\HttpFoundation\Response;
use Livewire\Component;

class SetStatus extends Component
{
    public $idea;
    public $status;
    public $comment;
    public $notifyAllVoters;

    public function mount(Idea $idea) {
        $this->idea = $idea;
        $this->status = $this->idea->status_id;
    }

    public function setStatus() {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            abort(Response::HTTP_FORBIDDEN);
        }

        if ($this->idea->status_id == $this->status) {
            $this->dispatch('statusWasUpdatedError', 'Status is the same');
            return;
        }

        $this->idea->status_id = $this->status;
        $this->idea->save();

        if ($this->notifyAllVoters) {
            NotifyAllVoters::dispatch($this->idea);
        }

        Comment::create([
            'user_id' => auth()->id(),
            'idea_id' => $this->idea->id,
            'status_id' => $this->status,
            'body' => $this->comment ?? 'No comment was added',
            'is_status_update' => true,
        ]);

        $this->dispatch('statusWasUpdated');
        $this->reset('comment');
    }

    public function render()
    {
        return view('livewire.set-status', [
            'statuses' => Status::all()
        ]);
    }
}

<?php

namespace App\Livewire;

use App\Jobs\NotifyAllVoters;
use App\Models\Idea;
use App\Models\Status;
use Symfony\Component\HttpFoundation\Response;
use Livewire\Component;

class SetStatus extends Component
{
    public $idea;
    public $status;
    public $notifyAllVoters;

    public function mount(Idea $idea) {
        $this->idea = $idea;
        $this->status = $this->idea->status_id;
    }

    public function setStatus() {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            abort(Response::HTTP_FORBIDDEN);
        }

        $this->idea->status_id = $this->status;
        $this->idea->save();

        if ($this->notifyAllVoters) {
            NotifyAllVoters::dispatch($this->idea);
        }

        $this->dispatch('statusWasUpdated');
    }

    public function render()
    {
        return view('livewire.set-status', [
            'statuses' => Status::all()
        ]);
    }
}

<?php

namespace App\Livewire;

use App\Models\Idea;
use Livewire\Component;
use Symfony\Component\HttpFoundation\Response;

class MarkIdeaAsNotSpam extends Component
{
    public $idea;

    public function mount(Idea $idea) {
        $this->idea = $idea;
    }

    public function markAsNotSpam() {
        if (auth()->guest() || !auth()->user()->isAdmin()) {
            abort(Response::HTTP_FORBIDDEN);
        }

        $this->idea->spam_reports = 0;
        $this->idea->save();

        $this->dispatch('ideaWasMarkedAsNotSpam', 'Spam counter was reset.');
    }

    public function render()
    {
        return view('livewire.mark-idea-as-not-spam');
    }
}

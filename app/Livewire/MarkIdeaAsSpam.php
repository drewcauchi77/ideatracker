<?php

namespace App\Livewire;

use App\Models\Idea;
use Livewire\Component;
use Symfony\Component\HttpFoundation\Response;

class MarkIdeaAsSpam extends Component
{
    public $idea;

    public function mount(Idea $idea) {
        $this->idea = $idea;
    }

    public function markAsSpam() {
        if (auth()->guest()) {
            abort(Response::HTTP_FORBIDDEN);
        }

        $this->idea->spam_reports++;
        $this->idea->save();

        $this->dispatch('ideaWasMarkedAsSpam', 'Idea marked as spam.');
    }

    public function render()
    {
        return view('livewire.mark-idea-as-spam');
    }
}

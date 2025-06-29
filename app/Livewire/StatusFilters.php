<?php

namespace App\Livewire;

use App\Models\Status;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Livewire\Component;

class StatusFilters extends Component
{
    public $status = 'All';
    public $statusCount;

    protected $queryString = ['status'];

    public function mount() {
        $this->statusCount = Status::getCount();

        if (Route::currentRouteName() == 'idea.show') {
            $this->status = null;
            $this->queryString = [];
        }
    }

    private function getPreviousRouteName()
    {
        $previousUrl = URL::previous();
        $previousRoute = app('router')->getRoutes()->match(app('request')->create($previousUrl));
        return $previousRoute->getName();
    }

    public function setStatus($newStatus) {
        $this->status = $newStatus;

//        if ($this->getPreviousRouteName() === 'idea.show') {
            return redirect()->route('idea.index', [
                'status' => $this->status
            ]);
//        }
    }


    public function render()
    {
        return view('livewire.status-filters');
    }
}

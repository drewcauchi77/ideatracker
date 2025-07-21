<?php

namespace App\Livewire;

use Livewire\Component;

class CommentNotifications extends Component
{
    const NOTIFICATION_THRESHOLD = 10;
    public $notificationCount;

    public function mount() {
        $this->getNotificationsCount();
    }

    public function getNotificationsCount() {
        $this->notificationCount = auth()->user()->unreadNotifications()->count();

        if ($this->notificationCount > self::NOTIFICATION_THRESHOLD) {
            $this->notificationCount = self::NOTIFICATION_THRESHOLD . '+';
        }
    }

    public function render()
    {
        return view('livewire.comment-notifications', [
            'notifications' => auth()->user()->unreadNotifications()->latest()->take(self::NOTIFICATION_THRESHOLD)->get()
        ]);
    }
}

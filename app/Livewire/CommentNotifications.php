<?php

namespace App\Livewire;

use App\Models\Comment;
use App\Models\Idea;
use Illuminate\Notifications\DatabaseNotification;
use Livewire\Component;
use Symfony\Component\HttpFoundation\Response;

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

    public function markAsRead($notificationid) {
        if (auth()->guest()) {
            abort(Response::HTTP_FORBIDDEN);
        }

        $notification = DatabaseNotification::findOrFail($notificationid);
        $notification->markAsRead();

        $idea = Idea::find($notification->data['idea_id']);
        $comment = Comment::find($notification->data['comment_id']);

        if (!$comment || !$idea) {
            session()->flash('error_message', 'Comment no longer exists');

            return redirect()->route('idea.index');
        }

        $comments = $idea->comments()->pluck('id');
        $indexOfComment = $comments->search($comment->id);

        $page = (int) ($indexOfComment / $comment->getPerPage()) + 1;

        session()->flash('scrollToComment', $comment->id);

        return redirect()->route('idea.show', [
            'idea' => $notification->data['idea_slug'],
            'page' => $page,
        ]);
    }

    public function markAllAsRead() {
        if (auth()->guest()) {
            abort(Response::HTTP_FORBIDDEN);
        }

        auth()->user()->unreadNotifications->markAsRead();
        $this->getNotificationsCount();
    }

    public function render()
    {
        return view('livewire.comment-notifications', [
            'notifications' => auth()->user()->unreadNotifications()->latest()->take(self::NOTIFICATION_THRESHOLD)->get()
        ]);
    }
}

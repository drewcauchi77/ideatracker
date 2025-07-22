<div wire:poll="getNotificationsCount">
    <strong>BELL</strong>
    @if($notificationCount)
        <span>{{ $notificationCount }}</span><br>
        <ul>
            @foreach($notifications as $notification)
                <li>
                    <a href="{{ route('idea.show', $notification->data['idea_slug']) }}" wire:click.prevent="markAsRead('{{ $notification->id }}')">
                        <img src="{{ $notification->data['user_avatar'] }}" width="30" height="30" />
                        <span>{{ $notification->data['user_name'] }}</span><br>
                        <span>{{ $notification->data['comment_body'] }}</span><br>
                        <span>{{ $notification->created_at->diffForHumans() }}</span><br>
                    </a>
                </li>
            @endforeach
        </ul>
        <button wire:click.prevent="markAllAsRead">Mark All as Read</button>
    @else
        <span>You have no notifications</span>
    @endif
</div>

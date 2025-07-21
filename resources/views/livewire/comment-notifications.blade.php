<div>
    <strong>BELL</strong>
    <ul>
        @foreach($notifications as $notification)
            <li>
                <a href="{{ route('idea.show', $notification->data['idea_slug']) }}">
                    <img src="{{ $notification->data['user_avatar'] }}" width="30" height="30" />
                    <span>{{ $notification->data['user_name'] }}</span><br>
                    <span>{{ $notification->data['comment_body'] }}</span><br>
                    <span>{{ $notification->created_at->diffForHumans() }}</span><br>
                </a>
            </li>
        @endforeach
    </ul>
</div>

<div style="border: 1px solid black; padding: 5px; margin: 10px; background-color: cyan;">
    <span style="display: block;padding-bottom: 10px;">{{ $comment->body }}</span><br>
    <span style="display: block;padding-bottom: 10px;">{{ $comment->created_at->diffForHumans() }}</span><br>
    <span style="display: block;padding-bottom: 10px;">{{ $comment->user->name }}</span><br>
    @if ($comment->user->id == $ideaUserId)
        <strong>OP</strong>
    @endif
    <img height="80" width="80" src="{{ $comment->user->getAvatarAttribute() }}">
    @auth
        @can('update', $comment)
            <span>Can Edit</span>

            <livewire:edit-comment :comment="$comment" />
        @endcan

        @can('delete', $comment)
            <span>Can Delete</span>

            <livewire:delete-comment :comment="$comment" />
        @endcan
    @endauth
</div>

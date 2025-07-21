<div style="border: 1px solid black; padding: 5px; margin: 10px; @if ($comment->is_status_update) background-color: {{ $comment->status->color }}; @else background-color: cyan; @endif">
    @if ($comment->is_status_update)
        <h4 style="font-size: 24px;">Status Changed To '{{ $comment->status->name }}'</h4>
    @endif

    <span style="display: block;padding-bottom: 10px;">{{ $comment->body }}</span><br>
    <span style="display: block;padding-bottom: 10px;">{{ $comment->created_at->diffForHumans() }}</span><br>
    <span style="display: block;padding-bottom: 10px;">{{ $comment->user->name }}</span><br>
    @if ($comment->user->id == $ideaUserId)
        <strong>OP</strong>
    @endif

    <img height="80" width="80" src="{{ $comment->user->getAvatarAttribute() }}">

    @if ($comment->user->isAdmin())
        <strong>ADMIN</strong>
    @endif

    @auth
        @can('update', $comment)
            <span>Can Edit</span>

            <livewire:edit-comment :comment="$comment" />
        @endcan

        @can('delete', $comment)
            <span>Can Delete</span>

            <livewire:delete-comment :comment="$comment" />
        @endcan

        @admin
            @if ($comment->spam_reports > 0)
                <strong style="color: red; font-size: 15px;">Spam Reports {{ $comment->spam_reports }}</strong><br>
            @endif
        @endadmin

        <span>Can Mark as Spam</span>

        <livewire:mark-comment-as-spam :comment="$comment" />
    @endauth

    @if (session('scrollToComment'))
        <x-notification-success :redirect="true" message-to-display="Scroll to {{ session('scrollToComment') }}" />
    @endif
</div>

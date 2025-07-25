<div>
    @forelse($comments as $comment)
        <livewire:idea-comment :comment="$comment" :key="$comment->id" :ideaUserId="$idea->user->id" />
    @empty
        <h3>NO COMMENTS YET</h3>
    @endforelse

    <div>
        {{ $comments->onEachSide(1)->links() }}
    </div>
</div>

<div>
    @forelse($comments as $comment)
        <livewire:idea-comment :comment="$comment" :key="$comment->id" />
    @empty
        <h3>NO COMMENTS YET</h3>
    @endforelse
</div>

<div>
    <h1>Add a new idea</h1>
    @auth
        <livewire:create-idea />
    @else
        <span>Please login to add an idea</span>
        <a href="{{ route('login') }}">Login</a>
    @endauth
</div>

@foreach($ideas as $idea)
    <livewire:idea-show :idea="$idea" :votesCount="$idea->votes_count" />
    <a href="{{ route('idea.show', $idea) }}">Go To Idea</a><br>
@endforeach

<div style="display:flex">
    {{ $ideas->links() }}
</div>

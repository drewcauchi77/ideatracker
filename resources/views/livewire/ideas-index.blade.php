<div>
    <div>
        <h1>Add a new idea</h1>
        @auth
            <livewire:create-idea></livewire:create-idea>
        @else
            <span>Please login to add an idea</span>
            <a href="{{ route('login') }}">Login</a>
        @endauth
    </div>

    @foreach($ideas as $idea)
        <livewire:idea-index :idea="$idea" :votesCount="$idea->votes_count" :key="$idea->id"></livewire:idea-index>
        <br/>
    @endforeach

    <div style="display:flex">
        {{ $ideas->links() }}
    </div>
</div>

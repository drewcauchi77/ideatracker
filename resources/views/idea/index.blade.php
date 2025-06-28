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
    <div>
        <img src="{{ $idea->user->avatar }}" alt="Avatar" />
        <span>{{ $idea->title }}</span><br>
        <p>{{ $idea->description }}</p>
        <span>Category: {{ $idea->category->name }}</span><br>
        <span>Status: <strong style="color: {{ $idea->status->color }};">{{ $idea->status->name }}</strong></span><br>
        <livewire:idea-index :votesCount="$idea->votes_count" /><br/>
        @if($idea->voted_by_user)
            <span style="color: blue;font-size: 18px">!!! You have already voted for this !!!</span><br>
            <button style="background:blue; color: white; font-size:22px; font-weight: 800;cursor:pointer;">
                Voted
            </button>
        @else
            <button style="background:grey; color: black; font-size:22px; font-weight: 800;cursor:pointer;">
                Vote Now
            </button>
        @endif
        <br><span>{{ $idea->user->name }}</span><br>
    </div>
    <a href="{{ route('idea.show', $idea) }}">Go To Idea</a><br>
@endforeach

<div style="display:flex">
    {{ $ideas->links() }}
</div>

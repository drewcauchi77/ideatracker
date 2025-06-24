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
    <img src="{{ $idea->user->avatar }}" alt="Avatar" />
    <h1>{{ $idea->title }}</h1>
    <p>{{ $idea->description }}</p>
    <small>{{ $idea->created_at->diffForHumans() }}</small><br>
    <span>Category: {{ $idea->category->name }}</span><br>
    <span>Status: <strong style="color: {{ $idea->status->color }};">{{ $idea->status->name }}</strong></span><br>
    <a href="{{ route('idea.show', $idea) }}">Go To Idea</a><br>
@endforeach

<div style="display:flex">
    {{ $ideas->links() }}
</div>


<div>
    <h1>IDEA-LIST ITEM</h1>
    <img src="{{ $idea->user->avatar }}" alt="Avatar" />
    <span>{{ $idea->title }}</span><br>
    <p>{{ $idea->description }}</p>
    <span>Category: {{ $idea->category->name }}</span><br>
    <span>Status: <strong style="color: {{ $idea->status->color }};">{{ $idea->status->name }}</strong></span><br>
    <span class="votes-count">Number of Votes: <strong>{{ $votesCount }}</strong></span><br>

    <br><span>{{ $idea->user->name }}</span><br>


    <a href="{{ route('idea.show', $idea) }}">Go To Idea</a><br>
</div>

@livewireScripts

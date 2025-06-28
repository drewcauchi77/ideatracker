<div>
    <img src="{{ $idea->user->avatar }}" alt="Avatar" />
    <span>{{ $idea->title }}</span><br>
    <p>{{ $idea->description }}</p>
    <span>Category: {{ $idea->category->name }}</span><br>
    <span>Status: <strong style="color: {{ $idea->status->color }};">{{ $idea->status->name }}</strong></span><br>
    <livewire:idea-index :votesCount="$votesCount" /><br/>
    @if($hasVoted)
        <span style="color: blue;font-size: 18px">!!! You have already voted for this !!!</span><br>
    @endif
    @if($hasVoted)
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

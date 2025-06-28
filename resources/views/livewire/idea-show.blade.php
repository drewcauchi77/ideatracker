<div>
    <h1>IDEA-SHOW</h1>
    <img src="{{ $idea->user->avatar }}" alt="Avatar" />
    <span>{{ $idea->title }}</span><br>
    <p>{{ $idea->description }}</p>
    <span>Category: {{ $idea->category->name }}</span><br>
    <span>Status: <strong style="color: {{ $idea->status->color }};">{{ $idea->status->name }}</strong></span><br>
    <span class="votes-count">Number of Votes: <strong>{{ $votesCount }}</strong></span><br>
    @if($hasVoted)
        <span style="color: blue;font-size: 18px">!!! You have already voted for this !!!</span><br>
    @endif
    @if($hasVoted)
        <button style="background:blue; color: white; font-size:22px; font-weight: 800;cursor:pointer;" wire:click.prevent="vote">
            Voted
        </button>
    @else
        <button style="background:grey; color: black; font-size:22px; font-weight: 800;cursor:pointer;" wire:click.prevent="vote">
            Vote Now
        </button>
    @endif
    <br><span>{{ $idea->user->name }}</span><br>
</div>

@livewireScripts

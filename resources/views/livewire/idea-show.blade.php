<div>
    <h1>IDEA-SHOW</h1>
    <img src="{{ $idea->user->avatar }}" alt="Avatar" />
    <span>{{ $idea->title }}</span><br>
    @admin
        @if ($idea->spam_reports > 0)
            <strong style="color: red; font-size: 15px;">Spam Reports {{ $idea->spam_reports }}</strong>
        @endif
    @endadmin
    <p>{{ $idea->description }}</p>
    <span>Category: {{ $idea->category->name }}</span><br>
    <span>Status: <strong style="color: {{ $idea->status->color }};">{{ $idea->status->name }}</strong></span><br>

    @admin
        <livewire:set-status :idea="$idea"></livewire:set-status>
    @endadmin

    <span class="votes-count">Number of Votes: <strong>{{ $votesCount }}</strong></span><br>

    @if($hasVoted)
        <span style="color: blue;font-size: 18px">!!! You have already voted for this !!!</span><br>
        <button style="background:blue; color: white; font-size:22px; font-weight: 800;cursor:pointer;" wire:click.prevent="vote">
            Voted
        </button>
    @else
        <button style="background:grey; color: black; font-size:22px; font-weight: 800;cursor:pointer;" wire:click.prevent="vote">
            Vote Now
        </button>
    @endif

    <br><span>{{ $idea->user->name }}</span><br>

    @can('update', $idea)
        <livewire:edit-idea :idea="$idea"></livewire:edit-idea>
    @endcan

    @can('delete', $idea)
        <livewire:delete-idea :idea="$idea"></livewire:delete-idea>
    @endcan

    @auth
        <livewire:mark-idea-as-spam :idea="$idea"></livewire:mark-idea-as-spam>
        @if ($idea->spam_reports > 0)
            <livewire:mark-idea-as-not-spam :idea="$idea"></livewire:mark-idea-as-not-spam>
        @endif
    @endauth
</div>

@livewireScripts

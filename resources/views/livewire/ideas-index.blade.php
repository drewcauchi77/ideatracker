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

    <select name="category" wire:model.live="category">
        <option value="All Categories">All Categories</option>
        @foreach($categories as $category)
            <option value="{{ $category->name }}">{{ $category->name }}</option>
        @endforeach
    </select>

    <select name="category" wire:model.live="filter">
        <option value="No Filter">No Filter</option>
        <option value="Top Voted">Top Voted</option>
        <option value="My Ideas">My Ideas</option>
        @admin
            <option value="Spam Ideas">Spam Ideas</option>
        @endadmin
    </select>

    <input type="search" wire:model.live="search" placeholder="Search...">

    @forelse($ideas as $idea)
        <livewire:idea-index :idea="$idea" :votesCount="$idea->votes_count" :key="$idea->id"></livewire:idea-index>
        <br/>
    @empty
        <div>No ideas were found!</div>
    @endforelse

    <div style="display:flex">
        {{ $ideas->links() }}
    </div>
</div>

@livewireScripts

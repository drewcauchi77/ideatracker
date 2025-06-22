<a href="{{ route('idea.index') }}">Go Back</a>

<h1>Idea</h1>
<img src="{{ $idea->user->avatar }}" alt="Avatar" />
<span>{{ $idea->title }}</span><br>
<p>{{ $idea->description }}</p>
<span>Category: {{ $idea->category->name }}</span><br>
<span>Status: <strong style="color: {{ $idea->status->color }};">{{ $idea->status->name }}</strong></span><br>
<span>{{ $idea->user->name }}</span><br>

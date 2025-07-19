<div style="border: 1px solid black; padding: 5px; margin: 10px; background-color: cyan;">
    <span style="display: block;padding-bottom: 10px;">{{ $comment->body }}</span><br>
    <span style="display: block;padding-bottom: 10px;">{{ $comment->created_at->diffForHumans() }}</span><br>
    <span style="display: block;padding-bottom: 10px;">{{ $comment->user->name }}</span><br>
    <img height="80" width="80" src="{{ $comment->user->getAvatarAttribute() }}">
</div>

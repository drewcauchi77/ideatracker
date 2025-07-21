<x-mail::message>
# A comment was posted on your idea.

{{ $comment->user->name }} commented on your idea:

**{{ $comment->idea->title }}**

Comment: {{ $comment->body }}

<x-mail::button :url="route('idea.show', $comment->idea)">
Button Text
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>

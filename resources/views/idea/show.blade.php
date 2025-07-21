<livewire:status-filters />
<a href="{{ $backUrl }}">Go Back</a>
@auth
    <livewire:comment-notifications />
@endauth
<h1>Idea</h1>
<livewire:idea-show :idea="$idea" :votesCount="$votesCount" />

<livewire:idea-comments :idea="$idea" />

<x-notification-success />

@livewireScripts

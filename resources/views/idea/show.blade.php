<livewire:status-filters />
<a href="{{ $backUrl }}">Go Back</a>

<livewire:comment-notifications />

<h1>Idea</h1>
<livewire:idea-show :idea="$idea" :votesCount="$votesCount" />

<livewire:idea-comments :idea="$idea" />

<x-notification-success />

@livewireScripts

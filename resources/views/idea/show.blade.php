<livewire:status-filters />
<a href="{{ $backUrl }}">Go Back</a>

<h1>Idea</h1>
<livewire:idea-show :idea="$idea" :votesCount="$votesCount" />

@can('update', $idea)
    <livewire:edit-idea :idea="$idea"></livewire:edit-idea>
@endcan

@can('delete', $idea)
    <livewire:delete-idea :idea="$idea"></livewire:delete-idea>
@endcan

@auth
    <livewire:mark-idea-as-spam :idea="$idea"></livewire:mark-idea-as-spam>
@endauth

@admin
    @if ($idea->spam_reports > 0)
        <livewire:mark-idea-as-not-spam :idea="$idea"></livewire:mark-idea-as-not-spam>
    @endif
@endadmin

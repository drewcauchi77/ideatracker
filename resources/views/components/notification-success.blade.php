@props([
    'redirect' => false,
    'messageToDisplay' => '',
    'type' => 'success'
])

<div
    x-cloak
    x-data="{
        isOpen: false,
        messageToDisplay: '{{ $messageToDisplay }}',
        showNotification(message) {
            this.isOpen = true;
            this.messageToDisplay = message;
        },
        init() {
            @if($redirect)
                this.showNotification()
            @else
                Livewire.on('ideaWasUpdated', (message) => {
                    this.showNotification(message);
                })

                Livewire.on('ideaWasMarkedAsSpam', (message) => {
                    this.showNotification(message);
                })

                Livewire.on('ideaWasMarkedAsNotSpam', (message) => {
                    this.showNotification(message);
                })

                Livewire.on('commentWasAdded', (message) => {
                    this.showNotification(message);
                })

                Livewire.on('commentWasUpdated', (message) => {
                    this.showNotification(message);
                })

                Livewire.on('commentWasDeleted', (message) => {
                    this.showNotification(message);
                })

                Livewire.on('commentWasMarkedAsSpam', (message) => {
                    this.showNotification(message);
                })
            @endif
        }
     }"
    x-show="isOpen"
    x-init="init()"
    style="position: absolute; display: flex; background-color: gray; bottom: 10px; right: 10px; margin: auto; height: 60px; padding: 20px; min-width: 240px; border-radius: 8px;">
    @if ($type == 'error')
        <span>ERRORRRRRR</span>
    @endif
    <strong style="flex-grow: 1;" x-text="messageToDisplay"></strong>
    <em style="cursor: pointer;" @click="isOpen = false">Close</em>
</div>

@livewireScripts

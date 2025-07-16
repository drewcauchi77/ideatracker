@props([
    'modalTitle',
    'modalDescription',
    'modalConfirmButtonText',
    'wireClick'
])

<div>
    <h3>{{ $modalTitle }}</h3>
    <p>{{ $modalDescription }}</p>

    <button wire:click="{{ $wireClick }}" style="background-color: red; color: white;">{{ $modalConfirmButtonText }}</button>
</div>

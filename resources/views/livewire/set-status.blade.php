<select wire:model.live="status" wire:change="setStatus">
    @foreach($statuses as $status)
        <option value="{{ $status->id }}">{{ $status->name }}</option>
    @endforeach
</select>

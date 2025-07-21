<div style="border: 1px solid black; padding:10px; margin: 10px;">
    <select wire:model.live="status" wire:change="setStatus">
        @foreach($statuses as $status)
            <option value="{{ $status->id }}">{{ $status->name }}</option>
        @endforeach
    </select> <br>

    <textarea wire:model.defer="comment" placeholder="Enter comment here"></textarea>
    <label>
        <span>Notify All voters</span>
        <input wire:model.live="notifyAllVoters" type="checkbox">
    </label>
</div>

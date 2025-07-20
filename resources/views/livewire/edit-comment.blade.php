<div>
    <h3>Edit Comment</h3>

    <form wire:submit.prevent="updateComment" style="display:flex; flex-direction:column; max-width:500px; grid-gap:30px;">
        <textarea wire:model.defer="body" placeholder="Enter comment here"></textarea>
        @error('body')
            <span style="color:red;">{{ $message }}</span>
        @enderror
        <button type="submit">Update Comment</button>
    </form>
</div>

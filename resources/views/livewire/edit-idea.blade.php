<div>
    <h3>Edit Idea</h3>
    <p>
        You have one hour to edit your idea from the time you created it.
    </p>

    @error('title')
        <span style="color:red;">{{ $message }}</span>
    @enderror

    @error('category')
        <span style="color:red;">{{ $message }}</span>
    @enderror

    @error('description')
        <span style="color:red;">{{ $message }}</span>
    @enderror

    <form wire:submit.prevent="updateIdea" style="display:flex; flex-direction:column; max-width:500px; grid-gap:30px;">
        <input wire:model.defer="title" type="text" />
        <select wire:model.defer="category">
            @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>
        <textarea wire:model.defer="description"></textarea>
        <button type="submit">Update</button>
    </form>
</div>

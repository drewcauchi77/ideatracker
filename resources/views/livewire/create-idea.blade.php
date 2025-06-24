<div style="display:flex; flex-direction:column;">
    <span>Add your new idea here</span>

    @error('title')
        <span style="color:red;">{{ $message }}</span>
    @enderror

    @error('category')
        <span style="color:red;">{{ $message }}</span>
    @enderror


    @error('description')
        <span style="color:red;">{{ $message }}</span>
    @enderror

    <form wire:submit.prevent="createIdea" style="display:flex; flex-direction:column; max-width:500px; grid-gap:30px;">
        <input wire:model.defer="title" type="text" />
        <select wire:model.defer="category">
            @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>
        <textarea wire:model.defer="description"></textarea>
        <button type="submit">Submit</button>
    </form>

    @if (session('success_message'))
        <span style="background-color: green;">{{ session('success_message') }}</span>
    @endif
</div>


@livewireScripts

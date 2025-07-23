<div>
    @auth
        <form wire:submit.prevent="addComment">
            <textarea wire:model="comment" placeholder="Share your thoughts" required></textarea><br>

            @error('comment')
                <span style="color: red;">{{ $message }}</span><br>
            @enderror
            <button type="submit">Submit Comment</button>
        </form>
    @else
        <strong>Login or create account to add a comment</strong>
        <a wire:click.prevent="redirectToLogin" href="{{ route('login')  }}">LOGIN</a>
        <a wire:click.prevent="redirectToRegister" href="{{ route('register')  }}">REGISTER</a>
    @endauth
</div>

@livewireScripts

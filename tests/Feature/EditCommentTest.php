<?php

use App\Livewire\EditComment;
use App\Livewire\EditIdea;
use App\Livewire\IdeaComment;
use App\Livewire\IdeaShow;
use App\Models\Category;
use App\Models\Idea;
use App\Models\User;
use App\Models\Comment;
use Livewire\Livewire;
use Symfony\Component\HttpFoundation\Response;

test('shows edit comment livewire component when user has authorisation', function () {
    $user = User::factory()->create();
    $idea = Idea::factory()->create();

    Comment::factory()->create([
        'idea_id' => $idea->id,
        'user_id' => $user->id,
    ]);

    $this->actingAs($user)
        ->get(route('idea.show', $idea))
        ->assertSeeLivewire('edit-comment');
});

test('does not show edit comment livewire component when user does not have authorisation', function () {
    $user = User::factory()->create();
    $idea = Idea::factory()->create();

    $this->get(route('idea.show', $idea))
        ->assertDontSeeLivewire('edit-comment');
});

test('edit comment form validation works', function () {
    $user = User::factory()->create();
    $idea = Idea::factory()->create();

    $comment = Comment::factory()->create([
        'idea_id' => $idea->id,
        'user_id' => $user->id,
        'body' => 'Comment One'
    ]);

    Livewire::actingAs($user)
        ->test(EditComment::class, [
            'comment' => $comment,
        ])
        ->set('body', '')
        ->call('updateComment')
        ->assertHasErrors(['body'])
        ->assertSee('The body field is required.')
        ->set('body', 'ABC')
        ->call('updateComment')
        ->assertHasErrors(['body'])
        ->assertSee('The body field must be at least 4 characters.');
});

test('editing a comment works when user has authorisation', function () {
    $user = User::factory()->create();
    $idea = Idea::factory()->create();

    $comment = Comment::factory()->create([
        'idea_id' => $idea->id,
        'user_id' => $user->id,
        'body' => 'Comment One'
    ]);

    Livewire::actingAs($user)
        ->test(EditComment::class, [
            'comment' => $comment,
        ])
        ->set('body', 'Updated Comment One')
        ->call('updateComment')
        ->assertDispatched('commentWasUpdated');

    $this->assertEquals('Updated Comment One', Comment::first()->body);
});

test('editing a comment does not work when user does not have authorisation', function () {
    $user = User::factory()->create();
    $idea = Idea::factory()->create();

    $comment = Comment::factory()->create([
        'idea_id' => $idea->id,
        'body' => 'Comment One'
    ]);

    Livewire::actingAs($user)
        ->test(EditComment::class, [
            'comment' => $comment,
        ])
        ->set('body', 'Updated Comment One')
        ->call('updateComment')
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

test('editing a comment shows title when user has authorisation', function () {
    $user = User::factory()->create();
    $idea = Idea::factory()->create();

    $comment = Comment::factory()->create([
        'idea_id' => $idea->id,
        'user_id' => $user->id,
        'body' => 'Comment One'
    ]);

    Livewire::actingAs($user)
        ->test(IdeaComment::class, [
            'comment' => $comment,
            'ideaUserId' => $idea->user_id,
        ])
        ->assertSee('Can Edit');
});

test('editing a comment does not show title when user does not have authorisation', function () {
    $user = User::factory()->create();
    $idea = Idea::factory()->create();

    $comment = Comment::factory()->create([
        'idea_id' => $idea->id,
        'body' => 'Comment One'
    ]);

    Livewire::actingAs($user)
        ->test(IdeaComment::class, [
            'comment' => $comment,
            'ideaUserId' => $idea->user_id,
        ])
        ->assertDontSee('Can Edit');
});

<?php

use App\Livewire\DeleteComment;
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

test('shows delete comment livewire component when user has authorisation', function () {
    $user = User::factory()->create();
    $idea = Idea::factory()->create();

    Comment::factory()->create([
        'idea_id' => $idea->id,
        'user_id' => $user->id,
    ]);

    $this->actingAs($user)
        ->get(route('idea.show', $idea))
        ->assertSeeLivewire('delete-comment');
});

test('does not show delete comment livewire component when user does not have authorisation', function () {
    $user = User::factory()->create();
    $idea = Idea::factory()->create();

    $this->get(route('idea.show', $idea))
        ->assertDontSeeLivewire('delete-comment');
});

test('deleting a comment works when user has authorisation', function () {
    $user = User::factory()->create();
    $idea = Idea::factory()->create();

    $comment = Comment::factory()->create([
        'idea_id' => $idea->id,
        'user_id' => $user->id,
        'body' => 'Comment One'
    ]);

    Livewire::actingAs($user)
        ->test(DeleteComment::class, [
            'comment' => $comment,
        ])
        ->call('deleteComment')
        ->assertDispatched('commentWasDeleted');

    $this->assertEquals(0, Comment::count());
});

test('deleting a comment does not work when user does not have authorisation', function () {
    $user = User::factory()->create();
    $idea = Idea::factory()->create();

    $comment = Comment::factory()->create([
        'idea_id' => $idea->id,
        'body' => 'Comment One'
    ]);

    Livewire::actingAs($user)
        ->test(DeleteComment::class, [
            'comment' => $comment,
        ])
        ->call('deleteComment')
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

test('deleting a comment shows title when user has authorisation', function () {
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
        ->assertSee('Can Delete');
});

test('deleting a comment does not show title when user does not have authorisation', function () {
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
        ->assertDontSee('Can Delete');
});

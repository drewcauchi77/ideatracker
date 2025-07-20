<?php

use App\Livewire\AddComment;
use App\Models\Comment;
use App\Models\Idea;
use App\Models\User;
use Livewire\Livewire;

test('add comment livewire component renders', function () {
    $idea = Idea::factory()->create();

    $response = $this->get(route('idea.show', $idea));
    $response->assertSeeLivewire('add-comment');
});

test('add comment form renders when user is logged in', function () {
    $user = User::factory()->create();
    $idea = Idea::factory()->create();

    $response = $this->actingAs($user)->get(route('idea.show', $idea));
    $response->assertSee('Share your thoughts');
});

test('add comment form does not render when user is logged out', function () {
    $idea = Idea::factory()->create();

    $response = $this->get(route('idea.show', $idea));
    $response->assertSee('Login or create account to add a comment');
});

test('add comment form validation works', function () {
    $user = User::factory()->create();
    $idea = Idea::factory()->create();

    Livewire::actingAs($user)
        ->test(AddComment::class, [
            'idea' => $idea,
        ])
        ->set('comment', '')
        ->call('addComment')
        ->assertHasErrors(['comment'])
        ->set('comment', 'ABC')
        ->call('addComment')
        ->assertHasErrors(['comment']);
});

test('add comment form works', function () {
    $user = User::factory()->create();
    $idea = Idea::factory()->create();

    Livewire::actingAs($user)
        ->test(AddComment::class, [
            'idea' => $idea,
        ])
        ->set('comment', 'Comment Body')
        ->call('addComment')
        ->assertDispatched('commentWasAdded');

    $this->assertEquals(1, Comment::count());
    $this->assertEquals('Comment Body', $idea->comments()->first()->body);
});

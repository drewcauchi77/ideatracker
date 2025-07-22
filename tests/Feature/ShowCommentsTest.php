<?php

use App\Models\Comment;
use App\Models\Idea;
use App\Models\User;

test('idea comments livewire component renders correctly', function () {
    $idea = Idea::factory()->create();
    Comment::factory()->create([
        'idea_id' => $idea->id,
        'body' => 'First comment'
    ]);

    $this->get(route('idea.show', $idea))->assertSeeLivewire('idea-comments');
});

test('idea comment livewire component renders correctly', function () {
    $idea = Idea::factory()->create();
    Comment::factory()->create([
        'idea_id' => $idea->id,
        'body' => 'First comment'
    ]);

    $this->get(route('idea.show', $idea))->assertSeeLivewire('idea-comment');
});

test('no comments shows appropriate message', function () {
    $idea = Idea::factory()->create();

    $this->get(route('idea.show', $idea))->assertSee('NO COMMENTS YET');
});

test('list of comments shows on idea page', function () {
    $idea = Idea::factory()->create();

    Comment::factory()->create([
        'idea_id' => $idea->id,
        'body' => 'First comment'
    ]);
    Comment::factory()->create([
        'idea_id' => $idea->id,
        'body' => 'Second comment'
    ]);

    $this->get(route('idea.show', $idea))
        ->assertSeeInOrder(['First comment', 'Second comment'])
        ->assertSeeText('Number of Comments: 2');
});

test('comments count shows correctly on idea index page', function () {
    $idea = Idea::factory()->create();

    Comment::factory()->create([
        'idea_id' => $idea->id,
        'body' => 'First comment'
    ]);
    Comment::factory()->create([
        'idea_id' => $idea->id,
        'body' => 'Second comment'
    ]);

    $this->get(route('idea.index', $idea))
        ->assertSeeText('Number of Comments: 2');
});

test('op badge shows if author of idea comments on idea', function () {
    $user = User::factory()->create();
    $idea = Idea::factory()->create([
        'user_id' => $user->id,
    ]);

    Comment::factory()->create([
        'idea_id' => $idea->id,
        'body' => 'First comment'
    ]);
    Comment::factory()->create([
        'user_id' => $user->id,
        'idea_id' => $idea->id,
        'body' => 'Second comment'
    ]);

    $this->get(route('idea.show', $idea))
        ->assertSee('OP');
});

test('comments pagination works', function () {
    $idea = Idea::factory()->create();

    $commentOne = Comment::factory()->create([
        'idea_id' => $idea->id,
    ]);

    Comment::factory($commentOne->getPerPage())->create([
        'idea_id' => $idea->id,
    ]);

    $response = $this->get(route('idea.show', $idea));

    $response->assertSee($commentOne->body);
    $response->assertDontSee(Comment::find(Comment::count())->body);

    $response = $this->get(route('idea.show', [
        'idea' => $idea,
        'page' => 2
    ]));

    $response->assertDontSee($commentOne->body);
    $response->assertSee(Comment::find(Comment::count())->body);
});

<?php

use App\Livewire\DeleteIdea;
use App\Livewire\EditIdea;
use App\Livewire\IdeaShow;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Idea;
use App\Models\User;
use App\Models\Vote;
use Livewire\Livewire;
use Symfony\Component\HttpFoundation\Response;

test('shows delete idea livewire component when user has authorisation', function () {
    $user = User::factory()->create();
    $idea = Idea::factory()->create([
        'user_id' => $user->id,
    ]);

    $this->actingAs($user)
        ->get(route('idea.show', $idea))
        ->assertSeeLivewire('delete-idea');
});

test('does not show delete idea livewire component when user does not have authorisation', function () {
    $user = User::factory()->create();
    $idea = Idea::factory()->create();

    $this->actingAs($user)
        ->get(route('idea.show', $idea))
        ->assertDontSeeLivewire('delete-idea');
});

test('deleting an idea works when user has authorisation', function () {
    $user = User::factory()->create();

    $idea = Idea::factory()->create([
        'user_id' => $user->id
    ]);

    Livewire::actingAs($user)
        ->test(DeleteIdea::class, [
            'idea' => $idea,
        ])
        ->call('deleteIdea')
        ->assertRedirect(route('idea.index'));

    $this->assertEquals(Idea::count(), 0);
});

test('deleting an idea with votes works when user has authorisation', function () {
    $user = User::factory()->create();

    $idea = Idea::factory()->create([
        'user_id' => $user->id
    ]);

    Vote::factory()->create([
        'idea_id' => $idea->id,
        'user_id' => $user->id,
    ]);

    Livewire::actingAs($user)
        ->test(DeleteIdea::class, [
            'idea' => $idea,
        ])
        ->call('deleteIdea')
        ->assertRedirect(route('idea.index'));

    $this->assertEquals(Idea::count(), 0);
    $this->assertEquals(Vote::count(), 0);
});

test('deleting an idea with comments works when user has authorisation', function () {
    $user = User::factory()->create();

    $idea = Idea::factory()->create([
        'user_id' => $user->id
    ]);

    Comment::factory(1)->create([
        'idea_id' => $idea->id,
    ]);

    Livewire::actingAs($user)
        ->test(DeleteIdea::class, [
            'idea' => $idea,
        ])
        ->call('deleteIdea')
        ->assertRedirect(route('idea.index'));

    $this->assertEquals(Idea::count(), 0);
    $this->assertEquals(Comment::count(), 0);
});

test('deleting an idea works when user is admin', function () {
    $user = User::factory()->create([
        'email' => 'cauchi1020@gmail.com'
    ]);

    $idea = Idea::factory()->create();

    Livewire::actingAs($user)
        ->test(DeleteIdea::class, [
            'idea' => $idea,
        ])
        ->call('deleteIdea')
        ->assertRedirect(route('idea.index'));

    $this->assertEquals(Idea::count(), 0);
});

test('deleting an idea does not work when user does not have authorisation because different user created the idea', function () {
    $userA = User::factory()->create();
    $userB = User::factory()->create();

    $idea = Idea::factory()->create([
        'user_id' => $userA->id
    ]);

    Livewire::actingAs($userB)
        ->test(DeleteIdea::class, [
            'idea' => $idea,
        ])
        ->call('deleteIdea')
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

test('deleting an idea shows title when user has authorisation', function () {
    $user = User::factory()->create();
    $idea = Idea::factory()->create([
        'user_id' => $user->id,
    ]);

    Livewire::actingAs($user)
        ->test(IdeaShow::class, [
            'idea' => $idea,
            'votesCount' => 4
        ])
        ->assertSee('Delete Idea');
});

test('deleting an idea does not show title when user does not have authorisation', function () {
    $user = User::factory()->create();
    $idea = Idea::factory()->create();

    Livewire::actingAs($user)
        ->test(IdeaShow::class, [
            'idea' => $idea,
            'votesCount' => 4
        ])
        ->assertDontSee('Delete Idea');
});

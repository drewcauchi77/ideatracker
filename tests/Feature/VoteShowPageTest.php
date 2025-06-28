<?php

use App\Models\User;
use App\Models\Category;
use App\Models\Status;
use App\Models\Idea;
use App\Models\Vote;
use Livewire\Livewire;
use App\Livewire\IdeaShow;

test('show page contains idea show livewire component', function () {
    $user = User::factory()->create();
    $categoryOne = Category::factory()->create([ 'name' => 'Category One' ]);
    $statusOpen = Status::factory()->create([ 'name' => 'Open', 'color' => 'green' ]);

    $idea = Idea::factory()->create([
        'user_id' => $user->id,
        'title' => 'Idea 1',
        'category_id' => $categoryOne->id,
        'status_id' => $statusOpen->id,
        'description' => 'Idea 1 description',
    ]);

    $this->get(route('idea.show', $idea))->assertSeeLivewire('idea-show');
});

test('show page correctly receives votes count', function () {
    $userA = User::factory()->create();
    $userB = User::factory()->create();

    $categoryOne = Category::factory()->create([ 'name' => 'Category One' ]);
    $statusOpen = Status::factory()->create([ 'name' => 'Open', 'color' => 'green' ]);

    $idea = Idea::factory()->create([
        'user_id' => $userA->id,
        'title' => 'Idea 1',
        'category_id' => $categoryOne->id,
        'status_id' => $statusOpen->id,
        'description' => 'Idea 1 description',
    ]);

    Vote::factory()->create([
        'idea_id' => $idea->id,
        'user_id' => $userA->id,
    ]);

    Vote::factory()->create([
        'idea_id' => $idea->id,
        'user_id' => $userB->id,
    ]);

    $this->get(route('idea.show', $idea))->assertViewHas('votesCount', 2);
});

test('votes count shows correctly on idea show page livewire component', function () {
    $user = User::factory()->create();
    $categoryOne = Category::factory()->create([ 'name' => 'Category One' ]);
    $statusOpen = Status::factory()->create([ 'name' => 'Open', 'color' => 'green' ]);

    $idea = Idea::factory()->create([
        'user_id' => $user->id,
        'title' => 'Idea 1',
        'category_id' => $categoryOne->id,
        'status_id' => $statusOpen->id,
        'description' => 'Idea 1 description',
    ]);

    Livewire::test(IdeaShow::class, [
        'idea' => $idea,
        'votesCount' => 5,
    ])->assertSet('votesCount', 5)
    ->assertSeeHtml('<span class="votes-count">Number of Votes: <strong>5</strong></span>');
});

test('user who is logged in shows voted if idea already voted for', function () {
    $user = User::factory()->create();
    $categoryOne = Category::factory()->create([ 'name' => 'Category One' ]);
    $statusOpen = Status::factory()->create([ 'name' => 'Open', 'color' => 'green' ]);

    $idea = Idea::factory()->create([
        'user_id' => $user->id,
        'title' => 'Idea 1',
        'category_id' => $categoryOne->id,
        'status_id' => $statusOpen->id,
        'description' => 'Idea 1 description',
    ]);

    Vote::factory()->create([
        'idea_id' => $idea->id,
        'user_id' => $user->id,
    ]);

    Livewire::actingAs($user)
        ->test(IdeaShow::class, [
            'idea' => $idea,
            'votesCount' => 5,
        ])->assertSet('hasVoted', true)
        ->assertSee('Voted');
});

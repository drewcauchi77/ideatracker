<?php

use App\Models\User;
use App\Models\Category;
use App\Models\Status;
use App\Models\Idea;
use App\Models\Vote;
use App\Exceptions\DuplicateVoteException;
use App\Exceptions\VoteNotFoundException;

test('can check if idea is voted for by user', function () {
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

    $this->assertTrue($idea->isVotedByUser($userA));
    $this->assertFalse($idea->isVotedByUser($userB));
    $this->assertFalse($idea->isVotedByUser(null));
});

test('user can vote for idea', function () {
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

    $this->assertFalse($idea->isVotedByUser($user));
    $idea->vote($user);
    $this->assertTrue($idea->isVotedByUser($user));
});

test('user can unvote for idea', function () {
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

    $this->assertTrue($idea->isVotedByUser($user));
    $idea->removeVote($user);
    $this->assertFalse($idea->isVotedByUser($user));
});

test('voting for idea that already is voted for throws an exception', function () {
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

    $this->expectException(DuplicateVoteException::class);
    $idea->vote($user);
});

test('removing a vote that does not exist throws an exception', function () {
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

    $this->expectException(VoteNotFoundException::class);
    $idea->removeVote($user);
});

<?php

use App\Jobs\NotifyAllVoters;
use App\Livewire\SetStatus;
use App\Models\Category;
use App\Models\Idea;
use App\Models\Status;
use App\Models\User;
use Illuminate\Support\Facades\Queue;
use Livewire\Livewire;

test('show page contains set status livewire component when user is admin', function () {
    $userA = User::factory()->create([
        'email' => 'cauchi1020@gmail.com'
    ]);

    $categoryOne = Category::factory()->create([ 'name' => 'Category One' ]);
    $statusOpen = Status::factory()->create([ 'name' => 'Open' ]);

    $ideaOne = Idea::factory()->create([
        'user_id' => $userA->id,
        'category_id' => $categoryOne->id,
        'status_id' => $statusOpen->id,
        'title' => 'My First Idea'
    ]);

    $this->actingAs($userA)
        ->get(route('idea.show', $ideaOne))
        ->assertSeeLivewire('set-status');
});

test('show page does not contain set status livewire component when user is not admin', function () {
    $userA = User::factory()->create([
        'email' => 'userz@gmail.com'
    ]);

    $categoryOne = Category::factory()->create([ 'name' => 'Category One' ]);
    $statusOpen = Status::factory()->create([ 'name' => 'Open' ]);

    $ideaOne = Idea::factory()->create([
        'user_id' => $userA->id,
        'category_id' => $categoryOne->id,
        'status_id' => $statusOpen->id,
        'title' => 'My First Idea'
    ]);

    $this->actingAs($userA)
        ->get(route('idea.show', $ideaOne))
        ->assertDontSeeLivewire('set-status');
});

test('initial status is set correctly', function() {
    $userA = User::factory()->create([
        'email' => 'cauchi1020@gmail.com'
    ]);

    $categoryOne = Category::factory()->create([ 'name' => 'Category One' ]);
    $statusConsidering = Status::factory()->create([ 'name' => 'Considering' ]);

    $ideaOne = Idea::factory()->create([
        'user_id' => $userA->id,
        'category_id' => $categoryOne->id,
        'status_id' => $statusConsidering->id,
        'title' => 'My First Idea'
    ]);

    Livewire::actingAs($userA)
        ->test(SetStatus::class, [
            'idea' => $ideaOne,
        ])
        ->assertSet('status', $statusConsidering->id);
});

test('can set status correctly', function() {
    $userA = User::factory()->create([
        'email' => 'cauchi1020@gmail.com'
    ]);

    $categoryOne = Category::factory()->create([ 'name' => 'Category One' ]);
    $statusConsidering = Status::factory()->create([ 'name' => 'Considering', 'id' => 2 ]);
    $statusInProgress = Status::factory()->create([ 'name' => 'Considering', 'id' => 3 ]);

    $ideaOne = Idea::factory()->create([
        'user_id' => $userA->id,
        'category_id' => $categoryOne->id,
        'status_id' => $statusConsidering->id,
        'title' => 'My First Idea'
    ]);

    Livewire::actingAs($userA)
        ->test(SetStatus::class, [
            'idea' => $ideaOne,
        ])
        ->set('status', $statusInProgress->id)
        ->call('setStatus')
        ->assertDispatched('statusWasUpdated');

    $this->assertDatabaseHas('ideas', [
        'id' => $ideaOne->id,
        'status_id' => $statusInProgress->id,
    ]);
});

test('can set status correctly while notifying all voters', function() {
    $userA = User::factory()->create([
        'email' => 'cauchi1020@gmail.com'
    ]);

    $categoryOne = Category::factory()->create([ 'name' => 'Category One' ]);
    $statusConsidering = Status::factory()->create([ 'name' => 'Considering', 'id' => 2 ]);
    $statusInProgress = Status::factory()->create([ 'name' => 'Considering', 'id' => 3 ]);

    $ideaOne = Idea::factory()->create([
        'user_id' => $userA->id,
        'category_id' => $categoryOne->id,
        'status_id' => $statusConsidering->id,
        'title' => 'My First Idea'
    ]);

    Queue::fake();
    Queue::assertNothingPushed();

    Livewire::actingAs($userA)
        ->test(SetStatus::class, [
            'idea' => $ideaOne,
        ])
        ->set('status', $statusInProgress->id)
        ->set('notifyAllVoters', true)
        ->call('setStatus')
        ->assertDispatched('statusWasUpdated');

    Queue::assertPushed(NotifyAllVoters::class);
});

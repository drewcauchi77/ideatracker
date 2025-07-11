<?php

use App\Livewire\IdeasIndex;
use App\Livewire\StatusFilters;
use App\Models\Category;
use App\Models\Idea;
use App\Models\Status;
use App\Models\User;
use Livewire\Livewire;

test('index page contains status filters livewire component', function () {
    $user = User::factory()->create();
    $categoryOne = Category::factory()->create([ 'name' => 'Category One' ]);
    $statusOpen = Status::factory()->create([ 'name' => 'Open' ]);

    $idea = Idea::factory()->create([
        'user_id' => $user->id,
        'category_id' => $categoryOne->id,
        'status_id' => $statusOpen->id,
    ]);

    $this->get(route('idea.index'))->assertSeeLivewire('status-filters');
});

test('show page contains status filters livewire component', function () {
    $user = User::factory()->create();
    $categoryOne = Category::factory()->create([ 'name' => 'Category One' ]);
    $statusOpen = Status::factory()->create([ 'name' => 'Open' ]);

    $idea = Idea::factory()->create([
        'user_id' => $user->id,
        'category_id' => $categoryOne->id,
        'status_id' => $statusOpen->id,
    ]);

    $this->get(route('idea.show', $idea))->assertSeeLivewire('status-filters');
});

test('shows correct status count', function () {
    $user = User::factory()->create();
    $categoryOne = Category::factory()->create([ 'name' => 'Category One' ]);
    $statusImplemented = Status::factory()->create([
        'id' => 4,
        'name' => 'Implemented'
    ]);

    Idea::factory()->create([
        'user_id' => $user->id,
        'category_id' => $categoryOne->id,
        'status_id' => $statusImplemented->id,
    ]);

    Idea::factory()->create([
        'user_id' => $user->id,
        'category_id' => $categoryOne->id,
        'status_id' => $statusImplemented->id,
    ]);

    Livewire::test(StatusFilters::class)
        ->assertSee('All (2)')
        ->assertSee('Implemented (2)');
});

test('filtering works when query string in place', function () {
    $user = User::factory()->create();
    $categoryOne = Category::factory()->create([ 'name' => 'Category One' ]);

    Status::factory()->create([ 'name' => 'Open', 'color' => 'red' ]);
    Status::factory()->create([ 'name' => 'Considering', 'color' => 'purple' ]);
    Status::factory()->create([ 'name' => 'In Progress', 'color' => 'green' ]);
    Status::factory()->create([ 'name' => 'Implemented', 'color' => 'blue' ]);
    Status::factory()->create([ 'name' => 'Closed', 'color' => 'yellow' ]);

    $statusCounts = [0, 0, 0, 0, 0];

    for ($i = 0; $i < count($statusCounts); $i++) {
        $statusCounts[$i] = $statusCounts[$i] + 1;

        Idea::factory()->create([
            'user_id' => $user->id,
            'category_id' => $categoryOne->id,
            'status_id' => $i + 1,
        ]);
    }

    for ($i = 0; $i < 10; $i++) {
        $selectedStatus = rand(1, 5);
        $statusCounts[$selectedStatus - 1] = $statusCounts[$selectedStatus - 1] + 1;

        Idea::factory()->create([
            'user_id' => $user->id,
            'category_id' => $categoryOne->id,
            'status_id' => $selectedStatus,
        ]);
    }

    Livewire::withQueryParams([
        'status' => 'In Progress'
    ])->test(IdeasIndex::class)
        ->assertViewHas('ideas', function ($ideas) use ($statusCounts) {
            return $ideas->count() == $statusCounts[2] && $ideas->first()->status->name == 'In Progress';
        });
});

test('show page does not show selected status', function () {
    $user = User::factory()->create();
    $categoryOne = Category::factory()->create([ 'name' => 'Category One' ]);
    $status = Status::factory()->create([
        'id' => 4,
        'name' => 'Implemented'
    ]);

    $idea = Idea::factory()->create([
        'user_id' => $user->id,
        'category_id' => $categoryOne->id,
        'status_id' => $status->id,
    ]);

    $response = $this->get(route('idea.show', $idea));
    $response->assertDontSee('color:blue; text-decoration: underline;');
});

test('show page shows selected status', function () {
    $user = User::factory()->create();
    $categoryOne = Category::factory()->create([ 'name' => 'Category One' ]);
    $status = Status::factory()->create([
        'id' => 4,
        'name' => 'Implemented'
    ]);

    Idea::factory()->create([
        'user_id' => $user->id,
        'category_id' => $categoryOne->id,
        'status_id' => $status->id,
    ]);

    $response = $this->get(route('idea.index'));
    $response->assertSee('color:blue; text-decoration: underline;');
});

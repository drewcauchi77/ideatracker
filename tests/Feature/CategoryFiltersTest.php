<?php

use App\Livewire\IdeasIndex;
use App\Models\Category;
use App\Models\Idea;
use App\Models\Status;
use App\Models\User;
use Livewire\Livewire;

test('selecting a category filter correctly', function () {
    $categoryOne = Category::factory()->create([ 'name' => 'Category One' ]);
    $categoryTwo = Category::factory()->create([ 'name' => 'Category Two' ]);

    Idea::factory()->create([
        'category_id' => $categoryOne->id,
    ]);

    Idea::factory()->create([
        'category_id' => $categoryTwo->id,
    ]);

    Idea::factory()->create([
        'category_id' => $categoryOne->id,
    ]);

    Livewire::test(IdeasIndex::class)
        ->set('category', 'Category One')
        ->assertViewHas('ideas', function ($ideas) {
            return $ideas->count() == 2 && $ideas->first()->category->name == 'Category One';
        });
});

test('the category query string filters correctly', function () {
    $categoryOne = Category::factory()->create([ 'name' => 'Category One' ]);
    $categoryTwo = Category::factory()->create([ 'name' => 'Category Two' ]);

    $statusOpen = Status::factory()->create([ 'name' => 'Open', 'color' => 'green' ]);

    Idea::factory()->create([
        'user_id' => User::factory()->create()->id,
        'title' => 'Idea 1',
        'category_id' => $categoryOne->id,
        'status_id' => $statusOpen->id,
        'description' => 'Idea 1 description',
    ]);

    Idea::factory()->create([
        'user_id' => User::factory()->create()->id,
        'title' => 'Idea 2',
        'category_id' => $categoryTwo->id,
        'status_id' => $statusOpen->id,
        'description' => 'Idea 2 description',
    ]);

    Idea::factory()->create([
        'user_id' => User::factory()->create()->id,
        'title' => 'Idea 3',
        'category_id' => $categoryOne->id,
        'status_id' => $statusOpen->id,
        'description' => 'Idea 3 description',
    ]);

    Livewire::withQueryParams(['category' => 'Category One'])
        ->test(IdeasIndex::class)
        ->assertViewHas('ideas', function ($ideas) {
            return $ideas->count() == 2 && $ideas->first()->category->name == 'Category One';
        });
});

test('selecting a status and a category filters correctly', function () {
    $categoryOne = Category::factory()->create([ 'name' => 'Category One' ]);
    $categoryTwo = Category::factory()->create([ 'name' => 'Category Two' ]);

    $statusOpen = Status::factory()->create([ 'name' => 'Open', 'color' => 'green' ]);
    $statusConsidering = Status::factory()->create([ 'name' => 'Considering', 'color' => 'purple' ]);

    Idea::factory()->create([
        'user_id' => User::factory()->create()->id,
        'title' => 'Idea 1',
        'category_id' => $categoryOne->id,
        'status_id' => $statusOpen->id,
        'description' => 'Idea 1 description',
    ]);

    Idea::factory()->create([
        'user_id' => User::factory()->create()->id,
        'title' => 'Idea 2',
        'category_id' => $categoryTwo->id,
        'status_id' => $statusOpen->id,
        'description' => 'Idea 2 description',
    ]);

    Idea::factory()->create([
        'user_id' => User::factory()->create()->id,
        'title' => 'Idea 3',
        'category_id' => $categoryTwo->id,
        'status_id' => $statusConsidering->id,
        'description' => 'Idea 3 description',
    ]);

    Idea::factory()->create([
        'user_id' => User::factory()->create()->id,
        'title' => 'Idea 4',
        'category_id' => $categoryOne->id,
        'status_id' => $statusConsidering->id,
        'description' => 'Idea 4 description',
    ]);

    Livewire::test(IdeasIndex::class)
        ->set('status', 'Open')
        ->set('category', 'Category One')
        ->assertViewHas('ideas', function ($ideas) {
            return $ideas->count() == 1 && $ideas->first()->category->name == 'Category One' && $ideas->first()->status->name == 'Open';
        });
});

test('the category query string filters correctly with status', function () {
    $categoryOne = Category::factory()->create([ 'name' => 'Category One' ]);
    $categoryTwo = Category::factory()->create([ 'name' => 'Category Two' ]);

    $statusOpen = Status::factory()->create([ 'name' => 'Open', 'color' => 'green' ]);
    $statusConsidering = Status::factory()->create([ 'name' => 'Considering', 'color' => 'purple' ]);

    Idea::factory()->create([
        'user_id' => User::factory()->create()->id,
        'title' => 'Idea 1',
        'category_id' => $categoryOne->id,
        'status_id' => $statusOpen->id,
        'description' => 'Idea 1 description',
    ]);

    Idea::factory()->create([
        'user_id' => User::factory()->create()->id,
        'title' => 'Idea 2',
        'category_id' => $categoryTwo->id,
        'status_id' => $statusOpen->id,
        'description' => 'Idea 2 description',
    ]);

    Idea::factory()->create([
        'user_id' => User::factory()->create()->id,
        'title' => 'Idea 3',
        'category_id' => $categoryTwo->id,
        'status_id' => $statusConsidering->id,
        'description' => 'Idea 3 description',
    ]);

    Idea::factory()->create([
        'user_id' => User::factory()->create()->id,
        'title' => 'Idea 4',
        'category_id' => $categoryOne->id,
        'status_id' => $statusConsidering->id,
        'description' => 'Idea 4 description',
    ]);

    Livewire::withQueryParams([
        'category' => 'Category One',
        'status' => 'Open'
    ])->test(IdeasIndex::class)
        ->assertViewHas('ideas', function ($ideas) {
            return $ideas->count() == 1 && $ideas->first()->category->name == 'Category One' && $ideas->first()->status->name == 'Open';
        });
});

test('selecting all categories filters correctly', function () {
    $categoryOne = Category::factory()->create([ 'name' => 'Category One' ]);
    $categoryTwo = Category::factory()->create([ 'name' => 'Category Two' ]);

    $statusOpen = Status::factory()->create([ 'name' => 'Open', 'color' => 'green' ]);
    $statusConsidering = Status::factory()->create([ 'name' => 'Considering', 'color' => 'purple' ]);

    Idea::factory()->create([
        'user_id' => User::factory()->create()->id,
        'title' => 'Idea 1',
        'category_id' => $categoryOne->id,
        'status_id' => $statusOpen->id,
        'description' => 'Idea 1 description',
    ]);

    Idea::factory()->create([
        'user_id' => User::factory()->create()->id,
        'title' => 'Idea 2',
        'category_id' => $categoryTwo->id,
        'status_id' => $statusOpen->id,
        'description' => 'Idea 2 description',
    ]);

    Idea::factory()->create([
        'user_id' => User::factory()->create()->id,
        'title' => 'Idea 3',
        'category_id' => $categoryTwo->id,
        'status_id' => $statusConsidering->id,
        'description' => 'Idea 3 description',
    ]);

    Idea::factory()->create([
        'user_id' => User::factory()->create()->id,
        'title' => 'Idea 4',
        'category_id' => $categoryOne->id,
        'status_id' => $statusConsidering->id,
        'description' => 'Idea 4 description',
    ]);

    Livewire::test(IdeasIndex::class)
        ->set('category', 'All Categories')
        ->assertViewHas('ideas', function ($ideas) {
            return $ideas->count() == 4;
        });
});

<?php
use App\Livewire\IdeasIndex;
use App\Models\Category;
use App\Models\Idea;
use App\Models\Status;
use App\Models\User;
use App\Models\Vote;
use Livewire\Livewire;

test('top voted filter works', function () {
    $userA = User::factory()->create();
    $userB = User::factory()->create();

    $categoryOne = Category::factory()->create([ 'name' => 'Category One' ]);
    $statusOpen = Status::factory()->create([ 'name' => 'Open' ]);

    $ideaOne = Idea::factory()->create([
        'user_id' => $userA->id,
        'category_id' => $categoryOne->id,
        'status_id' => $statusOpen->id,
        'title' => 'My First Idea'
    ]);

    $ideaTwo = Idea::factory()->create([
        'user_id' => $userB->id,
        'category_id' => $categoryOne->id,
        'status_id' => $statusOpen->id,
        'title' => 'My Second Idea'
    ]);

    $ideaThree = Idea::factory()->create([
        'user_id' => $userB->id,
        'category_id' => $categoryOne->id,
        'status_id' => $statusOpen->id,
        'title' => 'My Third Idea'
    ]);

    Livewire::test(IdeasIndex::class)
        ->set('search', 'second')
        ->assertViewHas('ideas', function ($ideas) {
            return $ideas->count() == 1 && $ideas->first()->title == 'My Second Idea';
        });
});

test('does not perform search if less than 3 characters', function () {
    $userA = User::factory()->create();
    $userB = User::factory()->create();

    $categoryOne = Category::factory()->create([ 'name' => 'Category One' ]);
    $statusOpen = Status::factory()->create([ 'name' => 'Open' ]);

    $ideaOne = Idea::factory()->create([
        'user_id' => $userA->id,
        'category_id' => $categoryOne->id,
        'status_id' => $statusOpen->id,
        'title' => 'My First Idea'
    ]);

    $ideaTwo = Idea::factory()->create([
        'user_id' => $userB->id,
        'category_id' => $categoryOne->id,
        'status_id' => $statusOpen->id,
        'title' => 'My Second Idea'
    ]);

    $ideaThree = Idea::factory()->create([
        'user_id' => $userB->id,
        'category_id' => $categoryOne->id,
        'status_id' => $statusOpen->id,
        'title' => 'My Third Idea'
    ]);

    Livewire::test(IdeasIndex::class)
        ->set('search', 'ab')
        ->assertViewHas('ideas', function ($ideas) {
            return $ideas->count() == 3;
        });
});

test('search works correctly with category filter', function () {
    $userA = User::factory()->create();
    $userB = User::factory()->create();

    $categoryOne = Category::factory()->create([ 'name' => 'Category One' ]);
    $categoryTwo = Category::factory()->create([ 'name' => 'Category Two' ]);

    $statusOpen = Status::factory()->create([ 'name' => 'Open' ]);

    $ideaOne = Idea::factory()->create([
        'user_id' => $userA->id,
        'category_id' => $categoryOne->id,
        'status_id' => $statusOpen->id,
        'title' => 'My First Idea'
    ]);

    $ideaTwo = Idea::factory()->create([
        'user_id' => $userB->id,
        'category_id' => $categoryOne->id,
        'status_id' => $statusOpen->id,
        'title' => 'My Second Idea'
    ]);

    $ideaThree = Idea::factory()->create([
        'user_id' => $userB->id,
        'category_id' => $categoryTwo->id,
        'status_id' => $statusOpen->id,
        'title' => 'My Third Idea'
    ]);

    Livewire::test(IdeasIndex::class)
        ->set('search', 'idea')
        ->set('category', 'Category One')
        ->assertViewHas('ideas', function ($ideas) {
            return $ideas->count() == 2;
        });
});

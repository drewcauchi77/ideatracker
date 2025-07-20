<?php

use App\Livewire\IdeasIndex;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Idea;
use App\Models\Status;
use App\Models\User;
use App\Models\Vote;
use Livewire\Livewire;

test('top voted filter works', function () {
    $userA = User::factory()->create();
    $userB = User::factory()->create();
    $userC = User::factory()->create();

    $categoryOne = Category::factory()->create([ 'name' => 'Category One' ]);
    $statusOpen = Status::factory()->create([ 'name' => 'Open' ]);

    $ideaOne = Idea::factory()->create([
        'user_id' => $userA->id,
        'category_id' => $categoryOne->id,
        'status_id' => $statusOpen->id,
    ]);

    $ideaTwo = Idea::factory()->create([
        'user_id' => $userB->id,
        'category_id' => $categoryOne->id,
        'status_id' => $statusOpen->id,
    ]);

    Vote::factory()->create([
       'idea_id' => $ideaOne->id,
        'user_id' => $userA->id,
    ]);

    Vote::factory()->create([
        'idea_id' => $ideaOne->id,
        'user_id' => $userB->id,
    ]);

    Vote::factory()->create([
        'idea_id' => $ideaTwo->id,
        'user_id' => $userC->id,
    ]);

    Livewire::test(IdeasIndex::class)
        ->set('filter', 'Top Voted')
        ->assertViewHas('ideas', function ($ideas) {
            return $ideas->count() == 2 && $ideas->first()->votes->count() == 2 && $ideas->get(1)->votes->count() == 1;
        });
});

test('my ideas filter works correctly when logged in', function () {
    $userA = User::factory()->create();
    $userB = User::factory()->create();

    $categoryOne = Category::factory()->create([ 'name' => 'Category One' ]);
    $statusOpen = Status::factory()->create([ 'name' => 'Open' ]);

    Idea::factory()->create([
        'user_id' => $userA->id,
        'category_id' => $categoryOne->id,
        'status_id' => $statusOpen->id,
        'title' => 'My 1 Idea',
        'description' => 'Description for Idea 1'
    ]);

    Idea::factory()->create([
        'user_id' => $userA->id,
        'category_id' => $categoryOne->id,
        'status_id' => $statusOpen->id,
        'title' => 'My 2 Idea',
        'description' => 'Description for Idea 2'
    ]);

    Idea::factory()->create([
        'user_id' => $userB->id,
        'category_id' => $categoryOne->id,
        'status_id' => $statusOpen->id,
        'title' => 'My 3 Idea',
        'description' => 'Description for Idea 3'
    ]);

    Livewire::actingAs($userA)
        ->test(IdeasIndex::class)
        ->set('filter', 'My Ideas')
        ->assertViewHas('ideas', function ($ideas) {
            return $ideas->count() == 2 && $ideas->first()->title == 'My 2 Idea' && $ideas->get(1)->title == 'My 1 Idea';
        });
});

test('my ideas filter works correctly when user is not logged in', function () {
    $userA = User::factory()->create();
    $userB = User::factory()->create();

    $categoryOne = Category::factory()->create([ 'name' => 'Category One' ]);
    $statusOpen = Status::factory()->create([ 'name' => 'Open' ]);

    Idea::factory()->create([
        'user_id' => $userA->id,
        'category_id' => $categoryOne->id,
        'status_id' => $statusOpen->id,
        'title' => 'My 1 Idea',
        'description' => 'Description for Idea 1'
    ]);

    Idea::factory()->create([
        'user_id' => $userA->id,
        'category_id' => $categoryOne->id,
        'status_id' => $statusOpen->id,
        'title' => 'My 2 Idea',
        'description' => 'Description for Idea 2'
    ]);

    Idea::factory()->create([
        'user_id' => $userB->id,
        'category_id' => $categoryOne->id,
        'status_id' => $statusOpen->id,
        'title' => 'My 3 Idea',
        'description' => 'Description for Idea 3'
    ]);

    Livewire::test(IdeasIndex::class)
        ->set('filter', 'My Ideas')
        ->assertRedirect(route('login'));
});

test('my ideas filter works correctly with category filter', function () {
    $user = User::factory()->create();

    $categoryOne = Category::factory()->create([ 'name' => 'Category One' ]);
    $categoryTwo = Category::factory()->create([ 'name' => 'Category Two' ]);
    $statusOpen = Status::factory()->create([ 'name' => 'Open' ]);

    Idea::factory()->create([
        'user_id' => $user->id,
        'category_id' => $categoryOne->id,
        'status_id' => $statusOpen->id,
        'title' => 'My 1 Idea',
        'description' => 'Description for Idea 1'
    ]);

    Idea::factory()->create([
        'user_id' => $user->id,
        'category_id' => $categoryOne->id,
        'status_id' => $statusOpen->id,
        'title' => 'My 2 Idea',
        'description' => 'Description for Idea 2'
    ]);

    Idea::factory()->create([
        'user_id' => $user->id,
        'category_id' => $categoryTwo->id,
        'status_id' => $statusOpen->id,
        'title' => 'My 3 Idea',
        'description' => 'Description for Idea 3'
    ]);

    Livewire::actingAs($user)
        ->test(IdeasIndex::class)
        ->set('category', 'Category One')
        ->set('filter', 'My Ideas')
        ->assertViewHas('ideas', function ($ideas) {
            return $ideas->count() == 2 && $ideas->first()->title == 'My 2 Idea' && $ideas->get(1)->title == 'My 1 Idea';
        });
});

test('no filter works correctly', function () {
    $user = User::factory()->create();
    $categoryOne = Category::factory()->create([ 'name' => 'Category One' ]);
    $statusOpen = Status::factory()->create([ 'name' => 'Open' ]);

    Idea::factory()->create([
        'user_id' => $user->id,
        'category_id' => $categoryOne->id,
        'status_id' => $statusOpen->id,
        'title' => 'My 1 Idea',
        'description' => 'Description for Idea 1'
    ]);

    Idea::factory()->create([
        'user_id' => $user->id,
        'category_id' => $categoryOne->id,
        'status_id' => $statusOpen->id,
        'title' => 'My 2 Idea',
        'description' => 'Description for Idea 2'
    ]);

    Idea::factory()->create([
        'user_id' => $user->id,
        'category_id' => $categoryOne->id,
        'status_id' => $statusOpen->id,
        'title' => 'My 3 Idea',
        'description' => 'Description for Idea 3'
    ]);

    Livewire::test(IdeasIndex::class)
        ->set('filter', 'No Filter')
        ->assertViewHas('ideas', function ($ideas) {
            return $ideas->count() == 3 && $ideas->first()->title == 'My 3 Idea' && $ideas->get(1)->title == 'My 2 Idea';
        });
});

test('spam ideas filter works correctly', function () {
    $user = User::factory()->create([
        'email' => 'cauchi1020@gmail.com'
    ]);

    Idea::factory()->create([
        'title' => 'My 1 Idea',
        'spam_reports' => 6
    ]);

    Idea::factory()->create([
        'title' => 'My 2 Idea',
        'spam_reports' => 5
    ]);

    Idea::factory()->create([
        'title' => 'My 3 Idea',
        'spam_reports' => 7
    ]);

    Idea::factory()->create();

    Livewire::actingAs($user)
        ->test(IdeasIndex::class)
        ->set('filter', 'Spam Ideas')
        ->assertViewHas('ideas', function ($ideas) {
            return $ideas->count() == 3 && $ideas->first()->title == 'My 3 Idea' && $ideas->get(1)->title == 'My 1 Idea';
        });
});

test('spam comment filter works correctly', function () {
    $user = User::factory()->create([
        'email' => 'cauchi1020@gmail.com'
    ]);

    $ideaOne = Idea::factory()->create([
        'title' => 'My 1 Idea',
        'spam_reports' => 6
    ]);

    $ideaTwo = Idea::factory()->create([
        'title' => 'My 2 Idea',
        'spam_reports' => 5
    ]);

    Idea::factory()->create([
        'title' => 'My 3 Idea',
        'spam_reports' => 7
    ]);

    Idea::factory()->create();

    $comment = Comment::factory()->create([
        'idea_id' => $ideaOne->id,
        'spam_reports' => 5
    ]);

    $comment = Comment::factory()->create([
        'idea_id' => $ideaTwo->id,
        'spam_reports' => 1
    ]);

    Livewire::actingAs($user)
        ->test(IdeasIndex::class)
        ->set('filter', 'Spam Comments')
        ->assertViewHas('ideas', function ($ideas) {
            return $ideas->count() == 2 && $ideas->first()->title == 'My 2 Idea' && $ideas->get(1)->title == 'My 1 Idea';
        });
});

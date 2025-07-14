<?php

use App\Livewire\EditIdea;
use App\Livewire\IdeaShow;
use App\Models\Category;
use App\Models\Idea;
use App\Models\User;
use Livewire\Livewire;
use Symfony\Component\HttpFoundation\Response;

test('shows edit idea livewire component when user has authorisation', function () {
    $user = User::factory()->create();
    $idea = Idea::factory()->create([
        'user_id' => $user->id,
    ]);

    $this->actingAs($user)
        ->get(route('idea.show', $idea))
        ->assertSeeLivewire('edit-idea');
});

test('does not show edit idea livewire component when user does not have authorisation', function () {
    $user = User::factory()->create();
    $idea = Idea::factory()->create();

    $this->actingAs($user)
        ->get(route('idea.show', $idea))
        ->assertDontSeeLivewire('edit-idea');
});

test('edit idea form validation works', function () {
    $user = User::factory()->create();
    $idea = Idea::factory()->create([
        'user_id' => $user->id,
    ]);

    Livewire::actingAs($user)
        ->test(EditIdea::class, [
            'idea' => $idea,
        ])
        ->set('title', '')
        ->set('description', '')
        ->set('category', '')
        ->call('updateIdea')
        ->assertHasErrors(['title', 'description', 'category'])
        ->assertSee('The title field is required.');
});

test('editing an idea works when user has authorisation', function () {
    $user = User::factory()->create();

    $categoryOne = Category::factory()->create([ 'name' => 'Category One' ]);
    $categoryTwo = Category::factory()->create([ 'name' => 'Category Two' ]);

    $idea = Idea::factory()->create([
        'user_id' => $user->id,
        'category_id' => $categoryOne,
    ]);

    Livewire::actingAs($user)
        ->test(EditIdea::class, [
            'idea' => $idea,
        ])
        ->set('title', 'My Edited Idea')
        ->set('description', 'This is an edited description.')
        ->set('category', $categoryTwo->id)
        ->call('updateIdea')
        ->assertDispatched('ideaWasUpdated');

    $this->assertDatabaseHas('ideas', [
        'title' => 'My Edited Idea',
        'description' => 'This is an edited description.',
        'category_id' => $categoryTwo->id,
    ]);
});

test('editing an idea does not work when user does not have authorisation because different user created the idea', function () {
    $userA = User::factory()->create();
    $userB = User::factory()->create();

    $categoryOne = Category::factory()->create([ 'name' => 'Category One' ]);
    $categoryTwo = Category::factory()->create([ 'name' => 'Category Two' ]);

    $idea = Idea::factory()->create([
        'user_id' => $userA->id,
        'category_id' => $categoryOne,
    ]);

    Livewire::actingAs($userB)
        ->test(EditIdea::class, [
            'idea' => $idea,
        ])
        ->set('title', 'My Edited Idea')
        ->set('description', 'This is an edited description.')
        ->set('category', $categoryTwo->id)
        ->call('updateIdea')
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

test('editing an idea does not work when user does not have authorisation because idea was created longer than 1 hour ago', function () {
    $user = User::factory()->create();

    $categoryOne = Category::factory()->create([ 'name' => 'Category One' ]);
    $categoryTwo = Category::factory()->create([ 'name' => 'Category Two' ]);

    $idea = Idea::factory()->create([
        'user_id' => $user->id,
        'category_id' => $categoryOne,
        'created_at' => now()->subHours(2),
    ]);

    Livewire::actingAs($user)
        ->test(EditIdea::class, [
            'idea' => $idea,
        ])
        ->set('title', 'My Edited Idea')
        ->set('description', 'This is an edited description.')
        ->set('category', $categoryTwo->id)
        ->call('updateIdea')
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

test('editing an idea shows title when user has authorisation', function () {
    $user = User::factory()->create();
    $idea = Idea::factory()->create([
        'user_id' => $user->id,
    ]);

    Livewire::actingAs($user)
        ->test(IdeaShow::class, [
            'idea' => $idea,
            'votesCount' => 4
        ])
        ->assertSee('Edit Idea');
});

test('editing an idea does not show title when user does not have authorisation', function () {
    $user = User::factory()->create();
    $idea = Idea::factory()->create();

    Livewire::actingAs($user)
        ->test(IdeaShow::class, [
            'idea' => $idea,
            'votesCount' => 4
        ])
        ->assertDontSee('Edit Idea');
});

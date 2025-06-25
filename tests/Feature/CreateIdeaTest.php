<?php

use App\Livewire\CreateIdea;
use App\Models\Status;
use App\Models\User;
use App\Models\Category;
use Livewire\Livewire;

test('create idea form does not show when logged out', function () {
    $response = $this->get(route('idea.index'));
    $response->assertStatus(200);

    $response->assertSee('Please login to add an idea');
    $response->assertDontSee('Add your new idea here');
});

test('create idea form does show when logged in', function () {
    $response = $this->actingAs(User::factory()->create())->get(route('idea.index'));
    $response->assertStatus(200);

    $response->assertDontSee('Please login to add an idea');
    $response->assertSee('Add your new idea here');
});


test('main page contains create idea component', function () {
    $this->actingAs(User::factory()->create())
        ->get(route('idea.index'))
        ->assertSeeLivewire('create-idea');
});

test('create idea form validation works', function () {
    Livewire::actingAs(User::factory()->create())
        ->test(CreateIdea::class)
        ->set('title', '')
        ->set('description', '')
        ->set('category', '')
        ->call('createIdea')
        ->assertHasErrors(['title', 'description', 'category'])
        ->assertSee('The title field is required.');
});


test('creating an idea works correctly', function () {
    $user = User::factory()->create();
    $categoryOne = Category::factory()->create(['name' => 'Category 1']);
    $statusOpen = Status::factory()->create(['name' => 'Open', 'color' => 'green']);

    Livewire::actingAs($user)
        ->test(CreateIdea::class)
        ->set('title', 'Title Name')
        ->set('description', 'This is a description')
        ->set('category', $categoryOne->id)
        ->call('createIdea')
        ->assertRedirect(route('idea.index'));

    $response = $this->actingAs($user)->get(route('idea.index'));
    $response->assertStatus(200);
    $response->assertSee('Title Name');
    $response->assertSee('This is a description');

    $this->assertDatabaseHas('ideas', [
        'title' => 'Title Name',
    ]);
});

test('creating two ideas with same title still works with different slugs', function () {
    $user = User::factory()->create();
    $categoryOne = Category::factory()->create(['name' => 'Category 1']);

    $statusOpen = Status::factory()->create(['name' => 'Open', 'color' => 'green']);

    Livewire::actingAs($user)
        ->test(CreateIdea::class)
        ->set('title', 'Title Name')
        ->set('description', 'This is a description')
        ->set('category', $categoryOne->id)
        ->call('createIdea')
        ->assertRedirect(route('idea.index'));

    $this->assertDatabaseHas('ideas', [
        'title' => 'Title Name',
        'slug' => 'title-name',
    ]);

    Livewire::actingAs($user)
        ->test(CreateIdea::class)
        ->set('title', 'Title Name')
        ->set('description', 'This is a description')
        ->set('category', $categoryOne->id)
        ->call('createIdea')
        ->assertRedirect(route('idea.index'));

    $this->assertDatabaseHas('ideas', [
        'title' => 'Title Name',
        'slug' => 'title-name-2',
    ]);
});

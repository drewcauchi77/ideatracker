<?php

use App\Models\Category;
use App\Models\Idea;
use App\Models\User;
use App\Models\Status;

test('list of ideas shows on ideas page', function () {
    $categoryOne = Category::factory()->create([ 'name' => 'Category One' ]);
    $categoryTwo = Category::factory()->create([ 'name' => 'Category Two' ]);

    $statusOpen = Status::factory()->create([ 'name' => 'Open', 'color' => 'green' ]);
    $statusConsidering = Status::factory()->create([ 'name' => 'Considering', 'color' => 'purple' ]);

    $ideaOne = Idea::factory()->create([
        'user_id' => User::factory()->create()->id,
        'title' => 'Idea 1',
        'category_id' => $categoryOne->id,
        'status_id' => $statusOpen->id,
        'description' => 'Idea 1 description',
    ]);

    $ideaTwo = Idea::factory()->create([
        'user_id' => User::factory()->create()->id,
        'title' => 'Idea 2',
        'category_id' => $categoryTwo->id,
        'status_id' => $statusConsidering->id,
        'description' => 'Idea 2 description',
    ]);

    $response = $this->get(route('idea.index'));

    $response->assertStatus(200);

    $response->assertSee($ideaOne->title);
    $response->assertSee($ideaOne->description);
    $response->assertSee($categoryOne->name);
    $response->assertSee('<strong style="color: green;">Open</strong>', false);

    $response->assertSee($ideaTwo->title);
    $response->assertSee($ideaTwo->description);
    $response->assertSee($categoryTwo->name);
    $response->assertSee('<strong style="color: purple;">Considering</strong>', false);
});

test('shows correctly on the show page', function () {
    $categoryOne = Category::factory()->create([ 'name' => 'Category One' ]);
    $statusOpen = Status::factory()->create([ 'name' => 'Open', 'color' => 'green' ]);

    $idea = Idea::factory()->create([
        'user_id' => User::factory()->create()->id,
        'title' => 'Idea 1',
        'category_id' => $categoryOne->id,
        'status_id' => $statusOpen->id,
        'description' => 'Idea 1 description',
    ]);

    $response = $this->get(route('idea.show', $idea));

    $response->assertStatus(200);

    $response->assertSee($idea->title);
    $response->assertSee($idea->description);
    $response->assertSee($categoryOne->name);
    $response->assertSee('<strong style="color: green;">Open</strong>', false);
});

test('ideas pagination works', function () {
    $categoryOne = Category::factory()->create([ 'name' => 'Category One' ]);
    $statusOpen = Status::factory()->create([ 'name' => 'Open', 'color' => 'green' ]);

    $paginationCountPlusOne = Idea::PAGINATION_COUNT + 1;
    Idea::factory($paginationCountPlusOne)->create([
        'user_id' => User::factory()->create()->id,
        'category_id' => $categoryOne->id,
        'status_id' => $statusOpen->id,
    ]);

    $ideaOne = Idea::find(1);
    $ideaOne->title = 'Idea One';
    $ideaOne->save();

    $ideaPaginationCountPlusOne = Idea::find($paginationCountPlusOne);
    $ideaPaginationCountPlusOne->title = "Idea Eleven";
    $ideaPaginationCountPlusOne->save();

    $response = $this->get(route('idea.index'));

    $response->assertDontSee($ideaOne->title);
    $response->assertSee($ideaPaginationCountPlusOne->title);
    $response->assertSee('<strong style="color: green;">Open</strong>', false);

    $response = $this->get('/ideas?page=2');

    $response->assertSee($ideaOne->title);
    $response->assertDontSee($ideaPaginationCountPlusOne->title);
    $response->assertSee('<strong style="color: green;">Open</strong>', false);
});

test('same idea titles have different slugs', function () {
    $categoryOne = Category::factory()->create([ 'name' => 'Category One' ]);
    $statusOpen = Status::factory()->create([ 'name' => 'Open', 'color' => 'green' ]);

    $ideaOne = Idea::factory()->create([
        'user_id' => User::factory()->create()->id,
        'title' => 'My Idea Title',
        'category_id' => $categoryOne->id,
        'status_id' => $statusOpen->id,
        'description' => 'Idea 1 description',
    ]);

    $ideaTwo = Idea::factory()->create([
        'user_id' => User::factory()->create()->id,
        'title' => 'My Idea Title',
        'category_id' => $categoryOne->id,
        'status_id' => $statusOpen->id,
        'description' => 'Idea 2 description',
    ]);

    $response = $this->get(route('idea.show', $ideaOne));

    $response->assertStatus(200);
    $this->assertTrue(request()->path() === 'ideas/my-idea-title');
    $response->assertSee('<strong style="color: green;">Open</strong>', false);

    $response = $this->get(route('idea.show', $ideaTwo));

    $response->assertStatus(200);
    $this->assertTrue(request()->path() === 'ideas/my-idea-title-2');
    $response->assertSee('<strong style="color: green;">Open</strong>', false);
});

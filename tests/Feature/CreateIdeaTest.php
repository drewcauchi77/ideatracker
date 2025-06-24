<?php

use App\Models\User;

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
    $this->actingAs(User::factory()->create())->get(route('idea.index'))->assertSee('create-idea');
});

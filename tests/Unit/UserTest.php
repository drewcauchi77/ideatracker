<?php

use App\Models\User;

test('can check if user is an admin', function () {
    $userA = User::factory()->create([
        'name' => 'Andrew',
        'email' => 'user1@gmail.com'
    ]);

    $userB = User::factory()->create([
        'name' => 'Andrew',
        'email' => 'drewcauchi@gmail.com'
    ]);

    $this->assertFalse($userA->isAdmin());
    $this->assertTrue($userB->isAdmin());
});

<?php

use App\Models\User;

test('can check if user is an admin', function () {
    $userA = User::factory()->create([
        'name' => 'Andrew',
        'email' => 'user1@gmail.com'
    ]);

    $userB = User::factory()->create([
        'name' => 'Andrew',
        'email' => 'cauchi1020@gmail.com'
    ]);

    $this->assertFalse($userA->isAdmin());
    $this->assertTrue($userB->isAdmin());
});

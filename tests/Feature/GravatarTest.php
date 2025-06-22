<?php

use \App\Models\User;
use \Illuminate\Support\Facades\Http;

test('user can generate a default gravatar image when no email is found with first character a', function () {
    $user = User::factory()->create([
        'name' => 'Andrew',
        'email' => 'a-fake-email@fakedomain.com'
    ]);

    $gravatarUrl = $user->avatar;

    $this->assertEquals(
        "https://gravatar.com/avatar/".md5($user->email)."?s=100&d=https://s3.amazonaws.com/laracasts/images/forum/avatars/default-avatar-1.png",
        $gravatarUrl
    );

    $response = Http::withOptions([
        'verify' => false,
    ])->get($user->avatar);

    $this->assertTrue($response->successful());
});

test('user can generate a default gravatar image when no email is found with last character z', function () {
    $user = User::factory()->create([
        'name' => 'Andrew',
        'email' => 'z-fake-email@fakedomain.com'
    ]);

    $gravatarUrl = $user->avatar;

    $this->assertEquals(
        "https://gravatar.com/avatar/".md5($user->email)."?s=100&d=https://s3.amazonaws.com/laracasts/images/forum/avatars/default-avatar-26.png",
        $gravatarUrl
    );

    $response = Http::withOptions([
        'verify' => false,
    ])->get($user->avatar);

    $this->assertTrue($response->successful());
});

test('user can generate a default gravatar image when no email is found with first character 0', function () {
    $user = User::factory()->create([
        'name' => 'Andrew',
        'email' => '0-fake-email@fakedomain.com'
    ]);

    $gravatarUrl = $user->avatar;

    $this->assertEquals(
        "https://gravatar.com/avatar/".md5($user->email)."?s=100&d=https://s3.amazonaws.com/laracasts/images/forum/avatars/default-avatar-27.png",
        $gravatarUrl
    );

    $response = Http::withOptions([
        'verify' => false,
    ])->get($user->avatar);

    $this->assertTrue($response->successful());
});

test('user can generate a default gravatar image when no email is found with first character 9', function () {
    $user = User::factory()->create([
        'name' => 'Andrew',
        'email' => '9-fake-email@fakedomain.com'
    ]);

    $gravatarUrl = $user->avatar;

    $this->assertEquals(
        "https://gravatar.com/avatar/".md5($user->email)."?s=100&d=https://s3.amazonaws.com/laracasts/images/forum/avatars/default-avatar-36.png",
        $gravatarUrl
    );

    $response = Http::withOptions([
        'verify' => false,
    ])->get($user->avatar);

    $this->assertTrue($response->successful());
});

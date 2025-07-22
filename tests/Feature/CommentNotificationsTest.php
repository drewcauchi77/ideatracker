<?php

use App\Livewire\AddComment;
use App\Livewire\CommentNotifications;
use App\Models\Idea;
use App\Models\User;
use Illuminate\Notifications\DatabaseNotification;
use Livewire\Livewire;

test('comment notifications component renders when user logged in', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('idea.index'));
    $response->assertSeeLivewire('comment-notifications');
});

test('comment notifications component does not render when user logged out', function () {
    $response = $this->get(route('idea.index'));
    $response->assertDontSeeLivewire('comment-notifications');
});

test('notifications show for logged in user', function () {
    $user = User::factory()->create();
    $idea =  Idea::factory()->create([
        'user_id' => $user->id,
    ]);

    $userAComment = User::factory()->create();
    $userBComment = User::factory()->create();

    Livewire::actingAs($userAComment)
        ->test(AddComment::class, [
            'idea' => $idea,
        ])
        ->set('comment', 'Comment A Body')
        ->call('addComment');

    Livewire::actingAs($userBComment)
        ->test(AddComment::class, [
            'idea' => $idea,
        ])
        ->set('comment', 'Comment B Body')
        ->call('addComment');

    DatabaseNotification::first()->update(['created_at' => now()->subMinute()]);

    Livewire::actingAs($user)
        ->test(CommentNotifications::class)
        ->assertSee('Comment A Body')
        ->assertSee('Comment B Body')
        ->assertSeeInOrder(['Comment B Body', 'Comment A Body'])
        ->assertSet('notificationCount', 2);
});

test('notification count greater than threshold shows for logged in users', function () {
    $user = User::factory()->create();
    $idea =  Idea::factory()->create([
        'user_id' => $user->id,
    ]);

    $userAComment = User::factory()->create();
    $threshold = CommentNotifications::NOTIFICATION_THRESHOLD;

    foreach(range(1, $threshold + 1) as $item) {
        Livewire::actingAs($userAComment)
            ->test(AddComment::class, [
                'idea' => $idea,
            ])
            ->set('comment', 'Comment A Body')
            ->call('addComment');
    }

    Livewire::actingAs($user)
        ->test(CommentNotifications::class)
        ->assertSet('notificationCount', $threshold . '+')
        ->assertSee($threshold . '+');
});

test('can mark all notifications as read', function () {
    $user = User::factory()->create();
    $idea =  Idea::factory()->create([
        'user_id' => $user->id,
    ]);

    $userAComment = User::factory()->create();
    $userBComment = User::factory()->create();

    Livewire::actingAs($userAComment)
        ->test(AddComment::class, [
            'idea' => $idea,
        ])
        ->set('comment', 'Comment A Body')
        ->call('addComment');

    Livewire::actingAs($userBComment)
        ->test(AddComment::class, [
            'idea' => $idea,
        ])
        ->set('comment', 'Comment B Body')
        ->call('addComment');

    Livewire::actingAs($user)
        ->test(CommentNotifications::class)
        ->call('markAllAsRead');

    $this->assertEquals(0, $user->fresh()->unreadNotifications->count());
});

test('can mark individual notifications as read', function () {
    $user = User::factory()->create();
    $idea =  Idea::factory()->create([
        'user_id' => $user->id,
    ]);

    $userAComment = User::factory()->create();
    $userBComment = User::factory()->create();

    Livewire::actingAs($userAComment)
        ->test(AddComment::class, [
            'idea' => $idea,
        ])
        ->set('comment', 'Comment A Body')
        ->call('addComment');

    Livewire::actingAs($userBComment)
        ->test(AddComment::class, [
            'idea' => $idea,
        ])
        ->set('comment', 'Comment B Body')
        ->call('addComment');

    Livewire::actingAs($user)
        ->test(CommentNotifications::class)
        ->call('markAsRead', DatabaseNotification::first()->id)
        ->assertRedirect(route('idea.show', [
            'idea' => $idea,
            'page' => 1
        ]));

    $this->assertEquals(1, $user->fresh()->unreadNotifications->count());
});

test('notification idea deleted redirects to index page', function () {
    $user = User::factory()->create();
    $idea =  Idea::factory()->create([
        'user_id' => $user->id,
    ]);

    $userAComment = User::factory()->create();

    Livewire::actingAs($userAComment)
        ->test(AddComment::class, [
            'idea' => $idea,
        ])
        ->set('comment', 'Comment A Body')
        ->call('addComment');

    $idea->comments()->delete();
    $idea->delete();

    Livewire::actingAs($user)
        ->test(CommentNotifications::class)
        ->call('markAsRead', DatabaseNotification::first()->id)
        ->assertRedirect(route('idea.index'));
});

test('notification comment deleted redirects to index page', function () {
    $user = User::factory()->create();
    $idea =  Idea::factory()->create([
        'user_id' => $user->id,
    ]);

    $userAComment = User::factory()->create();

    Livewire::actingAs($userAComment)
        ->test(AddComment::class, [
            'idea' => $idea,
        ])
        ->set('comment', 'Comment A Body')
        ->call('addComment');

    $idea->comments()->delete();

    Livewire::actingAs($user)
        ->test(CommentNotifications::class)
        ->call('markAsRead', DatabaseNotification::firstOrFail()->id)
        ->assertRedirect(route('idea.index'));
});

<?php

use App\Livewire\DeleteIdea;
use App\Livewire\EditIdea;
use App\Livewire\IdeaIndex;
use App\Livewire\IdeaShow;
use App\Livewire\MarkIdeaAsSpam;
use App\Models\Category;
use App\Models\Idea;
use App\Models\User;
use App\Models\Vote;
use Livewire\Livewire;
use Symfony\Component\HttpFoundation\Response;

test('mark idea as spam livewire component when user has authorisation', function () {
    $user = User::factory()->create();
    $idea = Idea::factory()->create();

    $this->actingAs($user)
        ->get(route('idea.show', $idea))
        ->assertSeeLivewire('mark-idea-as-spam');
});

test('does not show mark idea as spam livewire component when user does not have authorisation', function () {
    $user = User::factory()->create();
    $idea = Idea::factory()->create();

    $this->get(route('idea.show', $idea))
        ->assertDontSeeLivewire('mark-idea-as-spam');
});

test('marking an idea as spam works when user has authorisation', function () {
    $user = User::factory()->create();
    $idea = Idea::factory()->create();

    Livewire::actingAs($user)
        ->test(MarkIdeaAsSpam::class, [
            'idea' => $idea,
        ])
        ->call('markAsSpam')
        ->assertDispatched('ideaWasMarkedAsSpam');

    $this->assertEquals(Idea::first()->spam_reports, 1);
});

test('marking an idea as spam does not work when user does not have authorisation', function () {
    $idea = Idea::factory()->create();

    Livewire::test(MarkIdeaAsSpam::class, [
            'idea' => $idea,
        ])
        ->call('markAsSpam')
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

test('marking an idea as spam shows title when user has authorisation', function () {
    $user = User::factory()->create();
    $idea = Idea::factory()->create();

    Livewire::actingAs($user)
        ->test(IdeaShow::class, [
            'idea' => $idea,
            'votesCount' => 4
        ])
        ->assertSee('Mark Idea as Spam');
});

test('marking an idea as spam does not show title when user does not have authorisation', function () {
    $idea = Idea::factory()->create();

    Livewire::test(IdeaShow::class, [
            'idea' => $idea,
            'votesCount' => 4
        ])
        ->assertDontSee('Mark Idea as Spam');
});

test('mark idea as not spam livewire component when user has authorisation', function () {
    $user = User::factory()->create([
        'email' => 'cauchi1020@gmail.com'
    ]);
    $idea = Idea::factory()->create([
        'spam_reports' => 1
    ]);

    $this->actingAs($user)
        ->get(route('idea.show', $idea))
        ->assertSeeLivewire('mark-idea-as-not-spam');
});

// ... other not marked as spam tests

test('spam reports count shows on ideas index page if logged in as admin', function () {
    $user = User::factory()->create([
        'email' => 'cauchi1020@gmail.com'
    ]);
    $idea = Idea::factory()->create([
        'spam_reports' => 3
    ]);

    Livewire::actingAs($user)
        ->test(IdeaIndex::class, [
            'idea' => $idea,
            'votesCount' => 4
        ])
        ->assertSee('Spam Reports 3');
});

test('spam reports count shows on idea show page if logged in as admin', function () {
    $user = User::factory()->create([
        'email' => 'cauchi1020@gmail.com'
    ]);
    $idea = Idea::factory()->create([
        'spam_reports' => 3
    ]);

    Livewire::actingAs($user)
        ->test(IdeaShow::class, [
            'idea' => $idea,
            'votesCount' => 4
        ])
        ->assertSee('Spam Reports 3');
});

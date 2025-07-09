<?php

use App\Jobs\NotifyAllVoters;
use App\Mail\IdeaStatusUpdatedMailable;
use App\Models\Category;
use App\Models\Idea;
use App\Models\Status;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Support\Facades\Mail;

test('it sends an email to all voters', function () {
    $userA = User::factory()->create([
        'email' => 'kiki@gmail.com'
    ]);
    $userB = User::factory()->create([
        'email' => 'dudamaster@gmail.com'
    ]);

    $categoryOne = Category::factory()->create([ 'name' => 'Category One' ]);
    $statusConsidering = Status::factory()->create([ 'name' => 'Considering', 'id' => 2 ]);

    $ideaOne = Idea::factory()->create([
        'user_id' => $userA->id,
        'category_id' => $categoryOne->id,
        'status_id' => $statusConsidering->id,
        'title' => 'My First Idea'
    ]);

    Vote::create([
       'idea_id' => $ideaOne->id,
       'user_id' => $userA->id,
    ]);

    Vote::create([
        'idea_id' => $ideaOne->id,
        'user_id' => $userB->id,
    ]);

    Mail::fake();
    NotifyAllVoters::dispatch($ideaOne);

    Mail::assertQueued(IdeaStatusUpdatedMailable::class, function ($mail) {
        return $mail->hasTo('kiki@gmail.com') && $mail->envelope()->subject('An idea you voted for has a new status');
    });

    Mail::assertQueued(IdeaStatusUpdatedMailable::class, function ($mail) {
        return $mail->hasTo('dudamaster@gmail.com') && $mail->envelope()->subject('An idea you voted for has a new status');
    });
});

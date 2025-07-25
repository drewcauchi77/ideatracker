<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Idea;
use App\Models\Status;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(20)->create();

        Category::factory(4)->create();

        Status::factory()->create(['name' => 'Open', 'color' => 'green']);
        Status::factory()->create(['name' => 'Considering', 'color' => 'purple']);
        Status::factory()->create(['name' => 'In Progress', 'color' => 'orange']);
        Status::factory()->create(['name' => 'Implemented', 'color' => 'blue']);
        Status::factory()->create(['name' => 'Closed', 'color' => 'red']);

        Idea::factory(100)->existing()->create();

        for ($ideaId = 2; $ideaId <= 100; $ideaId += 2) {
            $userIds = collect(range(1, 20))->shuffle()->take(20);

            foreach ($userIds as $userId) {
                Vote::factory()->forIdeaAndUser($ideaId, $userId)->create();
            }
        }

        foreach (Idea::all() as $idea) {
            Comment::factory()->count(5)->state([
                'idea_id' => $idea->id
            ])->existing()->create();
        }
    }
}

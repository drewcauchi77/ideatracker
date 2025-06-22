<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Idea;
use App\Models\Status;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Category::factory(4)->create();

        Status::factory()->create(['name' => 'Open', 'color' => 'green']);
        Status::factory()->create(['name' => 'Considering', 'color' => 'purple']);
        Status::factory()->create(['name' => 'In Progress', 'color' => 'orange']);
        Status::factory()->create(['name' => 'Implemented', 'color' => 'blue']);
        Status::factory()->create(['name' => 'Closed', 'color' => 'red']);

        Idea::factory(30)->create();
    }
}

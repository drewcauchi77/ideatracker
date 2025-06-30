<?php

use App\Models\User;
use App\Models\Category;
use App\Models\Status;
use App\Models\Idea;

test('can get count of each status', function () {
    $user = User::factory()->create();
    $categoryOne = Category::factory()->create([ 'name' => 'Category One' ]);

    Status::factory()->create([ 'name' => 'Open' ]);
    Status::factory()->create([ 'name' => 'Considering' ]);
    Status::factory()->create([ 'name' => 'In Progress' ]);
    Status::factory()->create([ 'name' => 'Implemented' ]);
    Status::factory()->create([ 'name' => 'Closed' ]);

    $statusCounts = [0, 0, 0, 0, 0];

    for ($i = 1; $i <= 15; $i++) {
        $selectedStatus = rand(1, 5);
        $statusCounts[$selectedStatus - 1] = $statusCounts[$selectedStatus - 1] + 1;

        Idea::factory()->create([
            'user_id' => $user->id,
            'category_id' => $categoryOne->id,
            'status_id' => $selectedStatus,
        ]);
    }

    $this->assertEquals(15, Status::getCount()['all_statuses']);
    $this->assertEquals($statusCounts[0], Status::getCount()['open']);
    $this->assertEquals($statusCounts[1], Status::getCount()['considering']);
    $this->assertEquals($statusCounts[2], Status::getCount()['in_progress']);
    $this->assertEquals($statusCounts[3], Status::getCount()['implemented']);
    $this->assertEquals($statusCounts[4], Status::getCount()['closed']);
});

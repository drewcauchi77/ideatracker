<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Database\Factories\IdeaFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Idea extends Model
{
    /**
     * @use HasFactory<IdeaFactory>
     * @use Sluggable The slugifying of the title to ensure no duplicates
     */
    use HasFactory, Sluggable;

    /**
     * Const value for how many items should be in a page
     *
     * @var int
     */
    const PAGINATION_COUNT = 10;

    /**
     * Fields that are blocked from being mass-assigned.
     *
     * @var list<string>
     */
    protected $guarded = [];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    /**
     * Every idea should be tied to a user.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Every idea should be tied to a category.
     *
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Every idea should be tied to a status.
     *
     * @return BelongsTo
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }


    public function votes(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'votes');
    }

    public function isVotedByUser(?User $user): bool
    {
        if(!$user) return false;

        return Vote::where('user_id', $user->id)
            ->where('idea_id', $this->id)
            ->exists();
    }
}

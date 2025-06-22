<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Status extends Model
{
    /** @use HasFactory<\Database\Factories\StatusFactory> */
    use HasFactory;

    /**
     * Every status can be given to 1 or more ideas.
     *
     * @return hasMany
     */
    public function ideas() : HasMany
    {
        return $this->hasMany(Idea::class);
    }
}

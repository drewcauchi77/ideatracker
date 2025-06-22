<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    /**
     * Every user can have 1 or more ideas.
     *
     * @return HasMany
     */
    public function ideas(): HasMany
    {
        return $this->hasMany(Idea::class);
    }

    public function getAvatarAttribute(): string
    {
        $firstCharacter = $this->email[0];
        $integerToUse = is_numeric($firstCharacter) ? ord($firstCharacter) - 21 : ord($firstCharacter) - 96;

        return "https://gravatar.com/avatar/"
            .md5($this->email)
            ."?s=100&d=https://s3.amazonaws.com/laracasts/images/forum/avatars/default-avatar-".$integerToUse.".png";
    }
}

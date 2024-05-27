<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

/**
 * @mixin Builder
 */

class Post extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'body',
        'user_id'
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::creating(function(Post $post) {
            $post->user_id = auth()->id();
        });

        static::updating(function(Post $post) {
            // Asegúrate de que el user_id no se sobrescriba durante la actualización
            if(!$post->isDirty('user_id')) {
                $post->user_id = auth()->id();
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(PostAttachment::class)->latest();
    }

    public function reactions(): HasMany
    {
        return $this->hasMany(PostReaction::class);
    }

//    public function scopeWithReactions(Builder $query, User $user): Builder
//    {
//        return $query->with(['reactions' => function($query) use ($user) {
//            $query->where('user_id', $user->id);
//        }]);
//    }
//
//    public function scopeWithReactionsCount(Builder $query): Builder
//    {
//        return $query->withCount([
//            'reactions',
//            'reactions as likes_count' => function($query) {
//                $query->where('reaction', 'like');
//            },
//            'reactions as dislikes_count' => function($query) {
//                $query->where('reaction', 'dislike');
//            },
//        ]);
//    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function latestComments()
    {
        return $this->comments()->latest()->limit(5);
    }
}

<?php

namespace App\Models;

use App\Http\Enums\ReactionEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Reaction extends Model
{
    use HasFactory;

    const UPDATED_AT = null;

    protected $fillable = [
        'user_id',
        'reaction',
    ];

    protected $casts = [
        'reaction' => ReactionEnum::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /*
     * Get the parent reactable model (post, comment, etc.)
     */
    public function reactable(): MorphTo
    {
        return $this->morphTo();
    }
}

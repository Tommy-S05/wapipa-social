<?php

namespace App\Http\Enums;

enum ReactionEnum: string
{
    case LIKE = 'like';
    case DISLIKE = 'dislike';
    case LOVE = 'love';
    case HAHA = 'haha';
    case WOW = 'wow';
    case SAD = 'sad';
    case ANGRY = 'angry';
    case CARE = 'care';
    case SUPPORT = 'support';
    case THANKFUL = 'thankful';
    case PRAY = 'pray';
    case CELEBRATE = 'celebrate';

    //    public static function all(): array
    //    {
    //        return [
    //            self::LIKE,
    //            self::DISLIKE,
    //            self::LOVE,
    //            self::HAHA,
    //            self::WOW,
    //            self::SAD,
    //            self::ANGRY,
    //            self::CARE,
    //            self::SUPPORT,
    //            self::THANKFUL,
    //            self::PRAY,
    //            self::CELEBRATE,
    //        ];
    //    }
}

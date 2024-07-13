<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $userReaction = $this->reactions->first();
        $userReactionType = $userReaction ? $userReaction->reaction->value : null;

        /**
         * @var \App\Models\Comment $this
         */
        return [
            'id' => $this->id,
            'comment' => $this->comment,
//            'comments' => CommentResource::collection($this->comments()->latest()->limit(5)->get()),
            'comments' => $this->child_comments,
            'user' => [
                "id" => $this->user->id,
                "name" => $this->user->name,
                "username" => $this->user->username,
                "avatar_url" => $this->user->avatar_path ? Storage::url($this->user->avatar_path) : null
            ],
            'reaction_type' => $userReactionType,
            'reactions_count' => [
                'total' => $this->reactions_count,
                'like' => $this->likes_count,
                'dislike' => $this->dislikes_count,
            ],
//            'comments_count' => count($this->child_comments), // TODO count all subcomments
            'comments_count' => $this->comments_count, // TODO count all subcomments
            'created_at' => $this->created_at->format('d-m-Y H:i:s'),
            'updated_at' => $this->updated_at->format('d-m-Y H:i:s'),
        ];
    }
}

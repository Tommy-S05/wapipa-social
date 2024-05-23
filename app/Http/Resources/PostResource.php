<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $userReaction = $this->reactions->first();
        $userReactionType = $userReaction ? $userReaction->reaction : null;

        return [
            'id' => $this->id,
            'body' => $this->body,
            'created_at' => $this->created_at->format('d-m-Y H:i:s'),
            'updated_at' => $this->updated_at->format('d-m-Y H:i:s'),
            'user' => new UserResource($this->user),
            'group' => $this->group,
            'attachments' => PostAttachmentResource::collection($this->attachments),
            'reaction_type' => $userReactionType,
            'reactions_count' => [
                'total' => $this->reactions_count,
                'like' => $this->likes_count,
                'dislike' => $this->dislikes_count,
            ],
        ];
    }
}

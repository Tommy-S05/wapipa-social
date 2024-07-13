<?php

namespace App\Http\Resources;

use App\Models\Comment;
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
        $userReactionType = $userReaction ? $userReaction->reaction->value : null;

        $comments = $this->comments;

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
            'comments_count' => count($comments),
//            'comments' => CommentResource::collection($this->latestComments),
            'comments' => self::convertCommentIntoTree($comments),
        ];
    }

    /**
     * @param Comment[] $comments
     * @param $parentId
     * @return array
     */
    private static function convertCommentIntoTree($comments, $parentId = null): array
    {
        $commentTree = array();
        foreach ($comments as $comment) {
            if ($comment->parent_id == $parentId) {
                // Find all children for the current comment
                $children = self::convertCommentIntoTree($comments, $comment->id);
                $comment->child_comments = $children;
//                $comment->comments_count = collect($children)->sum('comments_count');
                $comment->comments_count = collect($children)->sum('num_of_comments') + count($children);
                //$comment->setAttribute('comments', $children);
//                $commentTree[] = $comment;
                $commentTree[] = new CommentResource($comment);
            }
        }
        return $commentTree;
    }
}

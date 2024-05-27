<?php

namespace App\Http\Controllers;

use App\Http\Enums\PostReactionEnum;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\CommentResource;
use App\Models\Post;
use App\Models\PostAttachment;
use App\Models\PostReaction;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $user = $request->user();

        DB::beginTransaction();
        $allFilePaths = [];
        try {
            $post = Post::create($data);

            /** @var UploadedFile[] $files */
            $files = $data['attachments'] ?? [];

            $this->createFiles($post, $user, $files);

            DB::commit();

        } catch (\Exception $exception) {
            foreach($allFilePaths as $filePath){
                Storage::disk('public')->delete($filePath);
            }
            DB::rollBack();
        }

        return Redirect::back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post): RedirectResponse
    {
        $data = $request->validated();
        $user = $request->user();

        DB::beginTransaction();
        $allFilePaths = [];
        try {
            $post->update($data);

            /** @var UploadedFile[] $files */
            $files = $data['attachments'] ?? [];

            $deleted_files = $data['deleted_files_ids'] ?? [];

            $attachments = PostAttachment::where('post_id', $post->id)
                ->whereIn('id', $deleted_files)
                ->get();

            foreach($attachments as $attachment) {
                $attachment->delete();
            }

            $this->createFiles($post, $user, $files);

            DB::commit();

        } catch (\Exception $exception) {
            foreach($allFilePaths as $filePath){
                Storage::disk('public')->delete($filePath);
            }
            DB::rollBack();
        }

        return Redirect::back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        // TODO
        $id = Auth::id();

        if($post->user_id !== $id) {
            return response("You don't have permission to delete this post", 403);
        }

        $post->delete();

        return Redirect::back();
    }

    public function download(PostAttachment $attachment)
    {
//        dd($attachment);
        // TODO check if the user has permission to download that attachment
        if(Storage::disk('public')->exists($attachment->path)) {
            return Storage::disk('public')->download($attachment->path, $attachment->name);
        }
        return Redirect::back();
    }

    private function createFiles(Post $post, User $user, $files)
    {
        foreach($files as $file) {
            $folderName = "user-$user->id/post-$post->id/attachments";
            $path = Storage::disk('public')->put($folderName, $file);

            $post->attachments()->create([
                'name' => $file->getClientOriginalName(),
                'path' => $path,
                'mime' => $file->getMimeType(),
                'size' => $file->getSize(),
                'created_by' => $user->id
            ]);
        }
    }

    public function postReaction(Post $post, Request $request)
    {
        $data = $request->validate([
            'reaction' => [Rule::enum(PostReactionEnum::class)]
        ]);

        $user = Auth::user();

        $reaction = PostReaction::where('post_id', $post->id)
            ->where('user_id', $user->id)
            ->first();

        if($reaction && $reaction->reaction !== $data['reaction']) {
            $reaction->update([
                'reaction' => $data['reaction']
            ]);

        } elseif($reaction && $reaction->reaction === $data['reaction']) {
            $reaction->delete();

        } else {
            PostReaction::create([
                'post_id' => $post->id,
                'user_id' => $user->id,
                'reaction' => $data['reaction']
            ]);

        }

        $reactions = PostReaction::where('post_id', $post->id)
            ->count();

        $likesCount = PostReaction::where('post_id', $post->id)
            ->where('reaction', 'like')
            ->count();

        $dislikesCount = PostReaction::where('post_id', $post->id)
            ->where('reaction', 'dislike')
            ->count();

        $currentUserReaction = PostReaction::where('post_id', $post->id)
            ->where('user_id', $user->id)
            ->first();

        return response()->json([
            'reaction_type' => $currentUserReaction?->reaction,
            'reactions_count' => [
                'total' => $reactions,
                'like' => $likesCount,
                'dislike' => $dislikesCount,
            ],
        ]);
    }

    public function createPostComment(Post $post, Request $request)
    {
        $data = $request->validate([
            'comment' => 'required|string'
        ]);

        $user = Auth::user();

        $comment = $post->comments()->create([
            'comment' => nl2br($data['comment']),
            'user_id' => $user->id
        ]);

        $comment_count = $post->comments()->count();

        return response()->json([
            'comment' => new CommentResource($comment),
            'comments_count' => $comment_count
        ], 201);
    }
}

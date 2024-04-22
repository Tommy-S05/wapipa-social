<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use App\Models\PostAttachment;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

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
}

<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $posts = Post::latest()
            //->withCount('reactions')
            ->withCount([
                'reactions',
                'reactions as likes_count' => function($query) {
                    $query->where('reaction', 'like');
                },
                'reactions as dislikes_count' => function($query) {
                    $query->where('reaction', 'dislike');
                },
                'comments'
            ])
            ->with([
                'reactions' => function($query) use ($user) {
                    $query->where('user_id', $user->id);
                },
                'latestComments' => function($query) use ($user) {
                    $query->withCount([
                        'reactions',
                        'reactions as likes_count' => function($query) {
                            $query->where('reaction', 'like');
                        },
                        'reactions as dislikes_count' => function($query) {
                            $query->where('reaction', 'dislike');
                        },
                    ])->with([
                        'reactions' => function($query) use ($user) {
                            $query->where('user_id', $user->id);
                        },
                    ]);
                }
            ])
            ->paginate(10);

        return Inertia::render('Home', [
            'posts' => PostResource::collection($posts)
        ]);
    }
}

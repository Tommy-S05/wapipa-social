<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Database\Eloquent\Builder;
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
                //'comments'
            ])
            ->with([
                'reactions' => function($query) use ($user) {
                    $query->where('user_id', $user->id);
                },
                'comments' => function($query) use ($user) {
                    /** @var Builder $query */
                    $query
                        //->whereNull('parent_id')
                        ->withCount([
                            'reactions',
                            'reactions as likes_count' => function($query) {
                                $query->where('reaction', 'like');
                            },
                            'reactions as dislikes_count' => function($query) {
                                $query->where('reaction', 'dislike');
                            },
                        ]);
                }
            ])
            ->paginate(1);

        return Inertia::render('Home', [
            'posts' => PostResource::collection($posts)
        ]);
    }
}

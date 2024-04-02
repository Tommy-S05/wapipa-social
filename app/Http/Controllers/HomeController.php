<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $posts = Post::latest()->paginate(10);
        return Inertia::render('Home', [
            'posts' => PostResource::collection($posts)
        ]);
    }
}

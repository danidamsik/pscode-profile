<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class BlogController extends Controller
{
    public function index(): Response
    {
        $blogs = Blog::query()
            ->where('is_published', true)
            ->whereNotNull('published_at')
            ->latest('published_at')
            ->paginate(6, [
                'id',
                'title',
                'slug',
                'excerpt',
                'thumbnail',
                'published_at',
            ])
            ->withQueryString();

        return Inertia::render('Blog/Index', [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'blogs' => $blogs,
        ]);
    }

    public function show(Blog $blog): Response
    {
        abort_unless($blog->is_published && $blog->published_at, 404);

        return Inertia::render('Blog/Show', [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'blog' => $blog->only([
                'id',
                'title',
                'slug',
                'excerpt',
                'content',
                'thumbnail',
                'images',
                'published_at',
            ]),
            'latestBlogs' => Blog::query()
                ->where('is_published', true)
                ->whereNotNull('published_at')
                ->whereKeyNot($blog->id)
                ->latest('published_at')
                ->take(3)
                ->get(['id', 'title', 'slug', 'excerpt', 'published_at']),
        ]);
    }
}

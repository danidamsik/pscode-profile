<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class BlogController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Blogs', [
            'items' => Blog::query()
                ->latest()
                ->paginate(10)
                ->withQueryString(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Blog::create($this->validatedData($request));

        return back();
    }

    public function update(Request $request, Blog $blog): RedirectResponse
    {
        $oldThumbnail = $blog->thumbnail;
        $oldImages = $blog->images ?? [];

        $blog->update($this->validatedData($request, $blog));

        if ($request->hasFile('thumbnail_file')) {
            $this->deleteStoredFile($oldThumbnail);
        }

        if ($request->hasFile('gallery_images')) {
            $this->deleteStoredFiles($oldImages);
        }

        return back();
    }

    public function destroy(Blog $blog): RedirectResponse
    {
        $this->deleteStoredFile($blog->thumbnail);
        $this->deleteStoredFiles($blog->images ?? []);

        $blog->delete();

        return back();
    }

    /**
     * @return array<string, mixed>
     */
    private function validatedData(Request $request, ?Blog $blog = null): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('blogs', 'slug')->ignore($blog)],
            'excerpt' => ['nullable', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'thumbnail_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'gallery_images' => ['nullable', 'array'],
            'gallery_images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'is_published' => ['boolean'],
            'published_at' => ['nullable', 'date'],
        ]);

        unset($data['thumbnail_file'], $data['gallery_images']);

        $data['slug'] = $this->uniqueSlug($data['slug'] ?: $data['title'], $blog);
        $data['is_published'] = (bool) ($data['is_published'] ?? false);

        if ($request->hasFile('gallery_images')) {
            $data['images'] = $this->storeGalleryImages($request);
        }

        if ($request->hasFile('thumbnail_file')) {
            $data['thumbnail'] = $this->publicStorageUrl(
                $request->file('thumbnail_file')->store('blogs/thumbnails', 'public')
            );
        }

        return $data;
    }

    /**
     * @return array<int, string>
     */
    private function storeGalleryImages(Request $request): array
    {
        return collect($request->file('gallery_images'))
            ->map(fn ($image): string => $this->publicStorageUrl($image->store('blogs/gallery', 'public')))
            ->values()
            ->all();
    }

    private function publicStorageUrl(string $path): string
    {
        return '/storage/'.$path;
    }

    /**
     * @param  array<int, string>  $paths
     */
    private function deleteStoredFiles(array $paths): void
    {
        foreach ($paths as $path) {
            $this->deleteStoredFile($path);
        }
    }

    private function deleteStoredFile(?string $path): void
    {
        if (! $path) {
            return;
        }

        $publicPath = parse_url($path, PHP_URL_PATH) ?: $path;

        if (! str_starts_with($publicPath, '/storage/')) {
            return;
        }

        Storage::disk('public')->delete(Str::after($publicPath, '/storage/'));
    }

    private function uniqueSlug(string $value, ?Blog $blog = null): string
    {
        $slug = Str::slug($value);
        $candidate = $slug;
        $counter = 2;

        while (Blog::query()
            ->where('slug', $candidate)
            ->when($blog, fn ($query) => $query->whereKeyNot($blog->getKey()))
            ->exists()) {
            $candidate = "{$slug}-{$counter}";
            $counter++;
        }

        return $candidate;
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Portfolio;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class PortfolioController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Portfolios', [
            'items' => Portfolio::query()
                ->with(['images:id,portfolio_id,image,alt_text,sort_order'])
                ->latest()
                ->paginate(10)
                ->withQueryString(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $portfolio = Portfolio::create($this->validatedData($request));

        $this->storeGalleryImages($request, $portfolio);

        return back();
    }

    public function update(Request $request, Portfolio $portfolio): RedirectResponse
    {
        $oldThumbnail = $portfolio->thumbnail;

        $portfolio->update($this->validatedData($request, $portfolio));

        if ($request->hasFile('thumbnail_file')) {
            $this->deleteStoredFile($oldThumbnail);
        }

        if ($request->hasFile('gallery_images')) {
            $this->replaceGalleryImages($request, $portfolio);
        }

        return back();
    }

    public function destroy(Portfolio $portfolio): RedirectResponse
    {
        $this->deleteStoredFile($portfolio->thumbnail);
        $this->deleteGalleryImages($portfolio);

        $portfolio->delete();

        return back();
    }

    /**
     * @return array<string, mixed>
     */
    private function validatedData(Request $request, ?Portfolio $portfolio = null): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('portfolios', 'slug')->ignore($portfolio)],
            'category' => ['required', Rule::in(['website', 'mobile', 'other'])],
            'client_name' => ['nullable', 'string', 'max:255'],
            'short_description' => ['nullable', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'technologies' => ['nullable'],
            'thumbnail_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'gallery_images' => ['nullable', 'array'],
            'gallery_images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'project_url' => ['nullable', 'url', 'max:255'],
            'is_featured' => ['boolean'],
            'is_published' => ['boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:65535'],
            'published_at' => ['nullable', 'date'],
        ]);

        unset($data['thumbnail_file'], $data['gallery_images']);

        $data['slug'] = $this->uniqueSlug($data['slug'] ?: $data['title'], $portfolio);
        $data['technologies'] = $this->normalizeList($data['technologies'] ?? null);
        $data['is_featured'] = (bool) ($data['is_featured'] ?? false);
        $data['is_published'] = (bool) ($data['is_published'] ?? false);
        $data['sort_order'] = $data['sort_order'] ?? 0;

        if ($request->hasFile('thumbnail_file')) {
            $data['thumbnail'] = $this->publicStorageUrl(
                $request->file('thumbnail_file')->store('portfolios/thumbnails', 'public')
            );
        }

        return $data;
    }

    private function storeGalleryImages(Request $request, Portfolio $portfolio): void
    {
        if (! $request->hasFile('gallery_images')) {
            return;
        }

        foreach ($request->file('gallery_images') as $index => $image) {
            $portfolio->images()->create([
                'image' => $this->publicStorageUrl($image->store('portfolios/gallery', 'public')),
                'alt_text' => $portfolio->title,
                'sort_order' => $index + 1,
            ]);
        }
    }

    private function publicStorageUrl(string $path): string
    {
        return '/storage/'.$path;
    }

    private function replaceGalleryImages(Request $request, Portfolio $portfolio): void
    {
        $this->deleteGalleryImages($portfolio);
        $this->storeGalleryImages($request, $portfolio);
    }

    private function deleteGalleryImages(Portfolio $portfolio): void
    {
        $portfolio->loadMissing('images');

        foreach ($portfolio->images as $image) {
            $this->deleteStoredFile($image->image);
        }

        $portfolio->images()->delete();
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

    private function uniqueSlug(string $value, ?Portfolio $portfolio = null): string
    {
        $slug = Str::slug($value);
        $candidate = $slug;
        $counter = 2;

        while (Portfolio::query()
            ->where('slug', $candidate)
            ->when($portfolio, fn ($query) => $query->whereKeyNot($portfolio->getKey()))
            ->exists()) {
            $candidate = "{$slug}-{$counter}";
            $counter++;
        }

        return $candidate;
    }

    /**
     * @return array<int, string>
     */
    private function normalizeList(mixed $value): array
    {
        if (is_array($value)) {
            return array_values(array_filter($value));
        }

        return collect(explode(',', (string) $value))
            ->map(fn (string $item): string => trim($item))
            ->filter()
            ->values()
            ->all();
    }
}

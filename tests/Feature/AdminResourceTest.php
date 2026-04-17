<?php

namespace Tests\Feature;

use App\Models\Blog;
use App\Models\Contact;
use App\Models\Portfolio;
use App\Models\TeamMember;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class AdminResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_resource_pages(): void
    {
        $admin = User::factory()->admin()->create();

        $routes = [
            'admin.portfolios.index',
            'admin.testimonials.index',
            'admin.blogs.index',
            'admin.contacts.index',
            'admin.team-members.index',
        ];

        foreach ($routes as $route) {
            $this->actingAs($admin)
                ->get(route($route))
                ->assertOk();
        }
    }

    public function test_user_cannot_view_admin_resource_pages(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('admin.blogs.index'))
            ->assertForbidden();
    }

    public function test_admin_can_manage_blog_posts(): void
    {
        $admin = User::factory()->admin()->create();
        Blog::factory()->create([
            'title' => 'Aplikasi Inventory Modern',
            'slug' => 'aplikasi-inventory-modern',
        ]);

        $this->actingAs($admin)
            ->post(route('admin.blogs.store'), [
                'title' => 'Aplikasi Inventory Modern',
                'slug' => '',
                'excerpt' => 'Ringkasan artikel admin.',
                'content' => 'Konten artikel dari dashboard admin.',
                'is_published' => true,
                'published_at' => now()->format('Y-m-d H:i:s'),
            ])
            ->assertSessionHasNoErrors()
            ->assertRedirect();

        $blog = Blog::query()->where('slug', 'aplikasi-inventory-modern-2')->firstOrFail();

        $this->actingAs($admin)
            ->put(route('admin.blogs.update', $blog), [
                'title' => 'Aplikasi Inventory untuk UMKM',
                'slug' => '',
                'excerpt' => 'Ringkasan artikel yang diperbarui.',
                'content' => 'Konten artikel sudah diperbarui.',
                'is_published' => false,
                'published_at' => null,
            ])
            ->assertSessionHasNoErrors()
            ->assertRedirect();

        $blog->refresh();

        $this->assertSame('aplikasi-inventory-untuk-umkm', $blog->slug);
        $this->assertFalse($blog->is_published);

        $this->actingAs($admin)
            ->delete(route('admin.blogs.destroy', $blog))
            ->assertRedirect();

        $this->assertDatabaseMissing('blogs', [
            'id' => $blog->id,
        ]);
    }

    public function test_admin_can_manage_portfolios_team_contacts_and_testimonials(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();
        $testimonial = Testimonial::factory()->create([
            'user_id' => $user->id,
            'is_approved' => false,
        ]);
        $contact = Contact::factory()->create(['status' => 'unread']);

        $this->actingAs($admin)
            ->post(route('admin.portfolios.store'), [
                'title' => 'Website Reservasi Klinik',
                'slug' => '',
                'category' => 'website',
                'client_name' => 'Klinik Sehat',
                'short_description' => 'Sistem reservasi online.',
                'description' => 'Website untuk reservasi dan manajemen jadwal klinik.',
                'technologies' => 'Laravel, Vue, MySQL',
                'project_url' => 'https://example.com',
                'is_featured' => true,
                'is_published' => true,
                'sort_order' => 1,
                'published_at' => now()->format('Y-m-d H:i:s'),
            ])
            ->assertSessionHasNoErrors()
            ->assertRedirect();

        $this->assertDatabaseHas('portfolios', [
            'slug' => 'website-reservasi-klinik',
            'category' => 'website',
        ]);

        $this->actingAs($admin)
            ->post(route('admin.team-members.store'), [
                'name' => 'Nadia Putri',
                'role' => 'Frontend Engineer',
                'description' => 'Membangun antarmuka aplikasi.',
                'linkedin' => 'https://linkedin.com/in/nadia',
                'github' => 'https://github.com/nadia',
                'sort_order' => 2,
                'is_active' => true,
            ])
            ->assertSessionHasNoErrors()
            ->assertRedirect();

        $teamMember = TeamMember::query()->where('name', 'Nadia Putri')->firstOrFail();

        $this->assertSame('https://github.com/nadia', $teamMember->social_links['github']);

        $this->actingAs($admin)
            ->put(route('admin.contacts.update', $contact), [
                'name' => $contact->name,
                'email' => $contact->email,
                'message' => $contact->message,
                'status' => 'read',
            ])
            ->assertSessionHasNoErrors()
            ->assertRedirect();

        $this->assertDatabaseHas('contacts', [
            'id' => $contact->id,
            'status' => 'read',
        ]);

        $this->actingAs($admin)
            ->put(route('admin.testimonials.update', $testimonial), [
                'rating' => 5,
                'comment' => 'Hasilnya rapi dan komunikasi jelas.',
                'is_approved' => true,
            ])
            ->assertSessionHasNoErrors()
            ->assertRedirect();

        $this->assertDatabaseHas('testimonials', [
            'id' => $testimonial->id,
            'is_approved' => true,
        ]);
    }

    public function test_admin_can_upload_portfolio_thumbnail_and_gallery_images(): void
    {
        Storage::fake('public');

        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->post(route('admin.portfolios.store'), [
                'title' => 'Website Reservasi Hotel',
                'slug' => '',
                'category' => 'website',
                'client_name' => 'Hotel Senja',
                'short_description' => 'Website reservasi kamar hotel.',
                'description' => 'Website reservasi dengan galeri kamar, kalender booking, dan dashboard admin.',
                'technologies' => 'Laravel, Vue, MySQL',
                'thumbnail_file' => UploadedFile::fake()->image('cover.jpg', 1200, 800),
                'gallery_images' => [
                    UploadedFile::fake()->image('gallery-a.jpg', 1200, 800),
                    UploadedFile::fake()->image('gallery-b.jpg', 1200, 800),
                ],
                'project_url' => 'https://example.com/hotel',
                'is_featured' => true,
                'is_published' => true,
                'sort_order' => 1,
                'published_at' => now()->format('Y-m-d H:i:s'),
            ])
            ->assertSessionHasNoErrors()
            ->assertRedirect();

        $portfolio = Portfolio::query()
            ->with('images')
            ->where('slug', 'website-reservasi-hotel')
            ->firstOrFail();

        $this->assertStringStartsWith('/storage/portfolios/thumbnails/', $portfolio->thumbnail);
        $this->assertCount(2, $portfolio->images);
        Storage::disk('public')->assertExists(Str::after($portfolio->thumbnail, '/storage/'));

        foreach ($portfolio->images as $image) {
            $this->assertStringStartsWith('/storage/portfolios/gallery/', $image->image);
            Storage::disk('public')->assertExists(Str::after($image->image, '/storage/'));
        }

        $oldThumbnail = Str::after($portfolio->thumbnail, '/storage/');
        $oldGalleryImages = $portfolio->images
            ->pluck('image')
            ->map(fn (string $path): string => Str::after($path, '/storage/'));

        $this->actingAs($admin)
            ->post(route('admin.portfolios.update', $portfolio), [
                '_method' => 'put',
                'title' => 'Website Reservasi Hotel Premium',
                'slug' => '',
                'category' => 'website',
                'client_name' => 'Hotel Senja',
                'short_description' => 'Website reservasi kamar hotel premium.',
                'description' => 'Website reservasi dengan galeri kamar, kalender booking, dan dashboard admin.',
                'technologies' => 'Laravel, Vue, MySQL',
                'thumbnail_file' => UploadedFile::fake()->image('cover-new.jpg', 1200, 800),
                'gallery_images' => [
                    UploadedFile::fake()->image('gallery-new.jpg', 1200, 800),
                ],
                'project_url' => 'https://example.com/hotel',
                'is_featured' => false,
                'is_published' => true,
                'sort_order' => 2,
                'published_at' => now()->format('Y-m-d H:i:s'),
            ])
            ->assertSessionHasNoErrors()
            ->assertRedirect();

        $portfolio->refresh()->load('images');

        Storage::disk('public')->assertMissing($oldThumbnail);
        $oldGalleryImages->each(fn (string $path) => Storage::disk('public')->assertMissing($path));

        $this->assertStringStartsWith('/storage/portfolios/thumbnails/', $portfolio->thumbnail);
        $this->assertCount(1, $portfolio->images);
        Storage::disk('public')->assertExists(Str::after($portfolio->thumbnail, '/storage/'));
        Storage::disk('public')->assertExists(Str::after($portfolio->images->first()->image, '/storage/'));
    }

    public function test_admin_can_upload_blog_thumbnail_gallery_and_team_photo(): void
    {
        Storage::fake('public');

        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->post(route('admin.blogs.store'), [
                'title' => 'Strategi Website untuk UMKM',
                'slug' => '',
                'excerpt' => 'Panduan singkat menyiapkan website bisnis.',
                'content' => 'Konten artikel tentang strategi website untuk UMKM.',
                'thumbnail_file' => UploadedFile::fake()->image('blog-cover.jpg', 1200, 800),
                'gallery_images' => [
                    UploadedFile::fake()->image('blog-gallery-a.jpg', 1200, 800),
                    UploadedFile::fake()->image('blog-gallery-b.jpg', 1200, 800),
                ],
                'is_published' => true,
                'published_at' => now()->format('Y-m-d H:i:s'),
            ])
            ->assertSessionHasNoErrors()
            ->assertRedirect();

        $blog = Blog::query()->where('slug', 'strategi-website-untuk-umkm')->firstOrFail();

        $this->assertStringStartsWith('/storage/blogs/thumbnails/', $blog->thumbnail);
        $this->assertCount(2, $blog->images);
        Storage::disk('public')->assertExists(Str::after($blog->thumbnail, '/storage/'));

        foreach ($blog->images as $image) {
            $this->assertStringStartsWith('/storage/blogs/gallery/', $image);
            Storage::disk('public')->assertExists(Str::after($image, '/storage/'));
        }

        $oldThumbnail = Str::after($blog->thumbnail, '/storage/');
        $oldImages = collect($blog->images)->map(fn (string $path): string => Str::after($path, '/storage/'));

        $this->actingAs($admin)
            ->post(route('admin.blogs.update', $blog), [
                '_method' => 'put',
                'title' => 'Strategi Website untuk Bisnis Lokal',
                'slug' => '',
                'excerpt' => 'Panduan singkat menyiapkan website bisnis lokal.',
                'content' => 'Konten artikel yang diperbarui.',
                'thumbnail_file' => UploadedFile::fake()->image('blog-cover-new.jpg', 1200, 800),
                'gallery_images' => [
                    UploadedFile::fake()->image('blog-gallery-new.jpg', 1200, 800),
                ],
                'is_published' => true,
                'published_at' => now()->format('Y-m-d H:i:s'),
            ])
            ->assertSessionHasNoErrors()
            ->assertRedirect();

        $blog->refresh();

        Storage::disk('public')->assertMissing($oldThumbnail);
        $oldImages->each(fn (string $path) => Storage::disk('public')->assertMissing($path));
        $this->assertStringStartsWith('/storage/blogs/thumbnails/', $blog->thumbnail);
        $this->assertCount(1, $blog->images);
        Storage::disk('public')->assertExists(Str::after($blog->thumbnail, '/storage/'));
        Storage::disk('public')->assertExists(Str::after($blog->images[0], '/storage/'));

        $this->actingAs($admin)
            ->post(route('admin.team-members.store'), [
                'name' => 'Arman Saputra',
                'role' => 'Backend Engineer',
                'photo_file' => UploadedFile::fake()->image('arman.jpg', 800, 800),
                'description' => 'Mengelola API dan integrasi sistem.',
                'linkedin' => 'https://linkedin.com/in/arman',
                'github' => 'https://github.com/arman',
                'sort_order' => 3,
                'is_active' => true,
            ])
            ->assertSessionHasNoErrors()
            ->assertRedirect();

        $teamMember = TeamMember::query()->where('name', 'Arman Saputra')->firstOrFail();

        $this->assertStringStartsWith('/storage/team-members/', $teamMember->photo);
        Storage::disk('public')->assertExists(Str::after($teamMember->photo, '/storage/'));

        $oldPhoto = Str::after($teamMember->photo, '/storage/');

        $this->actingAs($admin)
            ->post(route('admin.team-members.update', $teamMember), [
                '_method' => 'put',
                'name' => 'Arman Saputra',
                'role' => 'Lead Backend Engineer',
                'photo_file' => UploadedFile::fake()->image('arman-new.jpg', 800, 800),
                'description' => 'Mengelola API dan integrasi sistem.',
                'linkedin' => 'https://linkedin.com/in/arman',
                'github' => 'https://github.com/arman',
                'sort_order' => 3,
                'is_active' => true,
            ])
            ->assertSessionHasNoErrors()
            ->assertRedirect();

        $teamMember->refresh();

        Storage::disk('public')->assertMissing($oldPhoto);
        $this->assertStringStartsWith('/storage/team-members/', $teamMember->photo);
        Storage::disk('public')->assertExists(Str::after($teamMember->photo, '/storage/'));
    }
}

<?php

namespace Tests\Feature;

use App\Models\Blog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BlogTest extends TestCase
{
    use RefreshDatabase;

    public function test_blog_index_can_be_viewed(): void
    {
        Blog::factory()->count(2)->create();

        $response = $this->get('/blog');

        $response->assertOk();
    }

    public function test_published_blog_detail_can_be_viewed(): void
    {
        $blog = Blog::factory()->create([
            'is_published' => true,
            'published_at' => now(),
        ]);

        $response = $this->get(route('blog.show', $blog));

        $response->assertOk();
    }

    public function test_unpublished_blog_detail_returns_not_found(): void
    {
        $blog = Blog::factory()->create([
            'is_published' => false,
            'published_at' => now(),
        ]);

        $response = $this->get(route('blog.show', $blog));

        $response->assertNotFound();
    }
}

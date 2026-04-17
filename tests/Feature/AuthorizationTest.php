<?php

namespace Tests\Feature;

use App\Models\Testimonial;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_admin_dashboard_to_login(): void
    {
        $response = $this->get('/admin/dashboard');

        $response->assertRedirect('/login');
    }

    public function test_admin_can_access_admin_dashboard(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get('/admin/dashboard');

        $response->assertOk();
    }

    public function test_user_cannot_access_admin_dashboard(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/admin/dashboard');

        $response->assertForbidden();
    }

    public function test_admin_login_redirects_to_admin_dashboard(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->post('/login', [
            'email' => $admin->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(RouteServiceProvider::ADMIN_HOME);
    }

    public function test_user_login_redirects_to_user_dashboard(): void
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(RouteServiceProvider::HOME);
    }

    public function test_authenticated_admin_is_redirected_away_from_guest_pages(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get('/login');

        $response->assertRedirect(RouteServiceProvider::ADMIN_HOME);
    }

    public function test_authenticated_user_is_redirected_away_from_guest_pages(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/register');

        $response->assertRedirect(RouteServiceProvider::HOME);
    }

    public function test_guest_cannot_submit_testimonial(): void
    {
        $response = $this->post('/testimonials', [
            'rating' => 5,
            'comment' => 'Aplikasi berjalan rapi dan mudah digunakan.',
        ]);

        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_submit_testimonial(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/testimonials', [
            'rating' => 5,
            'comment' => 'Aplikasi berjalan rapi dan mudah digunakan.',
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        $this->assertDatabaseHas('testimonials', [
            'user_id' => $user->id,
            'rating' => 5,
            'comment' => 'Aplikasi berjalan rapi dan mudah digunakan.',
            'is_approved' => false,
        ]);
    }

    public function test_testimonial_rating_must_be_between_one_and_five(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->from('/')->post('/testimonials', [
            'rating' => 6,
            'comment' => 'Rating ini tidak valid.',
        ]);

        $response->assertRedirect('/');
        $response->assertSessionHasErrors('rating');
        $this->assertSame(0, Testimonial::count());
    }
}

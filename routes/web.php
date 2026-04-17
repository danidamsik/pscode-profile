<?php

use App\Http\Controllers\Admin\BlogController as AdminBlogController;
use App\Http\Controllers\Admin\ContactController as AdminContactController;
use App\Http\Controllers\Admin\PortfolioController as AdminPortfolioController;
use App\Http\Controllers\Admin\TeamMemberController as AdminTeamMemberController;
use App\Http\Controllers\Admin\TestimonialController as AdminTestimonialController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TestimonialController;
use App\Models\Blog;
use App\Models\Contact;
use App\Models\OrderStep;
use App\Models\Portfolio;
use App\Models\Service;
use App\Models\TeamMember;
use App\Models\Testimonial;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
        'services' => Service::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get(['id', 'title', 'slug', 'description', 'icon']),
        'orderSteps' => OrderStep::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get(['id', 'title', 'description', 'sort_order']),
        'portfolios' => Portfolio::query()
            ->with(['images:id,portfolio_id,image,alt_text,sort_order'])
            ->where('is_published', true)
            ->orderBy('sort_order')
            ->orderByDesc('published_at')
            ->get([
                'id',
                'title',
                'slug',
                'category',
                'client_name',
                'short_description',
                'description',
                'technologies',
                'thumbnail',
                'project_url',
                'is_featured',
            ]),
        'testimonials' => Testimonial::query()
            ->with(['user:id,name,avatar'])
            ->where('is_approved', true)
            ->latest()
            ->take(6)
            ->get(['id', 'user_id', 'rating', 'comment', 'created_at']),
        'latestBlogs' => Blog::query()
            ->where('is_published', true)
            ->whereNotNull('published_at')
            ->latest('published_at')
            ->take(3)
            ->get(['id', 'title', 'slug', 'excerpt', 'thumbnail', 'published_at']),
        'teamMembers' => TeamMember::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get(['id', 'name', 'role', 'photo', 'description', 'social_links']),
    ]);
})->name('home');

Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{blog:slug}', [BlogController::class, 'show'])->name('blog.show');
Route::post('/contacts', [ContactController::class, 'store'])->name('contacts.store');

Route::get('/dashboard', function () {
    if (request()->user()->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }

    return redirect()->route('home');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', function () {
            return Inertia::render('Admin/Dashboard', [
                'stats' => [
                    'portfolios' => Portfolio::count(),
                    'testimonials' => Testimonial::count(),
                    'pendingTestimonials' => Testimonial::where('is_approved', false)->count(),
                    'blogs' => Blog::count(),
                    'contacts' => Contact::count(),
                    'unreadContacts' => Contact::where('status', 'unread')->count(),
                    'teamMembers' => TeamMember::count(),
                ],
            ]);
        })->name('dashboard');

        Route::resource('portfolios', AdminPortfolioController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::resource('testimonials', AdminTestimonialController::class)->only(['index', 'update', 'destroy']);
        Route::resource('blogs', AdminBlogController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::resource('contacts', AdminContactController::class)->only(['index', 'update', 'destroy']);
        Route::resource('team-members', AdminTeamMemberController::class)->only(['index', 'store', 'update', 'destroy']);
    });

Route::post('/testimonials', [TestimonialController::class, 'store'])
    ->middleware(['auth', 'verified', 'role:user'])
    ->name('testimonials.store');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

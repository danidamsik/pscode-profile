<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\Contact;
use App\Models\OrderStep;
use App\Models\Portfolio;
use App\Models\Service;
use App\Models\TeamMember;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@pscode.test'],
            [
                'name' => 'Admin PSCode',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'avatar' => 'https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=crop&w=240&q=80',
                'email_verified_at' => now(),
            ]
        );

        $clientA = User::updateOrCreate(
            ['email' => 'client@pscode.test'],
            [
                'name' => 'Client Demo',
                'password' => Hash::make('password'),
                'role' => 'user',
                'avatar' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=240&q=80',
                'email_verified_at' => now(),
            ]
        );

        $clientB = User::updateOrCreate(
            ['email' => 'sarah@company.test'],
            [
                'name' => 'Sarah Wijaya',
                'password' => Hash::make('password'),
                'role' => 'user',
                'avatar' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=240&q=80',
                'email_verified_at' => now(),
            ]
        );

        $demoUsers = [
            ['email' => 'user1@pscode.test', 'avatar' => 'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?auto=format&fit=crop&w=240&q=80'],
            ['email' => 'user2@pscode.test', 'avatar' => 'https://images.unsplash.com/photo-1580489944761-15a19d654956?auto=format&fit=crop&w=240&q=80'],
            ['email' => 'user3@pscode.test', 'avatar' => 'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?auto=format&fit=crop&w=240&q=80'],
        ];

        foreach ($demoUsers as $index => $demoUser) {
            User::updateOrCreate(
                ['email' => $demoUser['email']],
                [
                    'name' => 'Demo User '.($index + 1),
                    'password' => Hash::make('password'),
                    'role' => 'user',
                    'avatar' => $demoUser['avatar'],
                    'email_verified_at' => now(),
                ]
            );
        }

        $services = [
            [
                'title' => 'Website Company Profile',
                'slug' => 'website-company-profile',
                'description' => 'Membangun website profesional untuk memperkuat branding, kredibilitas, dan komunikasi bisnis.',
                'icon' => 'monitor',
                'sort_order' => 1,
            ],
            [
                'title' => 'Web Application',
                'slug' => 'web-application',
                'description' => 'Pengembangan sistem berbasis web untuk operasional, dashboard, katalog, dan kebutuhan internal perusahaan.',
                'icon' => 'database',
                'sort_order' => 2,
            ],
            [
                'title' => 'Mobile Application',
                'slug' => 'mobile-application',
                'description' => 'Pembuatan aplikasi mobile yang responsif, mudah digunakan, dan siap dikembangkan sesuai proses bisnis.',
                'icon' => 'smartphone',
                'sort_order' => 3,
            ],
            [
                'title' => 'UI/UX Design',
                'slug' => 'ui-ux-design',
                'description' => 'Perancangan antarmuka dan pengalaman pengguna yang bersih, terarah, dan nyaman digunakan.',
                'icon' => 'palette',
                'sort_order' => 4,
            ],
        ];

        foreach ($services as $service) {
            Service::updateOrCreate(
                ['slug' => $service['slug']],
                $service + ['is_active' => true]
            );
        }

        $steps = [
            ['title' => 'Konsultasi Kebutuhan', 'description' => 'Diskusi awal untuk memahami tujuan, fitur utama, target pengguna, dan prioritas project.', 'sort_order' => 1],
            ['title' => 'Proposal dan Estimasi', 'description' => 'Tim menyiapkan ruang lingkup, estimasi timeline, dan penawaran kerja yang transparan.', 'sort_order' => 2],
            ['title' => 'Desain dan Development', 'description' => 'UI, frontend, backend, dan database dikerjakan secara bertahap dengan review berkala.', 'sort_order' => 3],
            ['title' => 'Testing dan Revisi', 'description' => 'Project diuji dari sisi fungsi, tampilan, dan alur pengguna sebelum siap dipublikasikan.', 'sort_order' => 4],
            ['title' => 'Launch dan Support', 'description' => 'Project dipublikasikan dan didampingi agar berjalan stabil setelah rilis.', 'sort_order' => 5],
        ];

        foreach ($steps as $step) {
            OrderStep::updateOrCreate(
                ['sort_order' => $step['sort_order']],
                $step + ['is_active' => true]
            );
        }

        $members = [
            ['name' => 'Raka Pratama', 'role' => 'Project Manager', 'photo' => 'images/team/raka.jpg', 'description' => 'Mengatur arah project, komunikasi client, dan prioritas delivery.', 'sort_order' => 1],
            ['name' => 'Maya Lestari', 'role' => 'UI/UX Designer', 'photo' => 'images/team/maya.jpg', 'description' => 'Merancang pengalaman pengguna dan visual interface yang mudah dipahami.', 'sort_order' => 2],
            ['name' => 'Dimas Putra', 'role' => 'Full Stack Developer', 'photo' => 'images/team/dimas.jpg', 'description' => 'Membangun frontend, backend, integrasi API, dan struktur database.', 'sort_order' => 3],
            ['name' => 'Nadia Kirana', 'role' => 'Quality Assurance', 'photo' => 'images/team/nadia.jpg', 'description' => 'Memastikan fitur berjalan stabil melalui pengujian fungsional dan regresi.', 'sort_order' => 4],
        ];

        foreach ($members as $member) {
            TeamMember::updateOrCreate(
                ['name' => $member['name']],
                $member + [
                    'social_links' => ['linkedin' => 'https://linkedin.com', 'github' => 'https://github.com'],
                    'is_active' => true,
                ]
            );
        }

        $portfolioData = [
            [
                'title' => 'Sistem Booking Klinik',
                'slug' => 'sistem-booking-klinik',
                'category' => 'website',
                'client_name' => 'Klinik Sehat Sentosa',
                'short_description' => 'Platform reservasi dokter dan manajemen jadwal klinik.',
                'description' => 'Sistem booking klinik dengan fitur jadwal dokter, antrian pasien, notifikasi, dan dashboard admin.',
                'technologies' => ['Laravel', 'Vue', 'Inertia', 'Tailwind CSS', 'MySQL'],
                'thumbnail' => 'images/portfolio/booking-klinik/cover.jpg',
                'project_url' => 'https://example.com/booking-klinik',
                'is_featured' => true,
                'sort_order' => 1,
                'images' => [
                    ['image' => 'images/portfolio/booking-klinik/dashboard.jpg', 'alt_text' => 'Dashboard booking klinik', 'sort_order' => 1],
                    ['image' => 'images/portfolio/booking-klinik/jadwal.jpg', 'alt_text' => 'Halaman jadwal dokter', 'sort_order' => 2],
                    ['image' => 'images/portfolio/booking-klinik/mobile.jpg', 'alt_text' => 'Tampilan mobile booking', 'sort_order' => 3],
                ],
            ],
            [
                'title' => 'Aplikasi Inventory Gudang',
                'slug' => 'aplikasi-inventory-gudang',
                'category' => 'website',
                'client_name' => 'Nusantara Logistic',
                'short_description' => 'Dashboard stok barang, mutasi gudang, dan laporan real-time.',
                'description' => 'Aplikasi inventory untuk memantau stok, barang masuk keluar, level minimum, dan laporan gudang.',
                'technologies' => ['Laravel', 'Vue', 'MySQL', 'Tailwind CSS'],
                'thumbnail' => 'images/portfolio/inventory/cover.jpg',
                'project_url' => null,
                'is_featured' => true,
                'sort_order' => 2,
                'images' => [
                    ['image' => 'images/portfolio/inventory/stok.jpg', 'alt_text' => 'Halaman stok barang', 'sort_order' => 1],
                    ['image' => 'images/portfolio/inventory/laporan.jpg', 'alt_text' => 'Halaman laporan inventory', 'sort_order' => 2],
                ],
            ],
            [
                'title' => 'Mobile App Pemesanan Laundry',
                'slug' => 'mobile-app-pemesanan-laundry',
                'category' => 'mobile',
                'client_name' => 'FreshWash',
                'short_description' => 'Aplikasi pemesanan laundry dengan tracking status order.',
                'description' => 'Aplikasi mobile untuk pemesanan pickup laundry, kalkulasi layanan, tracking status, dan riwayat transaksi.',
                'technologies' => ['Flutter', 'Laravel API', 'MySQL'],
                'thumbnail' => 'images/portfolio/laundry/cover.jpg',
                'project_url' => null,
                'is_featured' => false,
                'sort_order' => 3,
                'images' => [
                    ['image' => 'images/portfolio/laundry/home.jpg', 'alt_text' => 'Home mobile laundry', 'sort_order' => 1],
                    ['image' => 'images/portfolio/laundry/order.jpg', 'alt_text' => 'Form pemesanan laundry', 'sort_order' => 2],
                    ['image' => 'images/portfolio/laundry/tracking.jpg', 'alt_text' => 'Tracking order laundry', 'sort_order' => 3],
                ],
            ],
        ];

        foreach ($portfolioData as $item) {
            $images = $item['images'];
            unset($item['images']);

            $portfolio = Portfolio::updateOrCreate(
                ['slug' => $item['slug']],
                $item + [
                    'is_published' => true,
                    'published_at' => now()->subDays($item['sort_order'] * 5),
                ]
            );

            $portfolio->images()->delete();
            $portfolio->images()->createMany($images);

            $portfolio->teamMembers()->delete();
            $portfolio->teamMembers()->createMany([
                ['name' => 'Raka Pratama', 'role' => 'Project Manager', 'photo' => 'images/team/raka.jpg', 'description' => 'Mengelola scope dan komunikasi project.', 'sort_order' => 1],
                ['name' => 'Maya Lestari', 'role' => 'UI/UX Designer', 'photo' => 'images/team/maya.jpg', 'description' => 'Merancang flow dan tampilan utama.', 'sort_order' => 2],
                ['name' => 'Dimas Putra', 'role' => 'Developer', 'photo' => 'images/team/dimas.jpg', 'description' => 'Membangun frontend dan backend project.', 'sort_order' => 3],
            ]);
        }

        Testimonial::updateOrCreate(
            ['user_id' => $clientA->id],
            [
                'rating' => 5,
                'comment' => 'Tim PSCode komunikatif dan hasil websitenya rapi sesuai kebutuhan bisnis kami.',
                'is_approved' => true,
            ]
        );

        Testimonial::updateOrCreate(
            ['user_id' => $clientB->id],
            [
                'rating' => 4,
                'comment' => 'Proses pengerjaan jelas, revisi cepat, dan dashboard admin mudah digunakan.',
                'is_approved' => true,
            ]
        );

        $blogs = [
            [
                'title' => 'Mengapa Company Profile Penting untuk Bisnis Digital',
                'slug' => 'mengapa-company-profile-penting-untuk-bisnis-digital',
                'excerpt' => 'Website company profile membantu calon client memahami bisnis, layanan, dan kredibilitas perusahaan.',
                'content' => 'Website company profile menjadi etalase utama bagi bisnis yang ingin tampil profesional. Melalui halaman layanan, portfolio, dan testimoni, calon client dapat menilai kemampuan perusahaan sebelum memulai kerja sama.',
                'thumbnail' => 'images/blog/company-profile.jpg',
            ],
            [
                'title' => 'Tips Menyiapkan Brief Project Website',
                'slug' => 'tips-menyiapkan-brief-project-website',
                'excerpt' => 'Brief yang jelas membantu proses desain dan development berjalan lebih cepat dan terarah.',
                'content' => 'Brief project sebaiknya memuat tujuan website, target pengguna, referensi visual, fitur utama, dan prioritas rilis. Semakin jelas brief yang diberikan, semakin mudah tim menyusun estimasi dan roadmap pengerjaan.',
                'thumbnail' => 'images/blog/project-brief.jpg',
            ],
            [
                'title' => 'Perbedaan Website Company Profile dan Web Application',
                'slug' => 'perbedaan-website-company-profile-dan-web-application',
                'excerpt' => 'Keduanya sama-sama berbasis web, tetapi tujuan dan kompleksitas fiturnya berbeda.',
                'content' => 'Company profile fokus pada branding dan informasi publik. Web application fokus pada proses bisnis, data, autentikasi, dashboard, dan automasi workflow. Memahami perbedaan ini membantu bisnis memilih solusi yang tepat.',
                'thumbnail' => 'images/blog/web-app.jpg',
            ],
        ];

        foreach ($blogs as $index => $blog) {
            Blog::updateOrCreate(
                ['slug' => $blog['slug']],
                $blog + [
                    'images' => ['images/blog/content-'.($index + 1).'.jpg'],
                    'is_published' => true,
                    'published_at' => now()->subDays($index + 1),
                ]
            );
        }

        Contact::updateOrCreate(
            ['email' => 'andi@example.com'],
            [
                'name' => 'Andi Prasetyo',
                'message' => 'Saya ingin konsultasi pembuatan website company profile untuk usaha jasa.',
                'status' => 'unread',
            ]
        );

        Contact::updateOrCreate(
            ['email' => 'marketing@nusantara.test'],
            [
                'name' => 'Nusantara Group',
                'message' => 'Mohon informasi estimasi untuk pembuatan sistem internal berbasis web.',
                'status' => 'read',
            ]
        );
    }
}

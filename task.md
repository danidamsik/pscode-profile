## Phase 0 - Setup Dependency & Environment
### Task
- Pastikan project Laravel sudah tersedia
- Install Laravel Breeze, vue 3, inertia js
- Setup authentication dasar dari Breeze
- Install dan konfigurasi Tailwind CSS
- Pastikan Vite berjalan dengan baik
- Konfigurasi file frontend utama
- Konfigurasi file routing awal
- Konfigurasi layout dasar untuk auth
- Pastikan halaman login, register, dan dashboard default dapat diakses
- Pastikan build asset frontend berjalan normal
- Pastikan integrasi Laravel + Vue + Inertia + Tailwind berjalan baik
- Verifikasi project dapat dijalankan tanpa error dasar

## Phase 1 - Setup Migration, Relasi Model & seeder
### Task
- Analisis kebutuhan project website company profile pada file teknikal_dokumen.md
- buat migration sesuai kebutuhan yg ada di file teknikal_dokumen.md
- buat model dan relasi nya
- buat seeder untuk kebutuhan testing nanti

## Phase 2 - Authentication & Authorization
### Task
- Tambahkan role admin dan user pada sistem
- Buat middleware role
- Pastikan hanya admin yang dapat mengakses dashboard admin
- auto redirect jika sudah login
- Pastikan guest dapat melihat seluruh konten publik
- Pastikan user login diperlukan untuk fitur komentar dan rating testimoni

## Phase 3 - Struktur Layout & Navigasi Frontend
### Task
- Buat layout utama website guest/user
- Buat navbar utama
- Buat footer utama
- Buat wrapper / container section reusable
- Buat komponen section heading
- Buat komponen card dasar
- Buat komponen button reusable
- Buat komponen input reusable
- Buat struktur landing page
- Pastikan layout responsive
- Pastikan tampilan mobile-first
- Pastikan desain konsisten secara typography, spacing, dan hierarchy visual
- Tentukan gaya visual modern dan profesional
- Siapkan struktur navigasi menuju setiap section utama

## Phase 4 - Hero Section & Tentang Kami
### Task
- Buat hero section
- Tambahkan headline utama yang kuat
- Tambahkan subheadline yang menjelaskan jasa perusahaan
- Tambahkan call-to-action button
- Tambahkan elemen visual pendukung bila diperlukan
- Buat section tentang perusahaan
- Tampilkan deskripsi singkat perusahaan
- Tampilkan visi / nilai / keunggulan perusahaan
- Buat section informasi tim pengembang (tampilkan di modal)
- Tampilkan informasi singkat anggota tim
- Tampilkan peran tim pengembang jika diperlukan
- Pastikan semua elemen tampil profesional dan mudah dipahami

## Phase 5 - Layanan & Alur Pemesanan
### Task
- Buat section layanan
- Tampilkan layanan pembuatan software
- Tampilkan penjelasan singkat layanan
- Gunakan card atau komponen visual yang rapi
- Buat section alur pemesanan
- Tampilkan tahapan pemesanan dari awal sampai selesai
- Susun langkah pemesanan agar mudah dipahami calon client
- Gunakan komponen step / timeline / flow visual
- Pastikan section ini memperjelas proses kerja perusahaan

## Phase 6 - Portofolio
### Task
- Buat section portofolio
- Buat card portfolio
- buat modal untuk project yg di klik client
- Tampilkan judul project
- Tampilkan deskripsi singkat project
- Tampilkan thumbnail / image (gunakan slidder karena gambar lebih dari 1)
- Tampilkan kategori project (mobile atau website)
- Tampilkan teknologi yang digunakan 
- Pastikan tampilan portfolio menarik secara visual

## Phase 7 - Testimoni, Rating, dan Komentar
### Task
- Buat section testimoni
- Tampilkan daftar testimoni client
- Tampilkan rating secara visual
- Tampilkan nama user pemberi testimoni
- Buat form komentar dan rating
- Pastikan hanya user login yang dapat memberi testimoni
- Pastikan guest hanya dapat melihat testimoni
- Validasi rating hanya 1 sampai 5
- Hubungkan testimonial dengan data user
- Tambahkan validasi form komentar
- Pastikan tampilan testimoni tetap rapi dan profesional

## Phase 8 - Blog
### Task
- Buat halaman / section blog
- Buat list artikel blog
- Buat card artikel
- Tampilkan judul artikel
- Tampilkan ringkasan / excerpt
- Tampilkan thumbnail
- Tampilkan tanggal publish
- Tampilkan slug atau routing detail artikel
- Buat halaman detail artikel
- Tampilkan isi artikel lengkap
- Hubungkan data blog ke database
- Tambahkan pagination bila diperlukan

## Phase 9 - Kontak
### Task
- Buat section kontak
- Buat form kontak
- Tambahkan field:
  - nama
  - email
  - pesan
- Tambahkan validasi input
- Simpan data pesan ke database
- Tampilkan informasi kontak perusahaan
- Tambahkan CTA yang jelas
- Tambahkan feedback sukses / gagal submit
- Pastikan form mudah digunakan
- Pastikan desain section kontak konsisten dengan tema website

## Phase 10 - Dashboard Admin
### Task
- Buat layout dashboard admin
- Buat sidebar / navigasi admin
- Buat halaman dashboard ringkas
- Buat halaman admin untuk mengelola portfolio
- Buat halaman admin untuk mengelola testimonial
- Buat halaman admin untuk mengelola blog
- Buat halaman admin untuk melihat data kontak
- buat halaman admin untuk mengelola data tim
- Buat tabel data
- Buat form create (modal reusable)
- Buat form edit (modal reusable)
- Buat aksi delete (modal delete)
- Buat validasi form admin
- buat component toast notification SPA (bukan session flash)
- Pastikan route admin terproteksi
- Pastikan desain admin cukup rapi dan konsisten
## 1. Project Overview

**Nama Project:**
Company Profile Website - Digital Agency

**Tagline:**
Solusi Digital Modern untuk Website & Mobile Apps

**Deskripsi:**
Website company profile ini dibuat untuk menampilkan informasi perusahaan yang bergerak di bidang jasa pembuatan website dan mobile apps. Website ini berfungsi sebagai media branding, promosi layanan, serta interaksi dengan calon client melalui portfolio, blog, dan testimoni.

**Tujuan:**
- Meningkatkan branding perusahaan secara profesional
- Menarik calon client melalui tampilan modern dan informatif
- Menyediakan media komunikasi dan interaksi dengan user

**Target Pengguna:**
- Calon client (individu / perusahaan)
- Visitor umum yang ingin mengetahui layanan digital

### Scope

**In Scope:**
- Halaman landing (hero section)
- Halaman tentang perusahaan & tim
- Halaman layanan dan alur pemesanan
- Portfolio project
- Testimoni client (dengan login untuk komentar & rating)
- Blog artikel
- Kontak & form inquiry
- Dashboard admin untuk mengelola konten

**Out of Scope:**
- Sistem pembayaran online
- Marketplace layanan
- Mobile apps (native)

---

## 2. Problem & Solution

**Problem:**
- Tidak adanya platform profesional untuk menampilkan jasa
- Sulit membangun kepercayaan client tanpa portfolio & testimoni
- Kurangnya media komunikasi yang terstruktur dengan calon client

**Solution:**
- Membangun website company profile modern & profesional
- Menyediakan portfolio dan testimoni untuk meningkatkan trust
- Menyediakan fitur blog untuk edukasi dan SEO
- Menyediakan form kontak untuk komunikasi cepat

---

## 3. Tech Stack

| Layer | Teknologi |
|-------|-----------|
| **Frontend** | Vue 3, Inertia.js, Tailwind CSS |
| **Backend** | Laravel |
| **Database** | MySQL |

---

## 4. Core Features

| Fitur | Deskripsi |
|-------|-----------|
| Hero Section | Menampilkan headline, CTA, dan branding utama |
| Tentang Kami | Informasi perusahaan dan tim developer |
| Layanan | Daftar layanan dan alur pemesanan |
| Portfolio | Menampilkan hasil project yang pernah dibuat |
| Testimoni | Review client dengan fitur rating & komentar |
| Blog | Artikel edukasi dan SEO |
| Kontak | Form untuk menghubungi perusahaan |
| Admin Panel | Mengelola seluruh konten website |

---

## 5. User Flow

### Admin Flow
1. Login ke dashboard admin
2. Mengelola konten (blog, portfolio, layanan, testimoni)
3. Mengelola user & komentar
4. Publish / update konten

### User Flow
1. Mengunjungi website (landing page)
2. Melihat layanan dan portfolio
3. Membaca blog
4. Login untuk memberikan testimoni
5. Mengisi form kontak

---

## 6. Database Schema

```
users {
  id           BIGINT
  name         VARCHAR
  email        VARCHAR
  password     VARCHAR
  role         ENUM (admin, user)
  created_at   TIMESTAMP
  updated_at   TIMESTAMP
}

portfolios {
  id           BIGINT
  title        VARCHAR
  description  TEXT
  created_at   TIMESTAMP
}

portfolio_images {
  id            BIGINT
  portfolio_id  BIGINT
  image         VARCHAR
  created_at    TIMESTAMP
}

portfolio_team_members {
  id             BIGINT
  portfolio_id   BIGINT
  name           VARCHAR
  role           VARCHAR
  photo          VARCHAR
  description    TEXT
  created_at     TIMESTAMP
  updated_at     TIMESTAMP
}

testimonials {
  id           BIGINT
  user_id      BIGINT
  rating       INT
  comment      TEXT
  created_at   TIMESTAMP
}

blogs {
  id           BIGINT
  title        VARCHAR
  content      TEXT
  images       VARCHAR         
  created_at   TIMESTAMP
  updated_at   TIMESTAMP
}

contacts {
  id           BIGINT
  name         VARCHAR
  email        VARCHAR
  message      TEXT
  created_at   TIMESTAMP
}
```

---

## 7. Authentication & Authorization

**Authentication:**
- Laravel Breeze

**Authorization:**
- Role-Based Access Control (RBAC)
- Middleware

**Roles:**

| Role | Akses |
|------|-------|
| Admin | Mengelola seluruh konten website |
| User | Melihat konten, memberi testimoni & komentar |

---

## 8. Business Rules & Validation

- Email harus unik
- User harus login untuk memberikan testimoni
- Rating hanya bisa 1–5
- Admin dapat menghapus komentar yang tidak sesuai
- Konten blog harus memiliki judul dan isi
- Form kontak wajib diisi lengkap

---

## 9. UI Component & Design System

**Framework:** Tailwind CSS

**Components:**
- Button
- Input
- Modal
- Card
- Navbar
- Footer
- Table
- Rating Component

**Guidelines:**
- Modern & clean design
- Mobile-first design
- Konsistensi warna dan typography
- Komponen reusable

---

## 10. Security

- CSRF Protection (Laravel default)
- XSS Protection melalui Vue binding
- Validasi input (frontend & backend)
- Rate limiting pada form
- Password hashing menggunakan bcrypt
- Proteksi akses admin menggunakan middleware

---

## 11. Performance & Scalability

- Pagination pada blog & portfolio
- Lazy loading gambar
- Eager loading relasi database
- Caching data tertentu
- Optimasi asset (minify CSS & JS)

---

## 12. Testing Strategy

**Backend:**
- Unit Testing (PHPUnit)
- Feature Testing

**Frontend:**
- Vitest

---

## 13. Environment & Configuration

**Environment:**
- Local


**Konfigurasi Utama (`.env`):**

```env
APP_NAME=CompanyProfileApp
APP_ENV=local
APP_KEY=

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=company_profile
DB_USERNAME=root
DB_PASSWORD=
```

---

## 14. Coding Conventions

### Backend (Laravel)
- Standar penulisan: **PSR-12**
- Arsitektur: **Service Layer**
- Penamaan kolom database: `snake_case`
- Penamaan variabel PHP: `camelCase`

### Frontend (Vue)
- Gunakan **Composition API**
- Penamaan komponen: `PascalCase`
- Props dan emits harus didefinisikan dengan jelas

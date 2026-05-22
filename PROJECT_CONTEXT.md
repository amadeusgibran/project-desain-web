# Project Context: Portfolio Gibran Studio

## Ringkasan Project
Portfolio Gibran Studio adalah aplikasi web portfolio berbasis Laravel untuk fotografer. Website ini menampilkan profil fotografer, seri foto, halaman detail portfolio, halaman kontak, visual interaktif, dan panel Profile Assistant sederhana. Project ini berfokus pada nuansa editorial, komposisi visual minimalis, dan narasi fotografi.

## Identitas
- Nama project: Portfolio Gibran Studio
- Jenis aplikasi: Website portfolio fotografi personal
- Target pengguna: Calon klien fotografi, art director, brand, editor, dosen, atau reviewer project
- Bahasa utama UI: Indonesia dan Inggris

## Stack Teknologi
- Backend: Laravel 12
- PHP: ^8.2
- Frontend templating: Blade
- Build tool: Vite
- Styling: Tailwind CSS 4 dan CSS custom
- Interaksi 3D: Three.js
- Package AI/dev helper: Laravel Boost
- Testing: PHPUnit dan Playwright dependency

## Struktur Utama
- `routes/web.php`: Menyimpan route publik, route admin auth, dan route CRUD project.
- `app/Models/Project.php`: Model Eloquent untuk seri foto portfolio.
- `app/Http/Controllers/ProjectController.php`: CRUD admin untuk project.
- `app/Http/Controllers/CategoryController.php`: CRUD ringan untuk kategori portfolio.
- `app/Http/Controllers/AuthController.php`: Login/logout admin manual.
- `app/Http/Requests`: Validasi form create/update project.
- `resources/views/portfolio.blade.php`: View utama untuk halaman About, Portfolio, dan Contact.
- `resources/js/app.js`: Logic karakter 3D, rotasi pointer, dan AI Assistant panel.
- `resources/css/app.css`: Styling utama seluruh halaman.
- `public/images`: Asset gambar portfolio, kontak, dan karakter.
- `database/migrations`: Migration bawaan Laravel untuk user, cache, job, dan session.

## Fitur Saat Ini
1. Halaman About
   - Menampilkan hero profile.
   - Menampilkan visual interaktif.
   - Menampilkan filosofi fotografi dan fokus karya.

2. Halaman Portfolio
   - Menampilkan daftar seri foto.
   - Data project diambil dari tabel `projects`.
   - Setiap project memiliki title, slug, category, image, description, client, year, tools, images, dan link.
   - Setiap card mengarah ke halaman detail portfolio.

3. Halaman Contact
   - Menampilkan email, social link, dan availability.
   - Memiliki form kontak secara tampilan.
   - Form belum terhubung ke route penyimpanan.

4. AI Assistant Panel
   - Tombol AI Assistant membuka panel samping.
   - Panel mengambil data JSON dari endpoint `/profile/ai-insight`.
   - Insight masih berupa response lokal berbasis data profile statis.

5. Halaman Detail Portfolio
   - Menampilkan hero image, informasi client, tahun, production/tools, cerita seri, gallery, dan prev/next navigation.

6. Visual Interaktif
   - Dibangun dengan Three.js.
   - Bisa berotasi otomatis dan diputar manual dengan pointer drag.

## Route Aktif
- `GET /`: Halaman About.
- `GET /portfolio`: Halaman Portfolio.
- `GET /portfolio/{slug}`: Halaman detail portfolio berdasarkan slug.
- `GET /contact`: Halaman Contact.
- `GET /profile/ai-insight`: Endpoint JSON untuk AI Assistant panel.

## Status CRUD
Project ini sudah memiliki CRUD portfolio untuk admin. Data project berasal dari tabel `projects`, dikelola melalui `/admin/projects`, dan dilindungi middleware `auth`.

CRUD yang tersedia:
- Create project dengan cover image dan gallery.
- Read/list project di dashboard admin.
- Update metadata, cover image, gallery, status publish, dan urutan.
- Delete project.
- Drag-and-drop reorder project memakai SortableJS.
- Slug project dibuat otomatis dari title.
- Category dipilih dari dropdown dan dikelola lewat `/admin/categories`.
- Gallery upload memakai multi-file dropzone dengan preview sebelum submit.

## Batasan Saat Ini
- Data profile masih hardcoded.
- Form contact belum menyimpan data.
- Dashboard admin baru mengelola portfolio project.
- Endpoint AI belum memakai provider AI eksternal.
- CRUD belum mencakup profile, contact message, atau social links.

## Arah Pengembangan
Project ini dapat dikembangkan menjadi aplikasi portfolio dinamis dengan panel admin. Tahap pengembangan yang disarankan:
1. Membuat penyimpanan pesan kontak.
2. Memindahkan data profile ke database atau config.
3. Membuat dashboard untuk mengelola profile dan pesan.
4. Menambahkan edit category dan proteksi hapus category yang sedang dipakai.
5. Menambahkan role admin bila user lebih dari satu.
6. Menghubungkan Profile Assistant ke data project yang lebih lengkap.

## Cara Menjalankan
```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
npm run build
php artisan serve
```

Buka aplikasi di:

```text
http://127.0.0.1:8000
```

Admin:

```text
http://127.0.0.1:8000/admin/login
email: admin@example.com
password: password
projects: /admin/projects
categories: /admin/categories
```

## Ringkasan Untuk Prompt AI
Project ini adalah Laravel 12 portfolio fotografi bernama Portfolio Gibran Studio. Tampilan dibangun dengan Blade, Vite, Tailwind CSS 4, CSS custom, dan visual interaktif Three.js. Aplikasi memiliki halaman About, Portfolio, Detail Portfolio, Contact, panel Profile Assistant lokal melalui endpoint `/profile/ai-insight`, serta dashboard admin `/admin/projects` untuk CRUD seri foto. Data project berasal dari tabel `projects`; data profile masih hardcoded. Pengembangan berikutnya adalah CRUD profile, penyimpanan pesan kontak, dan role admin.

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
- `routes/web.php`: Menyimpan route halaman dan data statis profile/project.
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
   - Data project masih disimpan sebagai array di `routes/web.php`.
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
Project ini belum mendukung CRUD penuh. Data profile dan portfolio masih hardcoded di `routes/web.php`, belum berasal dari database. Belum ada model, migration, controller, atau route resource untuk mengelola data portfolio/contact secara dinamis.

Untuk mendukung CRUD, project perlu ditambah:
- Migration tabel, misalnya `projects` atau `contacts`.
- Model Eloquent, misalnya `Project` atau `ContactMessage`.
- Controller resource, misalnya `ProjectController`.
- Route resource untuk create, read, update, dan delete.
- View form create/edit dan halaman list/detail.
- Validasi request.
- Integrasi form dengan route `POST`, `PUT/PATCH`, dan `DELETE`.

## Batasan Saat Ini
- Data profile dan project belum dinamis.
- Form contact belum menyimpan data.
- Belum ada autentikasi admin.
- Belum ada dashboard pengelolaan konten.
- Endpoint AI belum memakai provider AI eksternal.
- Database belum digunakan untuk konten portfolio.

## Arah Pengembangan
Project ini dapat dikembangkan menjadi aplikasi portfolio dinamis dengan panel admin. Tahap pengembangan yang disarankan:
1. Membuat CRUD portfolio project.
2. Membuat penyimpanan pesan kontak.
3. Membuat autentikasi admin.
4. Memindahkan data profile ke database atau config.
5. Membuat dashboard untuk mengelola profile, portfolio, dan pesan.
6. Menghubungkan AI Assistant ke data project yang lebih lengkap.

## Cara Menjalankan
```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
npm run build
php artisan serve
```

Buka aplikasi di:

```text
http://127.0.0.1:8000
```

## Ringkasan Untuk Prompt AI
Project ini adalah Laravel 12 portfolio fotografi bernama Portfolio Gibran Studio. Tampilan dibangun dengan Blade, Vite, Tailwind CSS 4, CSS custom, dan visual interaktif Three.js. Aplikasi memiliki halaman About, Portfolio, Detail Portfolio, Contact, dan panel Profile Assistant lokal melalui endpoint `/profile/ai-insight`. Data profile dan project masih hardcoded di `routes/web.php`, sehingga aplikasi belum mendukung CRUD. Pengembangan berikutnya adalah membuat model, migration, controller, route resource, dan dashboard admin untuk mengelola seri foto secara dinamis.

# Portfolio Gibran Studio

## Identitas Mahasiswa
- **Nama:** Gibran
- **NIM:** Isi NIM kamu
- **Kelas:** Isi kelas kamu

## Deskripsi
Project Laravel portfolio editorial dengan halaman About, Portfolio, Contact, panel AI Assistant berbasis Laravel Boost, dan karakter 3D rotasi memakai Three.js.

## Stack
- Laravel 12, versi terbaru yang kompatibel dengan PHP 8.2 lokal
- Blade
- Vite
- Tailwind CSS 4
- Three.js
- Laravel Boost

## Catatan AI
Laravel AI SDK terbaru (`laravel/ai` v0.7.0) membutuhkan PHP `^8.3`. Mesin ini memakai PHP `8.2.12`, jadi package itu belum bisa dipasang. Project ini memakai package AI resmi Laravel yang kompatibel, yaitu `laravel/boost`, dan menyediakan endpoint `/profile/ai-insight` untuk panel AI profile.

## Cara Menjalankan
```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
npm run build
php artisan serve
```

Buka `http://127.0.0.1:8000`.

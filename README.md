# Portfolio Gibran Studio

## Identitas Mahasiswa
- **Nama:** Gibran Amadeus Jastin
- **NRP:** 5124500017
- **Kelas:** D3 MMB A

## Deskripsi
Project Laravel portfolio fotografi editorial dengan halaman About, Portfolio, Contact, detail portfolio, panel profile assistant, dan visual interaktif.

## Stack
- Laravel 12, versi terbaru yang kompatibel dengan PHP 8.2 lokal
- Blade
- Vite
- Tailwind CSS 4
- Three.js
- Laravel Boost

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

Buka `http://127.0.0.1:8000`.

## Admin
- URL: `http://127.0.0.1:8000/admin/login`
- Email: `admin@example.com`
- Password: `password`
- Projects: `/admin/projects`
- Categories: `/admin/categories`
- Messages: `/admin/messages`
- Profile: `/admin/profile`

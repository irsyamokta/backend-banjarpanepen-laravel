# VisitBapen Backend

VisitBapen adalah backend API untuk mengelola **Website Desa Wisata Banjarpanepen**. Backend ini dibuat dengan Laravel dan mendukung autentikasi pengguna, manajemen wisata, artikel, dan paket wisata.

## Technology

- Laravel 12
- PHP 8.3.6
- MySQL
- JWT Auth
- Cookie-based Refresh Token
- CORS support untuk frontend (Vite/React)

## Features

- Register & Login pengguna
- Logout dan validasi token
- Manajemen data wisata
- Manajemen artikel berita
- Manajemen paket wisata
- Middleware JWT untuk proteksi endpoint

## Setup

1. Clone repo

```bash
git clone https://github.com/username/backend-banjarpanepen-laravel.git
cd backend-banjarpanepen-laravel
````
2. Install dependencies

```bash
git clone https://github.com/username/visitbapen-backend.git
cd visitbapen-backend
````

3. Konfigurasi .env

```bash
DB_CONNECTION=
DB_HOST=
DB_PORT=
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=

ACCESS_TOKEN_EXPIRES=
REFRESH_TOKEN_EXPIRES=

ACCESS_TOKEN_SECRET=
REFRESH_TOKEN_SECRET=

CLOUDINARY_KEY=
CLOUDINARY_SECRET=
CLOUDINARY_CLOUD_NAME=
CLOUDINARY_URL=
CLOUDINARY_UPLOAD_PRESET=ml_default
CLOUDINARY_NOTIFICATION_URL=
````

4. Jalankan migrasi

```bash
php artisan migrate
````

5. Jalankan server

```bash
php artisan serve
````

## Author
- [@irsyamokta](https://github.com/irsyamokta)


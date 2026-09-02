# Sahabat Aman

Sahabat Aman adalah aplikasi web berbasis Laravel untuk sistem curhat digital yang memungkinkan siswa mengirimkan cerita/keluhan kepada **guru** atau **siswa lain** dengan pendekatan yang aman, nyaman, dan terarah. Siswa berperan sebagai pengguna, sedangkan guru berperan sebagai admin yang mengelola data siswa dan data curhat.

## 1. Ringkasan Proyek

- Nama aplikasi: Sahabat Aman
- Platform: Web App
- Stack utama: Laravel + MySQL + Vite
- Bahasa: PHP, Blade, JavaScript
- Target pengguna: Siswa dan Guru

## 2. Stack Teknologi

- PHP: 8.2
- Laravel: 12
- Database: MySQL
- Server web: Laravel built-in server / Apache / Nginx
- Frontend asset: Vite + Tailwind CSS
- Session/Auth: Laravel Auth native

### Konfigurasi default yang dipakai project

- DB connection: MySQL
- Port default MySQL: 3306
- Session driver: database
- Queue: database
- Cache: database

## 3. Login Pakai Apa?

Sistem login pada aplikasi memakai autentikasi Laravel standard dengan:

- Email
- Password

Proses login ada di route `/login` dan menggunakan `AuthController` dengan validasi:

- `email` wajib diisi dan valid format email
- `password` wajib diisi

Untuk testing, tester dapat menggunakan data dummy berikut (disiapkan via seeder):

- `Siswa:`
  Adit
  email: `adit@gmail.com`
  password: `password`

  Udin
  email: `udin@gmail.com`
  password: `password`

- `Guru:`
  email: `guru@sahabataman.test`
  password: `password`

> Catatan: akun-akun ini dibuat melalui `DatabaseSeeder` (lihat bagian seeder di bawah).

### Role yang tersedia

Saat register, user memilih role:

- `siswa`
- `guru`

Setelah login:

- `siswa` diarahkan ke halaman `/curhat` (menu: Curhat, Riwayat, Inbox)
- `guru` diarahkan ke `/dashboard` dan otomatis dibawa ke Panel Admin (`/admin`)

### Data yang dibutuhkan saat register

- `name`
- `email`
- `identifier` (contoh: NIS atau ID unik)
- `password`
- `role`

## 4. Fitur Utama

### Siswa (user)

- Login dan register akun
- Mengirim curhat
- Pilihan tujuan curhat: **guru** atau **siswa lain** (penerima dipilih dari daftar user terdaftar)
- Pilihan kategori: pribadi, sosial, belajar, karier
- Riwayat curhat per siswa (dengan nama penerima yang dipilih)
- **Inbox**: menerima curhat yang ditujukan ke dirinya dari siswa lain, serta membalas curhat yang masuk

### Guru (admin) — Panel Admin dengan sidebar

- **Data Curhat** (`/admin`): melihat seluruh curhat di semua siswa. Kolom yang ditampilkan: **pengirim, penerima, kategori, status, waktu**. Isi pesan **tidak** ditampilkan demi kerahasiaan.
- **Detail Curhat** (`/admin/curhat/{id}`): hanya menampilkan pengirim, penerima, kategori, status, dan waktu. **Isi pesan dan balasan tidak ditampilkan.**
- **Data Siswa** (`/admin/siswa`): daftar seluruh siswa terdaftar (nama, identitas, email).
- Filter curhat berdasarkan kategori dan status.

Status curhat: `terkirim`, `dilihat`, `dibalas`, `ditangani`.

## 5. Persyaratan Sistem

Sebelum install, pastikan perangkat sudah memiliki:

- PHP 8.2+
- Composer
- Node.js 18+
- MySQL 8.0 (direkomendasikan) atau 5.7+
- Git
- Browser modern

## 6. Cara Installasi Lokal

### 6.1 Clone project

```bash
git clone <url-repository>
cd sahabat-aman
```

### 6.2 Install dependency PHP

```bash
composer install
```

### 6.3 Install dependency frontend

```bash
npm install
```

### 6.4 Siapkan file environment

Salin file `.env.example` menjadi `.env`:

```bash
copy .env.example .env
```

Jika Linux/macOS:

```bash
cp .env.example .env
```

### 6.5 Konfigurasi database MySQL

Edit file `.env` dan atur konfigurasi database seperti berikut:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_sahabat_aman
DB_USERNAME=root
DB_PASSWORD=
```

Pastikan database sudah dibuat di MySQL:

```sql
CREATE DATABASE db_sahabat_aman;
```

### 6.6 Generate key aplikasi

```bash
php artisan key:generate
```

### 6.7 Jalankan migrasi database

```bash
php artisan migrate
```

Jika ingin langsung membuat struktur tabel dan data awal, bisa jalankan:

```bash
php artisan migrate --seed
```

Seeder (`DatabaseSeeder`) membuat:

- `Udin` (siswa) — `udin@gmail.com` / `password`
- `Adit` (siswa) — `adit@gmail.com` / `password`
- `Guru Demo` (guru) — `guru@sahabataman.test` / `password`

## 7. Menjalankan Aplikasi

### Mode development

Jalankan server Laravel:

```bash
php artisan serve
```

Lalu jalankan Vite untuk frontend:

```bash
npm run dev
```

Akses aplikasi di:

```text
http://localhost:8000
```

### Mode production

```bash
npm run build
php artisan optimize
php artisan serve
```

## 8. Cara Pakai Aplikasi

### Register akun

1. Buka halaman `/register`
2. Isi data sesuai form
3. Pilih role: `siswa` atau `guru`
4. Klik daftar

### Login

1. Buka halaman `/login`
2. Masukkan email dan password
3. Sistem akan redirect sesuai role

### Untuk siswa

- Klik menu `Curhat`
- Pilih tujuan curhat (guru atau siswa lain), lalu pilih penerima dari daftar
- Pilih kategori masalah
- Tulis pesan
- Kirim curhat
- Cek status di menu `Riwayat`
- Cek curhat yang masuk ke dirinya di menu `Inbox` dan balas bila perlu

### Untuk guru

- Login dengan akun role guru
- Akan diarahkan ke Panel Admin (sidebar)
- Menu **Data Curhat**: lihat seluruh curhat (pengirim, penerima, kategori, status, waktu) tanpa isi pesan, dan filter berdasarkan kategori/status
- Menu **Data Siswa**: lihat daftar seluruh siswa

## 9. Struktur Project

```text
sahabat-aman/
├── app/
│   ├── Http/Controllers/
│   │   ├── AdminController.php
│   │   ├── AuthController.php
│   │   └── CurhatController.php
│   ├── Http/Middleware/EnsureUserIsGuru.php
│   └── Models/
│       ├── Balasan.php
│       ├── Curhat.php
│       └── User.php
├── bootstrap/
├── config/
├── database/
│   ├── migrations/
│   └── seeders/DatabaseSeeder.php
├── public/
├── resources/views/
│   ├── layouts/
│   │   ├── admin.blade.php
│   │   └── app.blade.php
│   ├── admin/
│   ├── curhat/
│   ├── dashboard/
│   └── riwayat/
├── routes/web.php
├── storage/
├── tests/
├── .env.example
├── composer.json
├── package.json
├── phpunit.xml
├── vite.config.js
├── artisan
└── README.md
```

## 10. Troubleshooting

### Error database connection

Pastikan:

- MySQL aktif
- DB sudah dibuat
- `.env` sudah benar
- user MySQL punya akses ke database tersebut

### Error `Class "Illuminate\..." not found`

Jalankan:

```bash
composer dump-autoload
```

### Error saat menjalankan Vite

```bash
npm install
npm run dev
```

### Error key application

```bash
php artisan key:generate
```

## 11. Catatan Penting

- Aplikasi ini default menggunakan MySQL dan port `3306`.
- Versi Laravel yang digunakan adalah `12.x`.
- Login menggunakan email + password bawaan Laravel Auth.
- Role dibuat saat registrasi, dan akun demo disiapkan melalui seeder.
- Guru bersifat **admin murni**: isi pesan curhat **tidak** ditampilkan di Panel Admin demi kerahasiaan.
- Penerima curhat dipilih berdasarkan user terdaftar (`penerima_id`), bukan nama bebas.
- Jika ingin deploy ke hosting, pastikan konfigurasi `.env` disesuaikan dengan database hosting.

## 12. Lisensi

Project ini menggunakan lisensi default Laravel/MIT sesuai pengaturan awal proyek.

## 13. Sumber Referensi

- Laravel Framework
- Laravel Authentication basics
- Vite + Tailwind CSS

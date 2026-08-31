# Sahabat Aman

Sahabat Aman adalah aplikasi web berbasis Laravel untuk sistem curhat digital yang memungkinkan siswa mengirimkan keluhan atau cerita kepada teman sebaya atau konselor/BK dengan pendekatan aman, nyaman, dan anonim jika diinginkan.

## 1. Ringkasan Proyek

- Nama aplikasi: Sahabat Aman
- Platform: Web App
- Stack utama: Laravel + MySQL + Vite
- Bahasa: PHP, Blade, JavaScript
- Target pengguna: Siswa, Teman Sebaya, Konselor/BK

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

Untuk testing, tester dalam menggunakan data dummy untuk melakukan input maupun output data menggunakan data berikut:
- `Siswa:`
email: siswa@sahabataman.test
password: password

- `Teman Sebaya:`
email: teman@sahabataman.test
password: password

- `Konselor/Guru BK;`
email: konselor@sahabataman.test
password: password

### Role yang tersedia

Saat register, user memilih role:

- `siswa`
- `teman`
- `konselor`

Setelah login:

- `siswa` diarahkan ke halaman `/curhat`
- `teman` dan `konselor` diarahkan ke `/dashboard`

### Data yang dibutuhkan saat register

- `name`
- `email`
- `identifier` (contoh: NIS atau ID unik)
- `password`
- `role`

## 4. Fitur Utama

- Login dan register akun
- Siswa mengirim curhat
- Pilihan tujuan curhat: teman atau konselor
- Pilihan kategori: pribadi, sosial, belajar, karier
- Opsi anonim
- Riwayat curhat per siswa
- Dashboard untuk teman/konselor
- Balasan curhat dari penerima
- Status curhat: terkirim, dilihat, dibalas, ditangani

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
DB_DATABASE=sahabat_aman
DB_USERNAME=root
DB_PASSWORD=
```

Pastikan database sudah dibuat di MySQL:

```sql
CREATE DATABASE sahabat_aman;
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
3. Pilih role: `siswa`, `teman`, atau `konselor`
4. Klik daftar

### Login

1. Buka halaman `/login`
2. Masukkan email dan password
3. Sistem akan redirect sesuai role

### Untuk siswa

- Klik menu `Curhat`
- Pilih tujuan curhat
- Pilih kategori masalah
- Tulis pesan
- Aktifkan/ nonaktifkan mode anonim
- Kirim curhat
- Cek status di menu `Riwayat`

### Untuk teman/konselor

- Login dengan akun role yang sesuai
- Akses halaman `/dashboard`
- Lihat daftar curhat yang relevan
- Buka detail curhat
- Beri balasan
- Khusus konselor, bisa menandai curhat sebagai ditangani

## 9. Struktur Project

```text
sahabat-aman/
├── app/
├── bootstrap/
├── config/
├── database/
├── public/
├── resources/
├── routes/
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

### Error `Class "Illuminate\\..." not found`

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
- Role dibuat saat registrasi, bukan dari seed otomatis.
- Jika ingin deploy ke hosting, pastikan konfigurasi `.env` disesuaikan dengan database hosting.

## 12. Lisensi

Project ini menggunakan lisensi default Laravel/MIT sesuai pengaturan awal proyek.

## 13. Sumber Referensi

- Laravel Framework
- Laravel Jetstream/Authentication basics
- Vite + Tailwind CSS

Jika Anda mau, saya juga bisa bantu membuat versi README yang lebih formal untuk portofolio atau versi yang lebih ringkas untuk GitHub landing page.

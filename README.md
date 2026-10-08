# Sahabat Aman

Aplikasi web layanan bimbingan & konseling sekolah (curhat digital) yang aman dan bebas stigma. Siswa dapat mengirim cerita kepada **guru**, **konselor sebaya**, atau **siswa lain**, sementara guru berfungsi sebagai admin yang mengelola data siswa dan data curhat.

---

## 1. Teknologi yang Digunakan

| Lapisan | Teknologi |
| --- | --- |
| Backend | PHP 8.2, Laravel 12 |
| Database | MySQL 8.0 |
| Autentikasi | Laravel Auth native (session, driver `database`) |
| View | Blade Template |
| Frontend | Vite 7, Tailwind CSS 4, JavaScript |
| Library Pendukung | Yajra DataTables (server-side), SweetAlert2, DataTables.net |
| Testing | PHPUnit 11 |
| Deployment | Railway |
| Server Web | Apache / Nginx / `php artisan serve` |

### Konfigurasi bawaan project

- `DB_CONNECTION` = `mysql`, port `3306`
- `SESSION_DRIVER` = `database`
- `CACHE_STORE` = `database`
- `QUEUE_CONNECTION` = `database`
- Rate limit: login 100x/jam per email+IP, register 3x/menit per IP
- Auto logout setelah idle

### Fitur utama

- **Siswa** — register/login, kirim curhat (pilih penerima & kategori), riwayat curhat, inbox & balas pesan.
- **Guru** — Panel Admin dengan DataTables: monitoring seluruh curhat (pengirim, penerima, kategori, status, waktu) **tanpa isi pesan**, plus filter kategori & status.
- **Konselor Sebaya** — menerima dan membalas curhat yang ditujukan kepadanya.

Status curhat: `terkirim` → `dilihat` → `dibalas` → `ditangani`.

---

## 2. Cara Instalasi

### Persyaratan

- PHP 8.2+
- Composer 2
- Node.js 18+
- MySQL 8.0
- Git

### 2.1 Clone repository

```bash
git clone <url-repository>
cd Sahabat-Aman
```

### 2.2 Install dependency

```bash
composer install
npm install
```

### 2.3 Siapkan file environment

```bash
# Windows
copy .env.example .env

# Linux / macOS
cp .env.example .env
```

### 2.4 Konfigurasi database

Buat database di MySQL:

```sql
CREATE DATABASE db_sahabat_aman;
```

Lalu atur `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_sahabat_aman
DB_USERNAME=root
DB_PASSWORD=
```

Generate key aplikasi:

```bash
php artisan key:generate
```

Jalankan migrasi + seeder:

```bash
php artisan migrate --seed
```

Seeder membuat akun demo:

| Nama | Email | Password | Role |
| --- | --- | --- | --- |
| Udin | `udin@gmail.com` | `password` | siswa |
| Adit | `adit@gmail.com` | `password` | siswa |
| Annisa Uswatun Hasanah, S.Pd | `annisa@gmail.com` | `password` | guru |
| Konselor Sebaya A | `konselorA@gmail.com` (contoh: `konselorA@gmail.com`) | `password` | konselor sebaya |
| Konselor Sebaya B | `konselorB@gmail.com` (contoh: `konselorB@gmail.com`) | `password` | konselor sebaya |
| Admin | `admin@gmail.com` | `password` | admin |

### 2.5 Kalau sudah pernah instalasi

Ambil perubahan terbaru dari remote:

```bash
git pull
```

Reset database beserta data demo:

```bash
php artisan migrate:fresh --seed
```

---

## 3. Pembuatan Branch & Konvensi Commit

### Alur kerja

```bash
# Ambil perubahan terbaru
git pull

# Buat branch baru dari branch terbaru
git checkout -b feat/nama-fitur

# Kerjakan perubahan, lalu commit
git add .
git commit -m "feat: tambah login multi-role"

# Push branch
git push -u origin feat/nama-fitur
```

Setelah branch selesai dan dicek, buat **Pull Request** ke branch utama untuk di-review.

Format nama branch: `<tipe>/<deskripsi-kecil>` dengan huruf kecil dan pemisah `-`, contoh `feat/login-multi-role`, `fix/hapus-tidak-sejajar`, `chore/update-composer`.

### Tipe commit

| Tipe | Dipakai untuk | Contoh |
| --- | --- | --- |
| `feat` | Nambah fitur baru | `feat: tambah login multi-role` |
| `fix` | Perbaikan bug | `fix: tombol hapus tidak sejajar dengan teks` |
| `refactor` | Rapihin/ubah struktur kode tanpa ubah fungsi | `refactor: pisah controller per role` |
| `style` | Perubahan tampilan/format kode (bukan logic) | `style: rapihin indentasi blade` |
| `docs` | Perubahan dokumentasi | `docs: update cara deploy ke VPS` |
| `chore` | Kerjaan beres-beres, config, dependency | `chore: update composer.json` |
| `test` | Nambah/ubah testing | `test: tambah test untuk curhat controller` |
| `perf` | Optimasi performa | `perf: tambah index tabel curhats` |
| `build` | Perubahan build tools/CI | `build: setup github actions` |
| `revert` | Membatalkan commit sebelumnya | `revert: revert fitur kotak masuk teman` |

Format commit message: `<tipe>: <deskripsi singkat>` (huruf kecil, tanpa titik di akhir).

---

## 4. Cara Menjalankan Program

### Mode development

Jalankan Laravel, queue, log, dan Vite sekaligus:

```bash
composer run dev
```

Jika ingin manual, jalankan terpisah di terminal berbeda:

```bash
php artisan serve      # server Laravel
npm run dev            # Vite (hot reload)
```

Akses di:

```text
http://127.0.0.1:8000/
```

### Mode production

```bash
npm run build
php artisan optimize
php artisan serve
```

### Menjalankan test

```bash
composer test
```

### Troubleshooting

- **Error koneksi database** — pastikan MySQL aktif, database sudah dibuat, dan kredensial `.env` benar.
- **`Class "Illuminate\..." not found`** — jalankan `composer dump-autoload`.
- **CSS/JS tidak termuat** — jalankan `npm install` lalu `npm run dev` (dev) atau `npm run build` (production).
- **Error application key** — jalankan `php artisan key:generate`.

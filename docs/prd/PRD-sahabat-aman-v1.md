# PRD — Sahabat Aman
**Product Requirements Document untuk pengembangan web app**

Versi: 1.0
Tipe produk: Web app full-stack
Status: Ready for development

---

## 1. Ringkasan Produk

**Nama Produk:** Sahabat Aman

**Deskripsi:** Platform web yang memungkinkan siswa menyampaikan cerita, keluhan, atau permasalahan yang sedang dihadapi kepada teman sebaya (peer counselor) atau Guru BK/Konselor, dengan cara yang mudah, nyaman, dan (jika diinginkan) anonim.

**Masalah yang dipecahkan:** Siswa sering enggan menyampaikan masalah pribadi secara langsung/tatap muka karena canggung, takut dihakimi, atau tidak nyaman. Sahabat Aman memberi jalur komunikasi yang lebih santai dan aman secara psikologis.

**Sasaran Pengguna (User Roles):**
| Role | Deskripsi |
|---|---|
| Siswa | Pengguna utama, mengirim curhat dan memantau statusnya |
| Teman Sebaya / Peer Counselor | Menerima & membalas curhat kategori ringan-sedang |
| Guru BK / Konselor | Menerima & menangani curhat yang butuh penanganan resmi |

---

## 2. Tujuan & Success Metrics

**Tujuan produk:**
- Memberi siswa saluran curhat yang rendah hambatan (low-friction)
- Memberi transparansi status penanganan ke siswa
- Memberi tools bagi BK/peer counselor untuk mengelola & merespons curhat masuk

**Metrik keberhasilan (untuk versi lanjutan/produksi):**
- Jumlah curhat terkirim per minggu
- Rata-rata waktu respons (dari Terkirim → Dilihat, dan → Dibalas)
- Persentase curhat yang mencapai status "Ditangani"
- Retensi penggunaan menu Riwayat (siswa balik cek status)

---

## 3. Lingkup (Scope)

### 3.1 Platform
Web app (bukan native app), dengan tampilan responsif yang bisa diakses baik dari **mobile** maupun **desktop/web**. Prototype single-file HTML/CSS/JS (`sahabat-aman-demo.html`) sudah mencakup UI/UX untuk role **Siswa** dengan pendekatan mobile-first, dan menjadi **starting point/referensi visual & alur** untuk pengembangan selanjutnya — namun layout perlu disesuaikan agar tetap nyaman digunakan di layar desktop (misal untuk dashboard Konselor/BK).

### 3.2 Yang perlu dibangun (scope PRD ini)
- [ ] Migrasi dari single-file prototype ke struktur project yang scalable
- [ ] Backend/data layer nyata (menggantikan localStorage)
- [ ] Autentikasi sederhana (minimal role: Siswa vs Petugas/BK)
- [ ] Dashboard untuk role Teman Sebaya & Konselor/BK (belum ada di prototype)
- [ ] Sistem notifikasi status (minimal: perubahan status tersimpan & terlihat real-time/on-refresh)
- [ ] Validasi & keamanan dasar (rate limiting kirim pesan, sanitasi input)

### 3.3 Out of scope (tidak dikerjakan dulu)
- Native mobile app
- Video/voice call antara siswa-konselor
- Integrasi dengan sistem sekolah lain (misal Dapodik)
- Multi-sekolah/multi-tenant (asumsi: 1 instansi/sekolah per deployment)

---

## 4. User Flows

### 4.1 Flow: Siswa Mengirim Curhat
```
Beranda
  → Klik menu "Curhat"
  → Pilih tujuan: [Teman] atau [Konselor/BK]
  → Pilih kategori: [Pribadi] [Sosial] [Belajar] [Karier]
  → Halaman "Kirim Pesan"
      - Tulis isi curhat (textarea, required)
      - Toggle "Kirim sebagai anonim" (default: ON)
      - Klik "Kirim"
  → Sistem menyimpan curhat baru dengan status default: Terkirim
  → Tampil halaman sukses ("Curhatmu terkirim")
  → Siswa bisa lanjut ke "Riwayat" atau kembali ke Beranda
```

### 4.2 Flow: Siswa Melihat Riwayat
```
Beranda → Menu "Riwayat"
  → List semua curhat milik siswa (terbaru di atas)
  → Setiap item menampilkan: kategori, tujuan, status, cuplikan pesan, waktu
  → Klik salah satu → Detail:
      - Info lengkap (kategori, tujuan, waktu, anonim/tidak)
      - Timeline status (Terkirim → Dilihat → Dibalas → Ditangani)
      - Thread percakapan (pesan asli + semua balasan, berurutan)
```

### 4.3 Flow: Teman/Konselor Menangani Curhat (BELUM ADA — perlu dibangun)
```
Login sebagai Teman Sebaya / Konselor
  → Dashboard: daftar curhat masuk (filter: kategori, status, ditujukan ke saya)
  → Klik curhat → status otomatis berubah ke "Dilihat"
  → Baca isi curhat (identitas siswa disamarkan jika anonim)
  → Tulis balasan → status berubah ke "Dibalas"
  → (Khusus Konselor) Tandai "Ditangani" setelah proses selesai
```

---

## 5. Functional Requirements

### FR-1: Modul Curhat (Siswa)
- FR-1.1: Sistem harus menyediakan pilihan tujuan curhat: **Teman** atau **Konselor/BK**
- FR-1.2: Sistem harus menyediakan 4 kategori masalah: **Pribadi, Sosial, Belajar, Karier**
- FR-1.3: Sistem harus menyediakan form input pesan bebas (teks, tanpa batas karakter maksimum ketat, tapi disarankan limit soft ~2000 karakter)
- FR-1.4: Sistem harus menyediakan toggle **anonim** (default ON). Jika anonim, identitas siswa tidak ditampilkan ke penerima
- FR-1.5: Setelah kirim, sistem harus membuat record curhat baru dengan status awal `terkirim`
- FR-1.6: Sistem harus menampilkan konfirmasi visual setelah pesan berhasil dikirim

### FR-2: Modul Riwayat (Siswa)
- FR-2.1: Sistem harus menampilkan seluruh curhat milik siswa yang sedang login, diurutkan dari terbaru
- FR-2.2: Setiap entri riwayat menampilkan: kategori, tujuan (teman/konselor), status saat ini, cuplikan pesan, waktu kirim
- FR-2.3: Sistem harus menyediakan halaman detail per curhat yang menampilkan:
  - Timeline/progress status (4 tahap)
  - Isi pesan asli
  - Seluruh balasan (jika ada), dengan label pengirim
- FR-2.4: Jika belum ada curhat, tampilkan empty state yang ramah (bukan halaman kosong/error)

### FR-3: Status Curhat (Sistem)
- FR-3.1: Status harus mengikuti 4 tahap berurutan (tidak bisa mundur):
  1. 🕐 **Terkirim** — pesan berhasil dikirim
  2. 👀 **Dilihat** — dibuka oleh penerima (teman/konselor)
  3. 💬 **Dibalas** — penerima sudah membalas minimal 1 kali
  4. ✅ **Ditangani** — kasus ditandai selesai/tertangani
- FR-3.2: Status `Dilihat` otomatis ter-set saat penerima pertama kali membuka detail curhat tersebut
- FR-3.3: Status `Dibalas` otomatis ter-set saat penerima mengirim balasan pertama
- FR-3.4: Status `Ditangani` hanya bisa diset manual oleh Konselor/BK (bukan otomatis, bukan oleh peer counselor)

### FR-4: Modul Dashboard Penerima (Teman Sebaya & Konselor/BK) — BARU
- FR-4.1: Sistem harus punya login/akses terpisah untuk role Teman Sebaya dan Konselor/BK
- FR-4.2: Dashboard menampilkan daftar curhat masuk yang relevan dengan role tersebut (curhat "ke Teman" hanya muncul di dashboard peer counselor, dst)
- FR-4.3: Dashboard harus bisa difilter berdasarkan kategori dan status
- FR-4.4: Penerima bisa membuka detail curhat dan mengirim balasan
- FR-4.5: Jika curhat anonim, dashboard tidak menampilkan nama/identitas siswa pengirim
- FR-4.6: Khusus role Konselor/BK: tersedia tombol untuk mengubah status jadi "Ditangani"

### FR-5: Autentikasi & Otorisasi
- FR-5.1: Siswa harus login (minimal dengan identitas unik, misal NIS/email sekolah) untuk mengakses Riwayat pribadinya
- FR-5.2: Curhat anonim tetap tersimpan terhubung ke akun siswa di database (untuk keperluan Riwayat pribadi), tapi identitas disembunyikan dari tampilan penerima
- FR-5.3: Role Teman Sebaya dan Konselor/BK punya akses login terpisah dari siswa
- FR-5.4: Siswa tidak boleh bisa mengakses curhat milik siswa lain

### FR-6: Notifikasi (minimal viable)
- FR-6.1: Siswa mendapat indikator visual (misal badge/dot) di menu Riwayat jika ada curhat yang statusnya berubah sejak terakhir dibuka
- FR-6.2 (opsional, fase 2): Notifikasi via email/browser push saat status berubah

---

## 6. Data Model (Referensi untuk Development)

```
User
- id
- nama (nullable jika sensitif)
- role: 'siswa' | 'teman' | 'konselor'
- identifier (NIS/email)

Curhat
- id
- siswa_id (FK ke User, selalu terisi meski anonim)
- tujuan: 'teman' | 'konselor'
- kategori: 'pribadi' | 'sosial' | 'belajar' | 'karier'
- pesan (text)
- anonim (boolean)
- status: 'terkirim' | 'dilihat' | 'dibalas' | 'ditangani'
- created_at
- updated_at
- dilihat_at (nullable)
- ditangani_by (FK ke User, nullable — hanya konselor)
- ditangani_at (nullable)

Balasan
- id
- curhat_id (FK ke Curhat)
- pengirim_id (FK ke User)
- pengirim_role: 'teman' | 'konselor'
- isi (text)
- created_at
```

---

## 7. Referensi Desain & UX (dari Prototype)

Prototype visual sudah dibuat (`sahabat-aman-demo.html`) dan **harus dijadikan acuan** untuk styling & interaksi pada implementasi selanjutnya. Poin penting yang harus dipertahankan:

- **Nuansa visual:** hangat, tenang, non-institusional — palet sage green (#7C9885), cream (#F6F3EC), aksen clay (#E8A87C) dan sky blue (#8FB3C7). Hindari kesan formulir birokratis/kaku.
- **Tipografi:** kombinasi serif display (Fraunces) untuk judul + sans-serif (Inter) untuk body, memberi kesan hangat tapi tetap modern.
- **Pola interaksi:** flow bertahap (step-by-step) untuk Curhat (pilih tujuan → kategori → tulis pesan), bukan satu form panjang sekaligus — mengurangi rasa terbebani.
- **Status pakai warna+icon berbeda**, bukan cuma teks, agar mudah dipindai (Terkirim: ungu, Dilihat: biru, Dibalas: oranye, Ditangani: hijau).
- **Komponen kunci yang sudah ada di prototype dan bisa dipakai ulang:**
  - Card pilihan (option-card) untuk tujuan & kategori
  - Toggle anonim (switch component)
  - Status badge & status timeline (track-step)
  - Chat bubble untuk thread percakapan
  - Bottom navigation (mobile-first)
- Layout mobile-first (max-width ~430px), karena target pengguna (siswa) mayoritas akses dari HP.

---

## 8. Tech Stack

- **Frontend:** HTML/CSS/JS (bisa dikembangkan dengan framework ringan seperti Alpine.js/Vue jika dibutuhkan), responsif untuk mobile & desktop — melanjutkan pendekatan visual dari prototype
- **Backend:** PHP (native atau framework seperti Laravel/CodeIgniter, menyesuaikan kemampuan tim)
- **Database:** MySQL
- **Hosting:** Rumahweb (shared hosting, sudah termasuk dukungan MySQL & PHP)
- **Auth:** Session-based login sederhana (PHP session), dengan role: siswa, teman, konselor

---



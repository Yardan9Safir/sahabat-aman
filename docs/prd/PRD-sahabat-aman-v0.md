# PRD — Sahabat Aman
**Product Requirements Document untuk pengembangan web app**

Versi: 1.0
Tipe produk: Full Stack
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

### 3.1 Sudah ada (prototype existing)
Prototype single-file HTML/CSS/JS (`sahabat-aman-demo.html`) sudah mencakup UI/UX untuk role **Siswa** saja, dengan data dummy tersimpan di `localStorage`. Ini adalah **starting point/referensi visual & alur** untuk pengembangan selanjutnya.

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

## 8. Rekomendasi Tech Stack

Karena target user adalah client/sekolah dengan budget terbatas (lihat konteks freelance), berikut 2 opsi:

**Opsi A — Tetap Static-First (budget minim, tanpa backend berbayar)**
- Frontend: HTML/CSS/JS vanilla atau lightweight framework (Alpine.js/Vue) — melanjutkan pendekatan prototype
- Data: Firebase Firestore (free tier) atau Supabase (free tier) untuk auth + database realtime
- Hosting: Netlify/Vercel/GitHub Pages (gratis)
- Cocok untuk: 1 sekolah, jumlah pengguna kecil-menengah

**Opsi B — Full Stack (jika butuh lebih robust/scalable)**
- Frontend: React/Vue + Tailwind CSS
- Backend: Node.js (Express) atau Laravel
- Database: PostgreSQL/MySQL
- Auth: JWT sederhana atau session-based
- Hosting: Railway/Render (backend) + Vercel/Netlify (frontend)

> Rekomendasi: mulai dari **Opsi A** dengan Supabase, karena sudah menyediakan auth + realtime database + row-level security tanpa perlu bangun backend sendiri — cocok untuk timeline & budget proyek kuliah/freelance kecil.

---

## 9. Non-Functional Requirements

- **Privasi & Keamanan:** Data curhat bersifat sensitif. Wajib ada kontrol akses (row-level security) agar siswa lain / pihak tidak berwenang tidak bisa mengakses curhat orang lain.
- **Performa:** Halaman utama harus load < 2 detik di koneksi mobile standar.
- **Aksesibilitas:** Kontras warna cukup, ukuran tap target minimal 44px (sudah diterapkan di prototype), dukung keyboard navigation dasar.
- **Responsif:** Wajib mobile-first, tapi tetap bisa diakses dari desktop (misal oleh Konselor/BK yang mengelola dari laptop).
- **Skalabilitas data:** Struktur data harus siap untuk ratusan-ribuan curhat tanpa perlu redesign skema.

---

## 10. Milestone / Fase Pengembangan (Saran)

| Fase | Deliverable |
|---|---|
| Fase 1 | Migrasi prototype ke project terstruktur + integrasi Supabase (auth + database) untuk role Siswa |
| Fase 2 | Bangun dashboard untuk role Teman Sebaya & Konselor/BK |
| Fase 3 | Sistem status otomatis (dilihat/dibalas) + notifikasi dasar |
| Fase 4 | Testing keamanan (row-level security), polish UI, deploy |

---

## 11. Catatan untuk AI Coding Assistant (VSCode)

Saat menggunakan PRD ini sebagai prompt di VSCode (misal dengan Claude Code/Copilot):
- Mulai dari **Fase 1** dulu, jangan minta generate semua fase sekaligus
- Lampirkan/rujuk file `sahabat-aman-demo.html` sebagai referensi desain saat meminta implementasi UI
- Tegaskan ke AI assistant: pertahankan alur step-by-step (bukan single long form) dan palet warna yang sudah didefinisikan di Section 7
- Untuk data sensitif (isi curhat), selalu minta AI menerapkan row-level security / access control di setiap query, jangan hanya mengandalkan filter di frontend

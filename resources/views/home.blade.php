@extends('layouts.app')

@section('title', 'Sahabat Aman - BK')

@section('content')
{{-- TOP EMERGENCY BANNER --}}
<div class="bg-surface-container-low text-on-surface-variant border-b border-surface-container px-4 py-2 text-center flex items-center justify-center gap-2 rounded-2xl">
    <span class="w-2 h-2 rounded-full bg-error animate-ping"></span>
    <span class="text-label-sm font-medium">Butuh bantuan segera saat krisis? Hotline Psikologi Sekolah siaga 24/7 di 0800-1 atau datangi Ruang BK Gedung A.</span>
    <a class="text-label-sm font-semibold text-primary underline ml-1 hover:text-primary-container" href="#krisis">Bantuan Cepat</a>
</div>

{{-- HERO SECTION --}}
<section class="relative pt-12 pb-20 md:pt-16 md:pb-28 overflow-hidden">
    <div class="absolute -top-24 left-1/2 -translate-x-1/2 w-[700px] h-[350px] bg-primary-fixed-dim/20 blur-3xl rounded-full pointer-events-none -z-10"></div>
    <div class="max-w-3xl mx-auto text-center">
        {{-- Trust Badge --}}
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-surface-container-high border border-outline-variant/30 text-on-surface-variant mb-6">
            <span class="material-symbols-outlined text-primary text-base" style="font-variation-settings: 'FILL' 1;">lock</span>
            <span class="text-label-md">100% Bebas Stigma &bull; Etika Kerahasiaan Terjamin</span>
        </div>
        {{-- Hero Headline --}}
        <h1 class="text-headline-xl font-bold tracking-tight text-on-surface text-balance mb-6 leading-tight">
            Setiap Cerita Berhak Didengar di <span class="text-primary underline decoration-primary-fixed decoration-wavy underline-offset-8">Ruang yang Aman</span> & Terjaga.
        </h1>
        {{-- Hero Subheadline --}}
        <p class="text-body-lg text-on-surface-variant text-balance max-w-2xl mx-auto mb-10">
            Bimbingan konseling sekolah modern tanpa tatap sinis atau rasa takut. Bebas curhat santai ke Teman Sebaya terlatih, atau konsultasi mendalam bersama Guru BK berlisensi dalam ekosistem digital yang hangat dan terlindungi.
        </p>
        {{-- Dual Main CTAs --}}
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mb-16">
            @auth
            <a class="w-full sm:w-auto inline-flex items-center justify-center gap-2.5 px-8 py-4 rounded-2xl bg-primary hover:bg-primary-container text-on-primary font-title-md shadow-md hover:shadow-lg transition-all duration-200" href="{{ route('curhat.create') }}">
                <span class="material-symbols-outlined text-2xl">edit_note</span>
                <span>Mulai Curhat (Portal Siswa)</span>
            </a>
            @if(Auth::user()->role === 'guru')
            <a class="w-full sm:w-auto inline-flex items-center justify-center gap-2.5 px-7 py-4 rounded-2xl bg-surface-container-lowest border border-outline-variant hover:bg-surface-container text-primary font-title-md transition-all duration-200" href="{{ route('admin.index') }}">
                <span class="material-symbols-outlined text-xl text-secondary">health_and_safety</span>
                <span>Akses Portal Guru BK</span>
            </a>
            @endif
            @else
            <a class="w-full sm:w-auto inline-flex items-center justify-center gap-2.5 px-8 py-4 rounded-2xl bg-primary hover:bg-primary-container text-on-primary font-title-md shadow-md hover:shadow-lg transition-all duration-200" href="{{ route('curhat.create') }}">
                <span class="material-symbols-outlined text-2xl">edit_note</span>
                <span>Mulai Curhat (Portal Siswa)</span>
            </a>
            <a class="w-full sm:w-auto inline-flex items-center justify-center gap-2.5 px-7 py-4 rounded-2xl bg-surface-container-lowest border border-outline-variant hover:bg-surface-container text-primary font-title-md transition-all duration-200" href="{{ route('login') }}">
                <span class="material-symbols-outlined text-xl text-secondary">health_and_safety</span>
                <span>Akses Portal Guru BK</span>
            </a>
            @endauth
        </div>
        {{-- Trust Metric Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-left">
            <div class="bg-surface-container-lowest p-5 rounded-2xl border border-outline-variant/30 custom-pill-shadow flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-surface-container flex items-center justify-center text-primary shrink-0">
                    <span class="material-symbols-outlined text-2xl">verified_user</span>
                </div>
                <div>
                    <p class="text-headline-sm font-bold text-on-surface">100% Privat</p>
                    <p class="text-body-sm text-on-surface-variant">Sesuai Kode Etik ABKIN</p>
                </div>
            </div>
            <div class="bg-surface-container-lowest p-5 rounded-2xl border border-outline-variant/30 custom-pill-shadow flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-surface-container flex items-center justify-center text-primary shrink-0">
                    <span class="material-symbols-outlined text-2xl">favorite</span>
                </div>
                <div>
                    <p class="text-headline-sm font-bold text-on-surface">480+ Siswa</p>
                    <p class="text-body-sm text-on-surface-variant">Merasa Lebih Lega & Terdampingi</p>
                </div>
            </div>
            <div class="bg-surface-container-lowest p-5 rounded-2xl border border-outline-variant/30 custom-pill-shadow flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-surface-container flex items-center justify-center text-primary shrink-0">
                    <span class="material-symbols-outlined text-2xl">groups</span>
                </div>
                <div>
                    <p class="text-headline-sm font-bold text-on-surface">8+ Konselor</p>
                    <p class="text-body-sm text-on-surface-variant">Guru BK & Peer Counselor Siaga</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- MOOD TRACKER --}}
<section class="max-w-3xl mx-auto mb-20">
    <div class="bg-surface-container-lowest p-6 rounded-2xl border border-outline-variant/40 custom-pill-shadow text-center">
        <span class="text-label-md text-on-surface-variant block mb-1">Cek Perasaan Hari Ini</span>
        <h3 class="text-title-md text-on-surface mb-4">Bagaimana suasana hatimu saat ini?</h3>
        <div class="flex flex-wrap items-center justify-center gap-2.5" id="mood-bar">
            <button class="mood-pill px-4 py-2 rounded-xl border border-outline-variant/50 hover:bg-surface-container-low text-label-md text-on-surface-variant transition-all hover:scale-105 active:scale-95 flex items-center gap-1.5" data-mood="cemas">
                <span>&#x1F327;&#xFE0F;</span> <span>Cemas & Takut</span>
            </button>
            <button class="mood-pill px-4 py-2 rounded-xl border border-outline-variant/50 hover:bg-surface-container-low text-label-md text-on-surface-variant transition-all hover:scale-105 active:scale-95 flex items-center gap-1.5" data-mood="lelah">
                <span>&#x1F50B;</span> <span>Lelah Berat</span>
            </button>
            <button class="mood-pill px-4 py-2 rounded-xl border border-outline-variant/50 hover:bg-surface-container-low text-label-md text-on-surface-variant transition-all hover:scale-105 active:scale-95 flex items-center gap-1.5" data-mood="netral">
                <span>&#x1F343;</span> <span>Netral / Biasa</span>
            </button>
            <button class="mood-pill px-4 py-2 rounded-xl bg-primary-fixed border border-primary text-primary text-label-md transition-all flex items-center gap-1.5" data-mood="lega">
                <span>&#x2600;&#xFE0F;</span> <span>Mulai Lega</span>
            </button>
            <button class="mood-pill px-4 py-2 rounded-xl border border-outline-variant/50 hover:bg-surface-container-low text-label-md text-on-surface-variant transition-all hover:scale-105 active:scale-95 flex items-center gap-1.5" data-mood="bersyukur">
                <span>&#x1F331;</span> <span>Bersyukur</span>
            </button>
        </div>
        <p class="text-body-sm text-outline mt-3">Tidak ada emosi yang salah. Apapun rasanya, Sahabat Aman hadir untuk mendengarkan tanpa menghakimi.</p>
    </div>
</section>

{{-- SECTION 2: DUA JALUR BIMBINGAN --}}
<section class="py-16 bg-surface-container-low border-y border-surface-container" id="layanan">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center max-w-2xl mx-auto mb-14">
            <span class="text-label-md text-primary bg-surface-container px-3 py-1 rounded-full uppercase tracking-wider">Desain Ekosistem Terpadu</span>
            <h2 class="text-headline-lg font-bold text-on-surface mt-3">Dua Jalur Bimbingan yang Disesuaikan</h2>
            <p class="text-body-md text-on-surface-variant mt-2">
                Pemisahan arsitektur privasi yang ketat memastikan siswa memiliki kendali penuh atas siapa yang membaca ceritanya, sementara Guru BK memiliki dasbor penanganan terstandar.
            </p>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-stretch">
            {{-- JALUR SISWA --}}
            <div class="bg-surface-container-lowest p-8 rounded-2xl border border-outline-variant/40 custom-pill-shadow flex flex-col justify-between hover:border-primary-fixed-dim transition-all">
                <div>
                    <div class="flex items-center justify-between mb-6">
                        <span class="px-3.5 py-1.5 rounded-full bg-primary-fixed text-primary text-label-md flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-sm">sentiment_satisfied</span>
                            Jalur Ruang Siswa
                        </span>
                        <span class="text-label-sm text-outline">Bebas Stigma &bull; 24/7 Daring</span>
                    </div>
                    <h3 class="text-headline-md text-on-surface mb-3">Pilih Nyamanmu: Guru BK atau Teman Sebaya</h3>
                    <p class="text-body-md text-on-surface-variant mb-6 leading-relaxed">
                        Kamu memegang kendali sepenuhnya. Pilih berdiskusi dengan sesama teman sebaya yang terlatih, atau bimbingan resmi dengan Guru BK jika membutuhkan solusi terstruktur.
                    </p>
                    <div class="space-y-4 mb-8">
                        <div class="p-4 rounded-xl bg-surface-container-low border border-surface-container flex gap-3.5">
                            <div class="w-10 h-10 rounded-lg bg-surface-container-lowest flex items-center justify-center text-primary shrink-0">
                                <span class="material-symbols-outlined">groups</span>
                            </div>
                            <div>
                                <h4 class="text-title-md text-on-surface">Curhat Teman Sebaya (Peer Counselor)</h4>
                                <p class="text-body-sm text-on-surface-variant mt-0.5">
                                    Ruang ngobrol santai antar siswa. <strong class="text-primary font-medium">Dijamin privat tidak dapat dibaca oleh Guru BK</strong> kecuali terdapat indikasi darurat bahaya fisik.
                                </p>
                            </div>
                        </div>
                        <div class="p-4 rounded-xl bg-surface-container-low border border-surface-container flex gap-3.5">
                            <div class="w-10 h-10 rounded-lg bg-surface-container-lowest flex items-center justify-center text-secondary shrink-0">
                                <span class="material-symbols-outlined">school</span>
                            </div>
                            <div>
                                <h4 class="text-title-md text-on-surface">Konseling Resmi Guru BK</h4>
                                <p class="text-body-sm text-on-surface-variant mt-0.5">
                                    Untuk problematika akademik, penjurusan, krisis keluarga, atau kecemasan berat. Dicatat dan didampingi langsung oleh guru BK bersertifikat.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pt-4 border-t border-surface-container">
                    <a class="w-full inline-flex items-center justify-center gap-2 py-3.5 px-6 rounded-xl bg-primary text-on-primary text-label-lg hover:bg-primary-container transition-colors" href="{{ route('curhat.create') }}">
                        <span>Buka Ruang Curhat Siswa</span>
                        <span class="material-symbols-outlined text-sm">arrow_forward</span>
                    </a>
                </div>
            </div>

            {{-- JALUR GURU BK --}}
            <div class="bg-surface-container-lowest p-8 rounded-2xl border border-outline-variant/40 custom-pill-shadow flex flex-col justify-between hover:border-secondary-fixed-dim transition-all" id="jalur-guru">
                <div>
                    <div class="flex items-center justify-between mb-6">
                        <span class="px-3.5 py-1.5 rounded-full bg-secondary-container text-on-secondary-container text-label-md flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-sm">shield</span>
                            Jalur Profesional Guru BK
                        </span>
                        <span class="text-label-sm text-outline">Dasbor Terenkripsi & Terverifikasi</span>
                    </div>
                    <h3 class="text-headline-md text-on-surface mb-3">Dasbor Pemantauan & Pendampingan Terpusat</h3>
                    <p class="text-body-md text-on-surface-variant mb-6 leading-relaxed">
                        Kelola tiket konseling siswa masuk, jadwalkan temu konseling bilik tertutup, dan catat rekam perkembangan psikologis siswa secara etis, terstruktur, dan akuntabel.
                    </p>
                    <div class="space-y-4 mb-8">
                        <div class="p-4 rounded-xl bg-surface-container-low border border-surface-container flex gap-3.5">
                            <div class="w-10 h-10 rounded-lg bg-surface-container-lowest flex items-center justify-center text-secondary shrink-0">
                                <span class="material-symbols-outlined">inbox</span>
                            </div>
                            <div>
                                <h4 class="text-title-md text-on-surface">Manajemen Tiket Curhat Terklasifikasi</h4>
                                <p class="text-body-sm text-on-surface-variant mt-0.5">
                                    Triage otomatis: Pisahkan curhat tingkat ringan, sedang, dan indikasi krisis yang membutuhkan panggilan tatap muka segera.
                                </p>
                            </div>
                        </div>
                        <div class="p-4 rounded-xl bg-surface-container-low border border-surface-container flex gap-3.5">
                            <div class="w-10 h-10 rounded-lg bg-surface-container-lowest flex items-center justify-center text-secondary shrink-0">
                                <span class="material-symbols-outlined">calendar_month</span>
                            </div>
                            <div>
                                <h4 class="text-title-md text-on-surface">Sinkronisasi Jadwal Tatap Muka Ruang BK</h4>
                                <p class="text-body-sm text-on-surface-variant mt-0.5">
                                    Siswa dapat memesan jam konsultasi di jam istirahat atau sepulang sekolah tanpa perlu dipanggil di depan kelas oleh pengeras suara.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pt-4 border-t border-surface-container">
                    <a class="w-full inline-flex items-center justify-center gap-2 py-3.5 px-6 rounded-xl bg-surface-container border border-outline-variant hover:bg-surface-container-high text-on-surface text-label-lg transition-colors" href="{{ route('login') }}">
                        <span>Login ke Portal Konselor BK</span>
                        <span class="material-symbols-outlined text-sm">login</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- SECTION 3: FITUR UNGGULAN --}}
<section class="py-20" id="tentang">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center max-w-2xl mx-auto mb-16">
            <span class="text-label-md text-primary bg-primary-fixed/40 px-3 py-1 rounded-full uppercase tracking-wider">Komitmen Keamanan</span>
            <h2 class="text-headline-lg font-bold text-on-surface mt-3">Sanctuary Digital yang Benar-Benar Melindungi</h2>
            <p class="text-body-md text-on-surface-variant mt-2">
                Kami merancang platform ini dengan standar konseling humanistik dan perlindungan data siswa yang transparan.
            </p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- Anti-Stigma --}}
            <div class="md:col-span-2 bg-surface-container-lowest p-8 rounded-2xl border border-outline-variant/30 custom-pill-shadow flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-xl bg-primary-fixed flex items-center justify-center text-primary mb-6">
                        <span class="material-symbols-outlined text-2xl">sentiment_very_satisfied</span>
                    </div>
                    <span class="text-label-md text-primary font-bold">REVOLUSI BUDAYA SEKOLAH</span>
                    <h3 class="text-headline-md text-on-surface mt-1 mb-3">Menghapus Stigma "Ruang BK Tempat Anak Bermasalah"</h3>
                    <p class="text-body-md text-on-surface-variant leading-relaxed">
                        Selama puluhan tahun, dipanggil ke ruang BK identik dengan hukuman atau kenakalan. Sahabat Aman mengubah ruang BK menjadi oasis penyembuhan emosional, eksplorasi minat karir, dan wadah bercerita tanpa takut dicap negatif oleh teman sekelas.
                    </p>
                </div>
                <div class="mt-8 pt-6 border-t border-surface-container flex flex-wrap gap-4 text-on-surface-variant text-label-md">
                    <span class="flex items-center gap-1.5"><span class="material-symbols-outlined text-primary text-base">check_circle</span> Tanpa Notifikasi Terbuka</span>
                    <span class="flex items-center gap-1.5"><span class="material-symbols-outlined text-primary text-base">check_circle</span> Mode Samaran Identitas</span>
                    <span class="flex items-center gap-1.5"><span class="material-symbols-outlined text-primary text-base">check_circle</span> Bahasa Empatik Ramah Remaja</span>
                </div>
            </div>
            {{-- Privasi --}}
            <div class="bg-surface-container-lowest p-8 rounded-2xl border border-outline-variant/30 custom-pill-shadow flex flex-col justify-between" id="privasi">
                <div>
                    <div class="w-12 h-12 rounded-xl bg-surface-container flex items-center justify-center text-primary mb-6">
                        <span class="material-symbols-outlined text-2xl" style="font-variation-settings: 'FILL' 1;">lock</span>
                    </div>
                    <span class="text-label-md text-outline font-bold">KODE ETIK & PRIVASI</span>
                    <h3 class="text-title-md text-on-surface mt-1 mb-3">Enkripsi & Partisi Data Ketat</h3>
                    <p class="text-body-sm text-on-surface-variant leading-relaxed">
                        Pesan antar teman sebaya tidak tercampur dalam berkas resmi guru BK. Seluruh data identitas siswa dilindungi dengan enkripsi AES-256 dan hanya dibuka jika terdapat ancaman keselamatan nyawa.
                    </p>
                </div>
                <div class="mt-6">
                    <a class="inline-flex items-center gap-1 text-label-md text-primary font-semibold hover:underline" href="#">
                        <span>Baca Lembar Pakta Integritas</span>
                        <span class="material-symbols-outlined text-sm">chevron_right</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- SECTION 4: LANGKAH MUDAH --}}
<section class="py-20 bg-surface-container-low border-t border-surface-container">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center max-w-2xl mx-auto mb-16">
            <span class="text-label-md text-primary bg-surface-container px-3 py-1 rounded-full uppercase tracking-wider">Alur Sederhana</span>
            <h2 class="text-headline-lg font-bold text-on-surface mt-3">3 Langkah Memulai Curhat Tanpa Cemas</h2>
            <p class="text-body-md text-on-surface-variant mt-2">
                Proses dirancang seringan mungkin agar kamu tidak merasa terbebani saat hendak menuangkan apa yang ada di kepala.
            </p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-surface-container-lowest p-8 rounded-2xl border border-outline-variant/30 custom-pill-shadow flex flex-col">
                <div class="w-12 h-12 rounded-2xl bg-primary-fixed text-primary text-headline-sm font-bold flex items-center justify-center mb-6">1</div>
                <h3 class="text-title-md text-on-surface mb-2">Pilih Teman Bicara</h3>
                <p class="text-body-md text-on-surface-variant leading-relaxed mb-4 flex-grow">
                    Tentukan apakah kamu ingin berbagi cerita ke Teman Sebaya atau bimbingan terarah dengan Guru BK sekolah.
                </p>
                <div class="p-3 rounded-xl bg-surface-container-low text-on-surface-variant text-label-sm flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary text-base">visibility_off</span>
                    <span>Identitasmu dapat dirahasiakan</span>
                </div>
            </div>
            <div class="bg-surface-container-lowest p-8 rounded-2xl border border-outline-variant/30 custom-pill-shadow flex flex-col">
                <div class="w-12 h-12 rounded-2xl bg-primary-fixed text-primary text-headline-sm font-bold flex items-center justify-center mb-6">2</div>
                <h3 class="text-title-md text-on-surface mb-2">Tulis Apa yang Membebani</h3>
                <p class="text-body-md text-on-surface-variant leading-relaxed mb-4 flex-grow">
                    Ketikkan apapun yang kamu rasakan: tekanan tugas sekolah, pertemanan rumit, kecemasan masa depan, atau sekadar ingin didengarkan tanpa diceramahi.
                </p>
                <div class="p-3 rounded-xl bg-surface-container-low text-on-surface-variant text-label-sm flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary text-base">schedule</span>
                    <span>Tidak ada batas waktu mengetik</span>
                </div>
            </div>
            <div class="bg-surface-container-lowest p-8 rounded-2xl border border-outline-variant/30 custom-pill-shadow flex flex-col">
                <div class="w-12 h-12 rounded-2xl bg-primary-fixed text-primary text-headline-sm font-bold flex items-center justify-center mb-6">3</div>
                <h3 class="text-title-md text-on-surface mb-2">Dapatkan Respon Hangat</h3>
                <p class="text-body-md text-on-surface-variant leading-relaxed mb-4 flex-grow">
                    Konselor akan membalas dengan penuh penerimaan dan empati. Kamu bisa terus bertukar pesan hingga merasa lebih tenang, atau menentukan langkah pendampingan berikutnya.
                </p>
                <div class="p-3 rounded-xl bg-surface-container-low text-on-surface-variant text-label-sm flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary text-base">volunteer_activism</span>
                    <span>Dukungan tulus tanpa vonis</span>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- SECTION 5: TESTIMONI --}}
<section class="py-20">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center max-w-2xl mx-auto mb-16">
            <span class="text-label-md text-primary bg-primary-fixed/40 px-3 py-1 rounded-full uppercase tracking-wider">Suara dari Hati</span>
            <h2 class="text-headline-lg font-bold text-on-surface mt-3">Kisah Nyata di Balik Sahabat Aman</h2>
            <p class="text-body-md text-on-surface-variant mt-2">
                Bagaimana keberanian untuk bercerita membawa perubahan nyata bagi ketenangan belajar dan harmoni di lingkungan sekolah.
            </p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
            <div class="bg-surface-container-lowest p-8 rounded-2xl border border-outline-variant/30 custom-pill-shadow flex flex-col justify-between">
                <div>
                    <div class="flex items-center gap-1 text-tertiary mb-4">
                        <span class="material-symbols-outlined text-lg" style="font-variation-settings: 'FILL' 1;">star</span>
                        <span class="material-symbols-outlined text-lg" style="font-variation-settings: 'FILL' 1;">star</span>
                        <span class="material-symbols-outlined text-lg" style="font-variation-settings: 'FILL' 1;">star</span>
                        <span class="material-symbols-outlined text-lg" style="font-variation-settings: 'FILL' 1;">star</span>
                        <span class="material-symbols-outlined text-lg" style="font-variation-settings: 'FILL' 1;">star</span>
                    </div>
                    <blockquote class="text-body-lg text-on-surface italic leading-relaxed mb-6">
                        "Dulu aku selalu mengunci diri kalau panik menjelang ujian akhir dan ekspektasi orang tua. Takut mau ke ruang BK karena takut dikira kena masalah. Lewat Sahabat Aman, aku bisa curhat duluan ke Temanku. Tanggapannya bikin adem banget, dan akhirnya aku berani konseling tatap muka dengan tenang."
                    </blockquote>
                </div>
                <div class="flex items-center gap-3 pt-4 border-t border-surface-container">
                    <div class="w-11 h-11 rounded-full bg-surface-container flex items-center justify-center text-primary font-bold">
                        <span class="material-symbols-outlined">person</span>
                    </div>
                    <div>
                        <p class="text-title-md text-on-surface leading-tight">Siswa Kelas XII IPA</p>
                        <p class="text-label-sm text-outline">Identitas Terlindungi Demi Privasi</p>
                    </div>
                </div>
            </div>
            <div class="bg-surface-container-lowest p-8 rounded-2xl border border-outline-variant/30 custom-pill-shadow flex flex-col justify-between">
                <div>
                    <div class="flex items-center gap-1 text-tertiary mb-4">
                        <span class="material-symbols-outlined text-lg" style="font-variation-settings: 'FILL' 1;">star</span>
                        <span class="material-symbols-outlined text-lg" style="font-variation-settings: 'FILL' 1;">star</span>
                        <span class="material-symbols-outlined text-lg" style="font-variation-settings: 'FILL' 1;">star</span>
                        <span class="material-symbols-outlined text-lg" style="font-variation-settings: 'FILL' 1;">star</span>
                        <span class="material-symbols-outlined text-lg" style="font-variation-settings: 'FILL' 1;">star</span>
                    </div>
                    <blockquote class="text-body-lg text-on-surface italic leading-relaxed mb-6">
                        "Platform ini menjembatani jurang komunikasi antara murid dan guru bimbingan. Kami bisa mendeteksi siswa yang sedang mengalami tekanan psikososial jauh lebih awal sebelum berujung drop out atau penurunan nilai drastis. Penjadwalan tatap muka pun jadi tertib tanpa membuat siswa merasa malu."
                    </blockquote>
                </div>
                <div class="flex items-center gap-3 pt-4 border-t border-surface-container">
                    <div class="w-11 h-11 rounded-full bg-secondary-container flex items-center justify-center text-secondary font-bold">
                        <span class="material-symbols-outlined">school</span>
                    </div>
                    <div>
                        <p class="text-title-md text-on-surface leading-tight">Ibu Guru</p>
                        <p class="text-label-sm text-outline">Koordinator Guru BK</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- SECTION 6: FAQ --}}
<section class="py-16 bg-surface-container-low border-t border-surface-container" id="faq">
    <div class="max-w-3xl mx-auto px-6">
        <div class="text-center mb-12">
            <span class="text-label-md text-primary bg-surface-container px-3 py-1 rounded-full uppercase tracking-wider">Pertanyaan Umum</span>
            <h2 class="text-headline-lg font-bold text-on-surface mt-3">Hal yang Sering Ditanyakan</h2>
        </div>
        <div class="space-y-4">
            <details class="group bg-surface-container-lowest rounded-2xl p-6 border border-outline-variant/30 custom-pill-shadow open:border-primary/40 transition-all">
                <summary class="flex justify-between items-center cursor-pointer text-title-md text-on-surface list-none">
                    <span>Apakah wali kelas atau orang tua saya akan diberitahu isi curhat saya?</span>
                    <span class="material-symbols-outlined text-on-surface-variant transition-transform group-open:rotate-180">expand_more</span>
                </summary>
                <p class="text-body-md text-on-surface-variant mt-4 leading-relaxed border-t border-surface-container pt-3">
                    Tidak. Sesuai kode etik resmi bimbingan konseling dan undang-undang privasi data, isi percakapan konseling bersifat rahasia mutlak antara kamu dan konselor. Wali kelas atau orang tua hanya akan dilibatkan jika ada persetujuan darimu atau jika terjadi kondisi darurat yang mengancam keselamatan jiwamu.
                </p>
            </details>
            <details class="group bg-surface-container-lowest rounded-2xl p-6 border border-outline-variant/30 custom-pill-shadow open:border-primary/40 transition-all">
                <summary class="flex justify-between items-center cursor-pointer text-title-md text-on-surface list-none">
                    <span>Apa bedanya curhat ke Teman Sebaya vs Guru BK?</span>
                    <span class="material-symbols-outlined text-on-surface-variant transition-transform group-open:rotate-180">expand_more</span>
                </summary>
                <p class="text-body-md text-on-surface-variant mt-4 leading-relaxed border-t border-surface-container pt-3">
                    Teman Sebaya (Peer Listener) adalah sesama siswa terlatih untuk menjadi pendengar yang baik bagi keluhan ringan sehari-hari, stres tugas, atau obrolan santai. Sedangkan Guru BK adalah konselor profesional bersertifikat yang menangani pendampingan mendalam, arah karir, krisis trauma, dan advokasi akademik sekolah.
                </p>
            </details>
            <details class="group bg-surface-container-lowest rounded-2xl p-6 border border-outline-variant/30 custom-pill-shadow open:border-primary/40 transition-all">
                <summary class="flex justify-between items-center cursor-pointer text-title-md text-on-surface list-none">
                    <span>Bagaimana jika saya butuh konsultasi di luar jam sekolah?</span>
                    <span class="material-symbols-outlined text-on-surface-variant transition-transform group-open:rotate-180">expand_more</span>
                </summary>
                <p class="text-body-md text-on-surface-variant mt-4 leading-relaxed border-t border-surface-container pt-3">
                    Kamu bisa menuliskan pesan kapan saja 24 jam sehari. Guru BK atau Peer Counselor yang sedang bertugas akan merespon pada jam operasional berikutnya. Jika terjadi krisis mendesak di malam hari, kami menyediakan tombol siaga langsung menuju Hotline Psikologi Darurat Nasional.
                </p>
            </details>
        </div>
    </div>
</section>

{{-- SECTION 7: FINAL CTA --}}
<section class="py-20 relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-6">
        <div class="bg-primary-container text-on-primary rounded-3xl p-10 md:p-16 text-center relative overflow-hidden shadow-xl">
            <div class="absolute -bottom-20 -right-20 w-80 h-80 bg-primary-fixed-dim/20 rounded-full blur-2xl pointer-events-none"></div>
            <div class="absolute -top-20 -left-20 w-80 h-80 bg-on-primary-container/10 rounded-full blur-2xl pointer-events-none"></div>
            <div class="max-w-2xl mx-auto relative z-10">
                <div class="w-16 h-16 rounded-2xl bg-on-primary/10 flex items-center justify-center mx-auto mb-6 backdrop-blur-sm">
                    <span class="material-symbols-outlined text-on-primary text-3xl">spa</span>
                </div>
                <h2 class="text-headline-xl font-bold tracking-tight mb-4 text-white">
                    Jangan Pendam Sendiri.<br>Sahabat Aman Siap Menemanimu Hari Ini.
                </h2>
                <p class="text-body-lg text-on-primary-container mb-8 max-w-lg mx-auto">
                    Satu langkah kecil bercerita dapat meringankan beban berat yang kamu pikul. Pintu kami selalu terbuka tanpa penghakiman.
                </p>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-4 rounded-xl bg-surface-container-lowest text-primary font-title-md shadow-md hover:bg-surface-container-low transition-all" href="{{ route('curhat.create') }}">
                        <span class="material-symbols-outlined">edit_note</span>
                        <span>Masuk Portal Siswa Sekarang</span>
                    </a>
                    <a class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-4 rounded-xl bg-primary hover:bg-on-primary-fixed-variant text-white font-title-md transition-all" href="{{ route('login') }}">
                        <span class="material-symbols-outlined text-xl">calendar_month</span>
                        <span>Jadwalkan Konseling Bilik</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- FOOTER --}}
<footer class="bg-surface-container-lowest border-t border-surface-container text-on-surface-variant pt-16 pb-12">
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-10 mb-14">
            <div class="md:col-span-1">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-8 h-8 rounded-lg bg-primary-fixed flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined text-lg">spa</span>
                    </div>
                    <span class="text-headline-sm font-bold text-primary">Sahabat Aman BK</span>
                </div>
                <p class="text-body-sm text-on-surface-variant mb-6 leading-relaxed">
                    Inisiatif Digitalisasi Bimbingan & Konseling Sekolah Inklusif. Menjamin kerahasiaan, kesehatan mental siswa, dan hubungan positif di lingkungan pendidikan.
                </p>
                <div class="flex items-center gap-2 text-outline text-label-sm">
                    <span class="material-symbols-outlined text-primary text-base">verified</span>
                    <span>Didukung Standar ABKIN & Kemendikbudristek</span>
                </div>
            </div>
            <div>
                <h4 class="text-title-md text-on-surface mb-4">Akses Siswa</h4>
                <ul class="space-y-2.5 text-body-sm">
                    <li><a class="hover:text-primary transition-colors" href="{{ route('curhat.create') }}">Pilih Teman Sebaya</a></li>
                    <li><a class="hover:text-primary transition-colors" href="{{ route('riwayat') }}">Riwayat Curhat</a></li>
                    <li><a class="hover:text-primary transition-colors" href="{{ route('dashboard') }}">Kotak Masuk & Dialog</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-title-md text-on-surface mb-4">Portal Pendidik BK</h4>
                <ul class="space-y-2.5 text-body-sm">
                    <li><a class="hover:text-primary transition-colors" href="{{ route('admin.siswa') }}">Data & Riwayat Siswa</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-title-md text-on-surface mb-4">Bantuan & Lokasi</h4>
                <div class="space-y-3 text-body-sm">
                    <p class="flex items-start gap-2">
                        <span class="material-symbols-outlined text-primary text-base mt-0.5">location_on</span>
                        <span>Ruang Bimbingan Konseling</span>
                    </p>
                    <p class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-base">mail</span>
                        <span>konseling@sekolah.sch.id</span>
                    </p>
                    <p class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-error text-base">phone_in_talk</span>
                        <span class="font-semibold text-on-surface">Hotline Siaga: 0800-1</span>
                    </p>
                </div>
            </div>
        </div>
        <div class="border-t border-surface-container pt-8 flex flex-col md:flex-row items-center justify-between gap-4 text-label-sm text-outline">
            <p>&copy; 2025 Sahabat Aman &bull; Seluruh hak cipta dilindungi. Ruang aman bagi setiap siswa untuk tumbuh dan didengar.</p>
            <div class="flex items-center gap-6">
                <a class="hover:text-primary transition-colors" href="#">Kebijakan Privasi Siswa</a>
                <a class="hover:text-primary transition-colors" href="#">Syarat Penggunaan</a>
                <a class="hover:text-primary transition-colors" href="#">Integritas Data</a>
            </div>
        </div>
    </div>
</footer>
@endsection

@section('scripts')
<script>
document.querySelectorAll('.mood-pill').forEach(pill => {
    pill.addEventListener('click', () => {
        document.querySelectorAll('.mood-pill').forEach(p => {
            p.classList.remove('bg-primary-fixed', 'border', 'border-primary', 'text-primary');
            p.classList.add('border', 'border-outline-variant/50', 'text-on-surface-variant');
        });
        pill.classList.add('bg-primary-fixed', 'border', 'border-primary', 'text-primary');
        pill.classList.remove('border-outline-variant/50', 'text-on-surface-variant');
    });
});
</script>
@endsection

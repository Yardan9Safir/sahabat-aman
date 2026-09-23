@extends('layouts.app')

@section('title', 'Sahabat Aman - BK')

@section('content')

    {{-- HERO SECTION --}}
    <section class="relative pt-12 pb-20 md:pt-16 md:pb-28 overflow-hidden">
        <div class="max-w-3xl mx-auto text-center">
            {{-- Trust Badge --}}
            <div
                class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-surface-container-high border border-outline-variant/30 text-on-surface-variant mb-6">
                <span class="material-symbols-outlined text-primary text-base"
                    style="font-variation-settings: 'FILL' 1;">lock</span>
                <span class="text-label-md">100% Bebas Stigma &bull; Etika Kerahasiaan Terjamin</span>
            </div>
            {{-- Hero Headline --}}
            <h1 class="text-headline-xl font-bold tracking-tight text-on-surface text-balance mb-6 leading-tight">
                Setiap Cerita Berhak Didengar oleh <span
                    class="text-primary underline decoration-primary-fixed decoration-wavy underline-offset-8">Sahabat
                    Aman</span>
            </h1>
            {{-- Hero Subheadline --}}
            <p class="text-body-lg text-on-surface-variant text-balance max-w-2xl mx-auto mb-10">
                Bimbingan konseling sekolah modern tanpa tatap sinis atau rasa takut. Bebas curhat santai ke Konselor Sebaya
                atau konsultasi mendalam bersama Guru BK dalam ekosistem digital yang hangat dan
                terlindungi.
            </p>
            {{-- Hero CTA Button --}}
            <a class="w-full sm:w-auto inline-flex items-center justify-center gap-2.5 px-8 py-4 rounded-2xl bg-primary hover:bg-primary-container hover:text-on-primary-container text-on-primary font-title-md shadow-md hover:shadow-lg transition-all duration-200"
                href="{{ route('login') }}">
                <span class="material-symbols-outlined text-2xl">edit_note</span>
                <span>Mulai Curhat</span>
            </a>
        </div>
    </section>

    {{-- QUOTE OF THE DAY --}}
    @if (isset($dailyQuote) && $dailyQuote)
        <section class="max-w-3xl mx-auto -mt-10 md:-mt-14 mb-20">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <span
                    class="text-label-md text-primary bg-surface-container px-3 py-1 rounded-full uppercase tracking-wider">Kata
                    Kata Hari Ini</span>
                <h2 class="text-headline-lg font-bold text-on-surface mt-3">Kalimat inspiratif Serta
                    Penyemangat Untuk Hari Ini</h2>
                <p class="text-body-md text-on-surface-variant mt-2">
                    Temukan kata-kata sederhana yang bisa menemani langkahmu, menguatkan hati, dan membuat harimu terasa
                    sedikit lebih berarti.
                </p>
            </div>
            <div class="text-center max-w-2xl mx-auto mb-16">
                <blockquote
                    class="text-headline-sm md:text-title-xl text-on-surface italic font-medium leading-relaxed text-balance">
                    "{{ $dailyQuote->isi_quotes }}"</blockquote>
                <p class="text-label-md text-on-surface-variant">
                    {{ $dailyQuote->pembuat?->name ?? 'Unknown' }}
                </p>
            </div>
        </section>
    @endif

    {{-- SECTION 4: LANGKAH MUDAH --}}
    <section class="py-20 bg-surface-container-low border-t border-surface-container">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <span
                    class="text-label-md text-primary bg-surface-container px-3 py-1 rounded-full uppercase tracking-wider">Alur
                    Sederhana</span>
                <h2 class="text-headline-lg font-bold text-on-surface mt-3">3 Langkah Memulai Curhat Tanpa Cemas</h2>
                <p class="text-body-md text-on-surface-variant mt-2">
                    Proses dirancang seringan mungkin agar kamu tidak merasa terbebani saat hendak menuangkan apa yang ada
                    di kepala.
                </p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div
                    class="bg-surface-container-lowest p-8 rounded-2xl border border-outline-variant/30 custom-pill-shadow flex flex-col">
                    <div
                        class="w-12 h-12 rounded-2xl bg-primary-fixed text-primary text-headline-sm font-bold flex items-center justify-center mb-6">
                        1</div>
                    <h3 class="text-title-md text-on-surface mb-2">Pilih Teman Bicara</h3>
                    <p class="text-body-md text-on-surface-variant leading-relaxed mb-4 flex-grow">
                        Tentukan apakah kamu ingin berbagi cerita ke Konselor Sebaya atau bimbingan terarah dengan Guru BK
                        sekolah.
                    </p>
                    <div
                        class="p-3 rounded-xl bg-surface-container-low text-on-surface-variant text-label-sm flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-base">visibility_off</span>
                        <span>Identitasmu dapat dirahasiakan</span>
                    </div>
                </div>
                <div
                    class="bg-surface-container-lowest p-8 rounded-2xl border border-outline-variant/30 custom-pill-shadow flex flex-col">
                    <div
                        class="w-12 h-12 rounded-2xl bg-primary-fixed text-primary text-headline-sm font-bold flex items-center justify-center mb-6">
                        2</div>
                    <h3 class="text-title-md text-on-surface mb-2">Tulis Apa yang Membebani</h3>
                    <p class="text-body-md text-on-surface-variant leading-relaxed mb-4 flex-grow">
                        Ketikkan apapun yang kamu rasakan: tekanan tugas sekolah, pertemanan rumit, kecemasan masa depan,
                        atau sekadar ingin didengarkan tanpa diceramahi.
                    </p>
                    <div
                        class="p-3 rounded-xl bg-surface-container-low text-on-surface-variant text-label-sm flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-base">schedule</span>
                        <span>Tidak ada batas waktu mengetik</span>
                    </div>
                </div>
                <div
                    class="bg-surface-container-lowest p-8 rounded-2xl border border-outline-variant/30 custom-pill-shadow flex flex-col">
                    <div
                        class="w-12 h-12 rounded-2xl bg-primary-fixed text-primary text-headline-sm font-bold flex items-center justify-center mb-6">
                        3</div>
                    <h3 class="text-title-md text-on-surface mb-2">Dapatkan Respon Hangat</h3>
                    <p class="text-body-md text-on-surface-variant leading-relaxed mb-4 flex-grow">
                        Konselor akan membalas dengan penuh penerimaan dan empati. Kamu bisa terus bertukar pesan hingga
                        merasa lebih tenang, atau menentukan langkah pendampingan berikutnya.
                    </p>
                    <div
                        class="p-3 rounded-xl bg-surface-container-low text-on-surface-variant text-label-sm flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-base">volunteer_activism</span>
                        <span>Dukungan tulus tanpa vonis</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- SECTION 5: FAQ --}}
    <section class="py-16 bg-surface-container-low border-t border-surface-container" id="faq">
        <div class="max-w-3xl mx-auto px-6">
            <div class="text-center mb-12">
                <span
                    class="text-label-md text-primary bg-surface-container px-3 py-1 rounded-full uppercase tracking-wider">Pertanyaan
                    Umum</span>
                <h2 class="text-headline-lg font-bold text-on-surface mt-3">Hal yang Sering Ditanyakan</h2>
            </div>
            <div class="space-y-4">
                <details
                    class="group bg-surface-container-lowest rounded-2xl p-6 border border-outline-variant/30 custom-pill-shadow open:border-primary/40 transition-all">
                    <summary
                        class="flex justify-between items-center cursor-pointer text-title-md text-on-surface list-none">
                        <span>Apakah wali kelas atau orang tua saya akan diberitahu isi curhat saya?</span>
                        <span
                            class="material-symbols-outlined text-on-surface-variant transition-transform group-open:rotate-180">expand_more</span>
                    </summary>
                    <p
                        class="text-body-md text-on-surface-variant mt-4 leading-relaxed border-t border-surface-container pt-3">
                        Tidak. Sesuai kode etik resmi bimbingan konseling dan undang-undang privasi data, isi percakapan
                        konseling bersifat rahasia mutlak antara kamu dan konselor. Wali kelas atau orang tua hanya akan
                        dilibatkan jika ada persetujuan darimu atau jika terjadi kondisi darurat yang mengancam keselamatan
                        jiwamu.
                    </p>
                </details>
                <details
                    class="group bg-surface-container-lowest rounded-2xl p-6 border border-outline-variant/30 custom-pill-shadow open:border-primary/40 transition-all">
                    <summary
                        class="flex justify-between items-center cursor-pointer text-title-md text-on-surface list-none">
                        <span>Apa bedanya curhat ke Konselor Sebaya vs Guru BK?</span>
                        <span
                            class="material-symbols-outlined text-on-surface-variant transition-transform group-open:rotate-180">expand_more</span>
                    </summary>
                    <p
                        class="text-body-md text-on-surface-variant mt-4 leading-relaxed border-t border-surface-container pt-3">
                        Konselor Sebaya (Peer Counselor) adalah siswa yang dilatih untuk menjadi pendengar yang baik bagi
                        keluhan ringan sehari-hari, stres tugas, atau obrolan santai. Sedangkan Guru BK adalah konselor
                        profesional bersertifikat yang menangani pendampingan mendalam, arah karir, krisis trauma, dan
                        advokasi akademik sekolah.
                    </p>
                </details>
                <details
                    class="group bg-surface-container-lowest rounded-2xl p-6 border border-outline-variant/30 custom-pill-shadow open:border-primary/40 transition-all">
                    <summary
                        class="flex justify-between items-center cursor-pointer text-title-md text-on-surface list-none">
                        <span>Bagaimana jika saya butuh konsultasi di luar jam sekolah?</span>
                        <span
                            class="material-symbols-outlined text-on-surface-variant transition-transform group-open:rotate-180">expand_more</span>
                    </summary>
                    <p
                        class="text-body-md text-on-surface-variant mt-4 leading-relaxed border-t border-surface-container pt-3">
                        Kamu bisa menuliskan pesan kapan saja 24 jam sehari. Guru BK atau Peer Counselor yang sedang
                        bertugas akan merespon pada jam operasional berikutnya. Jika terjadi krisis mendesak di malam hari,
                        kami menyediakan tombol siaga langsung menuju Hotline Psikologi Darurat Nasional.
                    </p>
                </details>
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
                        Inisiatif Digitalisasi Bimbingan & Konseling Sekolah Inklusif. Menjamin kerahasiaan, kesehatan
                        mental siswa, dan hubungan positif di lingkungan pendidikan.
                    </p>
                </div>
                <div>
                    <h4 class="text-title-md text-on-surface mb-4">Akses Siswa</h4>
                    <ul class="space-y-2.5 text-body-sm">
                        <li><a class="hover:text-primary transition-colors" href="{{ route('curhat.create') }}">Pilih
                                Konselor Sebaya</a></li>
                        <li><a class="hover:text-primary transition-colors" href="{{ route('riwayat') }}">Riwayat
                                Curhat</a></li>
                        <li><a class="hover:text-primary transition-colors" href="{{ route('dashboard') }}">Kotak Masuk &
                                Dialog</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-title-md text-on-surface mb-4">Portal Pendidik BK</h4>
                    <ul class="space-y-2.5 text-body-sm">
                        <li><a class="hover:text-primary transition-colors" href="{{ route('admin.index') }}">Ikhtisar
                                Konseling</a></li>
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
                    </div>
                </div>
            </div>
            <div
                class="border-t border-surface-container pt-8 flex flex-col md:flex-row items-center justify-between gap-4 text-label-sm text-outline">
                <p>&copy; 2026 Sahabat Aman</p>
            </div>
        </div>
    </footer>
@endsection

@section('scripts')
    <script>
        document.querySelectorAll('.mood-pill').forEach(pill => {
            pill.addEventListener('click', () => {
                document.querySelectorAll('.mood-pill').forEach(p => {
                    p.classList.remove('bg-primary-fixed', 'border', 'border-primary',
                        'text-primary');
                    p.classList.add('border', 'border-outline-variant/50',
                        'text-on-surface-variant');
                });
                pill.classList.add('bg-primary-fixed', 'border', 'border-primary', 'text-primary');
                pill.classList.remove('border-outline-variant/50', 'text-on-surface-variant');
            });
        });
    </script>
@endsection

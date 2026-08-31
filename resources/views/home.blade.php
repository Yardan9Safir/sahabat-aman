@extends('layouts.app')

@section('title', 'Sahabat Aman - Curhat Aman')

@section('content')
<div class="space-y-8">
    <section class="rounded-4xl bg-linear-to-br from-[#EAF3EE] via-[#F8F5F0] to-[#F1E4D9] p-6 shadow-[0_18px_45px_rgba(69,102,89,0.08)] sm:p-10">
        <div class="grid items-center gap-8 lg:grid-cols-[1.2fr_0.8fr]">
            <div>
                <p class="mb-4 inline-flex rounded-full bg-[#DDECE3] px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-[#456B5A]">Sahabat Aman</p>
                <h1 class="max-w-xl text-4xl font-black leading-tight text-[#214D41] sm:text-5xl">Curhatmu aman, dan tetap didengar.</h1>
                <p class="mt-5 max-w-xl text-base leading-7 text-[#496D62]">Platform yang membantu siswa menyampaikan keluhan dengan nyaman, baik ke teman sebaya maupun Guru BK dan konselor, tanpa rasa takut dihakimi.</p>
                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ route('curhat.create') }}" class="rounded-full bg-[#5B7F71] px-6 py-3 text-sm font-semibold text-white shadow-md hover:bg-[#496A5E]">Mulai Curhat</a>
                    <a href="{{ route('login') }}" class="rounded-full border border-[#7C9885] bg-white/60 px-6 py-3 text-sm font-semibold text-[#355C4F]">Masuk</a>
                </div>
            </div>
            <div class="rounded-[28px] bg-[#F9F6F2] p-5 shadow-inner ring-1 ring-[#DCD2C6]">
                <div class="space-y-4">
                    <div class="flex items-center justify-between rounded-2xl bg-[#E7F2EC] p-4">
                        <div>
                            <p class="text-xs uppercase tracking-[0.2em] text-[#507163]">Status</p>
                            <p class="mt-2 text-lg font-bold text-[#214D41]">Terkirim</p>
                        </div>
                        <span class="rounded-full bg-[#E7F2EC] px-3 py-1 text-xs font-bold text-[#3C685E]">Aman</span>
                    </div>
                    <div class="rounded-2xl bg-white p-4 shadow-sm ring-1 ring-[#E5DFD6]">
                        <p class="text-xs uppercase tracking-[0.2em] text-[#7A806D]">Curhatan</p>
                        <p class="mt-3 text-sm leading-6 text-[#384B46]">Saya merasa cemas saat ujian, tapi takut bilang ke orang tua. Ingin bercerita dengan teman yang bisa mengerti.</p>
                    </div>
                    <div class="flex gap-3">
                        <div class="flex-1 rounded-2xl bg-[#F7E7D5] p-3">
                            <p class="text-[11px] uppercase tracking-[0.2em] text-[#8D6C4C]">Tujuan</p>
                            <p class="mt-2 font-bold text-[#4F3B2C]">Teman Sebaya</p>
                        </div>
                        <div class="flex-1 rounded-2xl bg-[#DDEAF4] p-3">
                            <p class="text-[11px] uppercase tracking-[0.2em] text-[#4E677D]">Kategori</p>
                            <p class="mt-2 font-bold text-[#2F4658]">Belajar</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="grid gap-5 md:grid-cols-3">
        <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-[#E9E0D6]">
            <div class="mb-4 text-3xl">💬</div>
            <h2 class="text-xl font-bold text-[#214D41]">Curhat mudah</h2>
            <p class="mt-3 text-sm leading-6 text-[#587067]">Pilih tujuan, kategori, dan kirim cerita dengan mode anonim yang nyaman.</p>
        </div>
        <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-[#E9E0D6]">
            <div class="mb-4 text-3xl">🛡️</div>
            <h2 class="text-xl font-bold text-[#214D41]">Privasi dijaga</h2>
            <p class="mt-3 text-sm leading-6 text-[#587067]">Identitas siswa tetap terhubung ke akun, tetapi disembunyikan dari penerima saat anonim aktif.</p>
        </div>
        <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-[#E9E0D6]">
            <div class="mb-4 text-3xl">📈</div>
            <h2 class="text-xl font-bold text-[#214D41]">Status jelas</h2>
            <p class="mt-3 text-sm leading-6 text-[#587067]">Tiada kebingungan: terkirim, dilihat, dibalas, sampai ditangani.</p>
        </div>
    </section>
</div>
@endsection

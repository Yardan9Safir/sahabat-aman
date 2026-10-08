@extends('layouts.admin')

@section('title', 'Kelola Pengguna - Sahabat Aman')

@section('content')
    <section
        class="bg-surface-container-lowest p-5 rounded-2xl border border-outline-variant custom-shadow-resting flex flex-col gap-4 relative overflow-hidden">
        <div class="max-w-7xl mx-auto w-full space-y-6">

            <section class="flex flex-wrap items-end justify-between gap-3">
                <div>
                    <h1 class="text-headline-lg font-bold text-on-surface">Kelola Pengguna</h1>
                    <p class="text-body-md text-on-surface-variant mt-1">Cari, ubah data, atau hapus akun pengguna.</p>
                </div>
                <a href="{{ route('admin.users.create') }}"
                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-primary text-on-primary text-label-lg font-bold shadow-md hover:bg-primary-container hover:-translate-y-0.5 transition-all">
                    <span class="material-symbols-outlined text-xl">person_add</span> Tambah Akun
                </a>
            </section>

            {{-- Pencarian --}}
            <form method="GET" action="{{ route('admin.users.index') }}"
                class="flex flex-wrap items-center gap-2 p-4 bg-surface-container-lowest rounded-2xl border border-outline-variant/60 custom-shadow-resting">
                <div
                    class="flex items-center gap-2 flex-1 min-w-56 bg-surface-container-low border-1.5 border-outline-variant rounded-xl px-3.5 py-2.5 focus-within:border-primary-container transition-colors">
                    <span class="material-symbols-outlined text-outline text-xl">search</span>
                    <input type="search" name="q" value="{{ $q }}"
                        placeholder="Cari nama, email, atau NIS/NIP..."
                        class="flex-1 bg-transparent text-body-md text-on-surface placeholder:text-outline/70 outline-none">
                </div>
                <button type="submit"
                    class="px-5 py-2.5 rounded-xl bg-primary text-on-primary text-label-lg font-bold shadow-md hover:bg-primary-container hover:-translate-y-0.5 transition-all">
                    Cari
                </button>
                <select name="role" onchange="this.form.submit()"
                    class="px-4 py-2.5 rounded-xl bg-surface-container-lowest text-on-surface text-label-lg font-semibold border border-outline-variant/60 hover:bg-surface-container-low transition-colors cursor-pointer">
                    <option value="">Semua Peran</option>
                    @foreach (\App\Models\User::ROLES as $roleOption)
                        <option value="{{ $roleOption }}" @selected($role === $roleOption)>
                            {{ \App\Models\User::make(['role' => $roleOption])->roleLabel() }}</option>
                    @endforeach
                </select>
                @if ($q !== '' || $role !== '')
                    <a href="{{ route('admin.users.index') }}"
                        class="px-5 py-2.5 rounded-xl bg-surface-container text-on-surface-variant text-label-lg font-semibold hover:bg-surface-container-high transition-colors">
                        Reset
                    </a>
                @endif
            </form>

            {{-- Tabel pengguna --}}
            <section
                class="bg-surface-container-lowest rounded-2xl border border-outline-variant/60 custom-shadow-resting overflow-hidden">
                <div class="p-5 overflow-x-auto" data-datatable>
                    {!! $dataTable->table(['class' => 'dt-users w-full']) !!}
                </div>
            </section>

        </div>
    </section>

    {{-- Konfigurasi DataTables, dibaca oleh resources/js/datatables.js --}}
    <script type="application/json" data-datatable-config>{!! $dataTable->generateJson() !!}</script>
@endsection

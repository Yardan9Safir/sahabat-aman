@extends('layouts.admin')

@section('title', 'Tambah Quotes - BK')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div>
        <h1 class="text-headline-lg font-bold text-on-surface">Tambah Quotes</h1>
        <p class="text-body-md text-on-surface-variant mt-1">Buat kata-kata bijak untuk ditampilkan kepada siswa.</p>
    </div>

    @include('admin.quotes._form', [
        'action' => route('admin.quotes.store'),
        'method' => 'POST',
        'submitLabel' => 'Simpan Quotes',
        'submitIcon' => 'add_notes',
    ])
</div>
@endsection
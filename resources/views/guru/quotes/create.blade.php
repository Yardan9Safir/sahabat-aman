@extends('layouts.app')

@section('title', 'Tambah Quotes - Guru BK')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div>
        <h1 class="text-headline-lg font-bold text-on-surface">Tambah Quotes</h1>
        <p class="text-body-md text-on-surface-variant mt-1">Buat kata-kata bijak untuk koleksi bersama.</p>
    </div>

    @include('quotes._form', [
        'action' => route('guru.quotes.store'),
        'method' => 'POST',
        'submitLabel' => 'Simpan Quotes',
        'submitIcon' => 'add_notes',
        'cancelUrl' => route('guru.quotes.index'),
    ])
</div>
@endsection

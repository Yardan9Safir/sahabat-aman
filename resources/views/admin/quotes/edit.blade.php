@extends('layouts.admin')

@section('title', 'Edit Quotes - Guru BK')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div>
        <h1 class="text-headline-lg font-bold text-on-surface">Edit Quotes</h1>
        <p class="text-body-md text-on-surface-variant mt-1">Perbarui kata-kata bijak yang pernah dibuat.</p>
    </div>

    @include('quotes._form', [
        'action' => route('admin.quotes.update', $quote),
        'method' => 'PUT',
        'submitLabel' => 'Simpan Perubahan',
        'submitIcon' => 'save',
        'quote' => $quote,
        'cancelUrl' => route('admin.quotes.index'),
    ])
</div>
@endsection

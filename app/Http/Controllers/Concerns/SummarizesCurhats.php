<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Curhat;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

trait SummarizesCurhats
{
    /**
     * Ambil filter kategori & status dari query string, abaikan nilai yang tidak valid.
     *
     * @return array{kategori: ?string, status: ?string}
     */
    protected function curhatFilters(Request $request): array
    {
        return [
            'kategori' => in_array($request->query('kategori'), Curhat::KATEGORI, true)
                ? $request->query('kategori')
                : null,
            'status' => in_array($request->query('status'), Curhat::STATUS, true)
                ? $request->query('status')
                : null,
        ];
    }

    /**
     * Bangun data ringkasan dashboard: daftar curhat, jumlah per kategori, jumlah per status.
     *
     * @param  array<int, string>  $relations  relasi yang perlu di-eager load
     * @return array{curhats: Collection, jumlahKategori: Collection, jumlahStatus: Collection}
     */
    protected function summarizeCurhats(Builder $dasar, ?string $kategori, ?string $status, array $relations = []): array
    {
        $jumlahKategori = (clone $dasar)
            ->status($status)
            ->selectRaw('kategori, COUNT(*) as total')
            ->groupBy('kategori')
            ->pluck('total', 'kategori');

        $jumlahStatus = (clone $dasar)
            ->kategori($kategori)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $curhats = (clone $dasar)
            ->kategori($kategori)
            ->status($status)
            ->with($relations)
            ->latest()
            ->get();

        return compact('curhats', 'jumlahKategori', 'jumlahStatus');
    }
}

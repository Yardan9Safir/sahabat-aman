<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Quote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

trait ManagesQuotes
{
    protected function validateQuote(Request $request): array
    {
        return $request->validate([
            'judul_quotes' => ['required', 'string', 'max:255'],
            'category_quotes' => ['required', 'in:'.implode(',', Quote::CATEGORIES)],
            'isi_quotes' => ['required', 'string', 'min:10', 'max:1000'],
            'is_daily' => ['sometimes', 'boolean'],
        ]);
    }

    protected function createQuote(Request $request, array $validated, bool $isDaily): Quote
    {
        return DB::transaction(function () use ($request, $validated, $isDaily) {
            if ($isDaily) {
                Quote::query()->update(['is_daily' => false]);
            }

            return Quote::create([
                'id_pembuat' => $request->user()->id,
                'judul_quotes' => $validated['judul_quotes'],
                'category_quotes' => $validated['category_quotes'],
                'isi_quotes' => $validated['isi_quotes'],
                'is_daily' => $isDaily,
            ]);
        });
    }

    protected function updateQuote(Quote $quote, array $validated, bool $isDaily): void
    {
        DB::transaction(function () use ($quote, $validated, $isDaily) {
            if ($isDaily) {
                Quote::where('id_quotes', '!=', $quote->id_quotes)->update(['is_daily' => false]);
            }

            $quote->update([
                'judul_quotes' => $validated['judul_quotes'],
                'category_quotes' => $validated['category_quotes'],
                'isi_quotes' => $validated['isi_quotes'],
                'is_daily' => $isDaily,
            ]);
        });
    }

    protected function makeDaily(Quote $quote): void
    {
        DB::transaction(function () use ($quote) {
            Quote::where('id_quotes', '!=', $quote->id_quotes)->update(['is_daily' => false]);
            $quote->update(['is_daily' => true]);
        });
    }
}

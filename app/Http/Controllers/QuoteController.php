<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuoteController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->string('category')->toString() ?: null;
        $mine = $request->boolean('mine');

        $quotes = Quote::query()
            ->with('pembuat')
            ->kategori($category)
            ->when($mine, fn ($query) => $query->dibuatOleh($request->user()))
            ->latest('id_quotes')
            ->get();

        return view('quotes.index', [
            'quotes' => $quotes,
            'category' => $category,
            'mine' => $mine,
            'total' => Quote::count(),
            'counts' => Quote::query()
                ->selectRaw('category_quotes, COUNT(*) as total')
                ->groupBy('category_quotes')
                ->pluck('total', 'category_quotes'),
        ]);
    }

    public function create()
    {
        return view('quotes.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validateQuote($request);
        $isDaily = $request->user()->isGuru() && $request->boolean('is_daily');

        DB::transaction(function () use ($validated, $request, $isDaily) {
            if ($isDaily) {
                Quote::query()->update(['is_daily' => false]);
            }

            Quote::create([
                'id_pembuat' => $request->user()->id,
                'judul_quotes' => $validated['judul_quotes'],
                'category_quotes' => $validated['category_quotes'],
                'isi_quotes' => $validated['isi_quotes'],
                'is_daily' => $isDaily,
            ]);
        });

        return redirect()->route('quotes.index')
            ->with('success', 'Quotes berhasil ditambahkan.');
    }

    public function edit(Request $request, Quote $quote)
    {
        $this->authorize('update', $quote);

        return view('quotes.edit', compact('quote'));
    }

    public function update(Request $request, Quote $quote)
    {
        $this->authorize('update', $quote);

        $validated = $this->validateQuote($request);
        $isDaily = $request->user()->isGuru() && $request->boolean('is_daily');

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

        return redirect()->route('quotes.index')
            ->with('success', 'Quotes berhasil diperbarui.');
    }

    public function destroy(Request $request, Quote $quote)
    {
        $this->authorize('delete', $quote);

        $quote->delete();

        return redirect()->route('quotes.index')
            ->with('success', 'Quotes berhasil dihapus.');
    }

    public function setDaily(Quote $quote)
    {
        $this->authorize('setDaily', Quote::class);

        DB::transaction(function () use ($quote) {
            Quote::where('id_quotes', '!=', $quote->id_quotes)->update(['is_daily' => false]);
            $quote->update(['is_daily' => true]);
        });

        return redirect()->route('quotes.index')
            ->with('success', 'Quotes terpilih sebagai kata-kata hari ini.');
    }

    protected function validateQuote(Request $request): array
    {
        return $request->validate([
            'judul_quotes' => ['required', 'string', 'max:255'],
            'category_quotes' => ['required', 'in:'.implode(',', Quote::CATEGORIES)],
            'isi_quotes' => ['required', 'string', 'min:10', 'max:1000'],
            'is_daily' => ['sometimes', 'boolean'],
        ]);
    }
}

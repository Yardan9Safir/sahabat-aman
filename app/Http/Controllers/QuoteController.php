<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class QuoteController extends Controller
{
    public function index(Request $request)
    {
        $query = Quote::query()->with('pembuat');

        if ($request->filled('category')) {
            $query->where('category_quotes', $request->category);
        }

        $quotes = $query->latest()->get();

        return view('admin.quotes.index', compact('quotes'));
    }

    public function create()
    {
        return view('admin.quotes.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validateQuote($request);

        DB::transaction(function () use ($validated, $request) {
            $isDaily = $request->boolean('is_daily');

            if ($isDaily) {
                Quote::query()->update(['is_daily' => false]);
            }

            Quote::create([
                'id_pembuat' => Auth::id(),
                'judul_quotes' => $validated['judul_quotes'],
                'category_quotes' => $validated['category_quotes'],
                'isi_quotes' => $validated['isi_quotes'],
                'is_daily' => $isDaily,
            ]);
        });

        return redirect()->route('admin.quotes.index')
            ->with('success', 'Quotes berhasil ditambahkan.');
    }

    public function edit(Quote $quote)
    {
        return view('admin.quotes.edit', compact('quote'));
    }

    public function update(Quote $quote, Request $request)
    {
        $validated = $this->validateQuote($request);

        DB::transaction(function () use ($quote, $validated, $request) {
            $isDaily = $request->boolean('is_daily');

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

        return redirect()->route('admin.quotes.index')
            ->with('success', 'Quotes berhasil diperbarui.');
    }

    public function destroy(Quote $quote)
    {
        $quote->delete();

        return redirect()->route('admin.quotes.index')
            ->with('success', 'Quotes berhasil dihapus.');
    }

    public function setDaily(Quote $quote)
    {
        DB::transaction(function () use ($quote) {
            Quote::where('id_quotes', '!=', $quote->id_quotes)->update(['is_daily' => false]);
            $quote->update(['is_daily' => true]);
        });

        return redirect()->route('admin.quotes.index')
            ->with('success', 'Quotes terpilih sebagai kata-kata hari ini.');
    }

    protected function validateQuote(Request $request): array
    {
        return $request->validate([
            'judul_quotes' => ['required', 'string', 'max:255'],
            'category_quotes' => ['required', 'in:' . implode(',', Quote::CATEGORIES)],
            'isi_quotes' => ['required', 'string', 'min:10', 'max:1000'],
            'is_daily' => ['sometimes', 'boolean'],
        ]);
    }
}
<?php

namespace App\Http\Controllers\Siswa;

use App\DataTables\QuoteDataTable;
use App\Http\Controllers\Concerns\ManagesQuotes;
use App\Http\Controllers\Controller;
use App\Models\Quote;
use Illuminate\Http\Request;

class QuoteController extends Controller
{
    use ManagesQuotes;

    public function index(Request $request)
    {
        return view('quotes.index', [
            'dataTable' => app(QuoteDataTable::class)->html(),
            'category' => $request->string('category')->toString() ?: null,
            'mine' => $request->boolean('mine'),
            'total' => Quote::count(),
            'counts' => Quote::query()
                ->selectRaw('category_quotes, COUNT(*) as total')
                ->groupBy('category_quotes')
                ->pluck('total', 'category_quotes'),
        ]);
    }

    public function dataTable()
    {
        return app(QuoteDataTable::class)->make()->toJson();
    }

    public function create()
    {
        return view('quotes.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validateQuote($request);
        $isDaily = $request->user()->canSetDailyQuote() && $request->boolean('is_daily');

        $this->createQuote($request, $validated, $isDaily);

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
        $isDaily = $request->user()->canSetDailyQuote() && $request->boolean('is_daily');

        $this->updateQuote($quote, $validated, $isDaily);

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

        $this->makeDaily($quote);

        return redirect()->route('quotes.index')
            ->with('success', 'Quotes terpilih sebagai kata-kata hari ini.');
    }
}

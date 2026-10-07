<?php

namespace App\Http\Controllers\Guru;

use App\DataTables\QuoteDataTable;
use App\Http\Controllers\Concerns\ManagesQuotes;
use App\Http\Controllers\Controller;
use App\Models\Quote;
use Illuminate\Http\Request;

class QuoteController extends Controller
{
    use ManagesQuotes;

    /**
     * Nama route guru supaya tabel dan form tetap berada di panel guru.
     *
     * @var array<string, string>
     */
    protected array $routes = [
        'data' => 'guru.quotes.data',
        'edit' => 'guru.quotes.edit',
        'update' => 'guru.quotes.update',
        'destroy' => 'guru.quotes.destroy',
        'daily' => 'guru.quotes.daily',
    ];

    public function index(Request $request)
    {
        return view('guru.quotes.index', [
            'dataTable' => $this->quoteDataTable()->html(),
            'category' => $request->string('category')->toString() ?: null,
            'total' => Quote::count(),
            'counts' => Quote::query()
                ->selectRaw('category_quotes, COUNT(*) as total')
                ->groupBy('category_quotes')
                ->pluck('total', 'category_quotes'),
        ]);
    }

    public function dataTable()
    {
        return $this->quoteDataTable()->make()->toJson();
    }

    public function create()
    {
        return view('guru.quotes.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validateQuote($request);
        $isDaily = $request->user()->canSetDailyQuote() && $request->boolean('is_daily');

        $this->createQuote($request, $validated, $isDaily);

        return redirect()->route('guru.quotes.index')
            ->with('success', 'Quotes berhasil ditambahkan.');
    }

    public function edit(Quote $quote)
    {
        $this->authorize('update', $quote);

        return view('guru.quotes.edit', compact('quote'));
    }

    public function update(Request $request, Quote $quote)
    {
        $this->authorize('update', $quote);

        $validated = $this->validateQuote($request);
        $isDaily = $request->user()->canSetDailyQuote() && $request->boolean('is_daily');

        $this->updateQuote($quote, $validated, $isDaily);

        return redirect()->route('guru.quotes.index')
            ->with('success', 'Quotes berhasil diperbarui.');
    }

    public function destroy(Quote $quote)
    {
        $this->authorize('delete', $quote);

        $quote->delete();

        return redirect()->route('guru.quotes.index')
            ->with('success', 'Quotes berhasil dihapus.');
    }

    public function setDaily(Quote $quote)
    {
        $this->authorize('setDaily', Quote::class);

        $this->makeDaily($quote);

        return redirect()->route('guru.quotes.index')
            ->with('success', 'Quotes terpilih sebagai kata-kata hari ini.');
    }

    protected function quoteDataTable(): QuoteDataTable
    {
        return app(QuoteDataTable::class)->usingRoutes($this->routes);
    }
}

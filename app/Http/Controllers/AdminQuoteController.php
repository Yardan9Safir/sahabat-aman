<?php

namespace App\Http\Controllers;

use App\DataTables\QuoteDataTable;
use App\Http\Controllers\Concerns\ManagesQuotes;
use App\Models\Quote;
use Illuminate\Http\Request;

class AdminQuoteController extends Controller
{
    use ManagesQuotes;

    /**
     * Nama route admin supaya tabel dan form tetap berada di panel admin.
     *
     * @var array<string, string>
     */
    protected array $routes = [
        'data' => 'admin.quotes.data',
        'edit' => 'admin.quotes.edit',
        'update' => 'admin.quotes.update',
        'destroy' => 'admin.quotes.destroy',
        'daily' => 'admin.quotes.daily',
    ];

    public function index(Request $request)
    {
        return view('admin.quotes.index', [
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
        return view('admin.quotes.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validateQuote($request);

        $this->createQuote($request, $validated, $request->boolean('is_daily'));

        return redirect()->route('admin.quotes.index')
            ->with('success', 'Quotes berhasil ditambahkan.');
    }

    public function edit(Quote $quote)
    {
        $this->authorize('update', $quote);

        return view('admin.quotes.edit', compact('quote'));
    }

    public function update(Request $request, Quote $quote)
    {
        $this->authorize('update', $quote);

        $validated = $this->validateQuote($request);

        $this->updateQuote($quote, $validated, $request->boolean('is_daily'));

        return redirect()->route('admin.quotes.index')
            ->with('success', 'Quotes berhasil diperbarui.');
    }

    public function destroy(Quote $quote)
    {
        $this->authorize('delete', $quote);

        $quote->delete();

        return redirect()->route('admin.quotes.index')
            ->with('success', 'Quotes berhasil dihapus.');
    }

    public function setDaily(Quote $quote)
    {
        $this->authorize('setDaily', Quote::class);

        $this->makeDaily($quote);

        return redirect()->route('admin.quotes.index')
            ->with('success', 'Quotes terpilih sebagai kata-kata hari ini.');
    }

    protected function quoteDataTable(): QuoteDataTable
    {
        return app(QuoteDataTable::class)->usingRoutes($this->routes);
    }
}

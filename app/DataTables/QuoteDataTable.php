<?php

namespace App\DataTables;

use App\Models\Quote;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;

class QuoteDataTable
{
    /**
     * Nama route yang dipakai tabel ini, supaya bisa dipakai baik di halaman
     * quotes global maupun di halaman quotes admin.
     *
     * @var array<string, string>
     */
    protected array $routes = [
        'data' => 'quotes.data',
        'edit' => 'quotes.edit',
        'update' => 'quotes.update',
        'destroy' => 'quotes.destroy',
        'daily' => 'admin.quotes.daily',
    ];

    /**
     * @param  array<string, string>  $routes
     */
    public function usingRoutes(array $routes): static
    {
        $this->routes = array_merge($this->routes, $routes);

        return $this;
    }

    /**
     * Konfigurasi DataTables untuk sisi server (JSON).
     */
    public function make(): EloquentDataTable
    {
        return DataTables::eloquent($this->query())
            ->setRowId('id_quotes')
            ->editColumn('category_quotes', fn ($quote) => $this->categoryBadge($quote))
            ->editColumn('isi_quotes', fn ($quote) => Str::limit((string) $quote->isi_quotes, 90))
            ->editColumn('created_at', fn ($quote) => $quote->created_at?->diffForHumans() ?? '-')
            ->addColumn('author_name', fn ($quote) => $quote->displayAuthor())
            ->addColumn('status', fn ($quote) => $this->statusBadges($quote))
            ->addColumn('action', fn ($quote) => $this->actionButtons($quote))
            ->rawColumns(['category_quotes', 'status', 'action'])
            ->filter(function ($query) {
                $category = request()->string('category')->toString();

                if (in_array($category, Quote::CATEGORIES, true)) {
                    $query->where('category_quotes', $category);
                }

                if (request()->boolean('mine')) {
                    $query->where('id_pembuat', auth()->id());
                }
            }, true);
    }

    /**
     * Konfigurasi DataTables untuk sisi klien (tombol, pencarian, paginasi).
     */
    public function html(): HtmlBuilder
    {
        return app('datatables.html')
            ->setTableId('quotes-table')
            ->columns($this->columns())
            ->serverSide()
            ->processing()
            ->paging()
            ->searching()
            ->info()
            ->ordering()
            ->autoWidth(false)
            ->lengthChange(false)
            ->orderBy(0, 'desc')
            ->ajax([
                'url' => route($this->routes['data']),
                'type' => 'GET',
                'data' => [
                    'category' => request()->string('category')->toString(),
                    'mine' => request()->boolean('mine') ? 1 : 0,
                ],
            ])
            ->parameters([
                'dom' => 'rt<"dt-footer flex flex-wrap items-center justify-between gap-4 mt-4"ip>',
                'pageLength' => 10,
                'searchDelay' => 350,
                'language' => [
                    'search' => 'Cari:',
                    'searchPlaceholder' => 'Cari judul atau isi quotes...',
                    'lengthMenu' => 'Tampilkan _MENU_ baris',
                    'zeroRecords' => 'Quotes tidak ditemukan.',
                    'info' => 'Menampilkan _START_ sampai _END_ dari _TOTAL_ quotes',
                    'infoEmpty' => 'Tidak ada quotes',
                    'infoFiltered' => '(disaring dari _MAX_ quotes)',
                    'emptyTable' => 'Belum ada quotes',
                    'loadingRecords' => 'Memuat...',
                    'processing' => 'Memproses...',
                    'aria' => [
                        'sortAscending' => ': urutkan naik',
                        'sortDescending' => ': urutkan turun',
                    ],
                ],
            ]);
    }

    public function columns(): array
    {
        return [
            Column::make('id_quotes')
                ->title('ID')
                ->visible(false),

            Column::make('judul_quotes')
                ->title('Judul'),

            Column::make('category_quotes')
                ->title('Kategori')
                ->searchable()
                ->orderable(),

            Column::make('isi_quotes')
                ->title('Isi Quotes')
                ->orderable(false),

            Column::make('author_name')
                ->title('Pembuat')
                ->searchable(false)
                ->orderable(false),

            Column::make('status')
                ->title('Status')
                ->searchable(false)
                ->orderable(false),

            Column::make('created_at')
                ->title('Dibuat'),

            Column::computed('action')
                ->title('Aksi')
                ->searchable(false)
                ->orderable(false)
                ->exportable(false)
                ->printable(false)
                ->addClass('dt-actions'),
        ];
    }

    protected function query(): QueryBuilder
    {
        return Quote::query()
            ->with('pembuat')
            ->select([
                'quotes.id_quotes',
                'quotes.id_pembuat',
                'quotes.judul_quotes',
                'quotes.category_quotes',
                'quotes.isi_quotes',
                'quotes.is_daily',
                'quotes.tampil_di_login',
                'quotes.urutan_login',
                'quotes.created_at',
                'quotes.updated_at',
            ]);
    }

    protected function categoryBadge(Quote $quote): string
    {
        return '<span class="inline-flex items-center gap-1.5 rounded-full bg-surface-container-high px-2.5 py-1 text-xs font-semibold text-primary whitespace-nowrap">'
            .'<span class="material-symbols-outlined text-base leading-none">'.e($quote->category_icon).'</span>'
            .e($quote->category_label)
            .'</span>';
    }

    protected function statusBadges(Quote $quote): string
    {
        $badges = '';

        if ($quote->is_daily) {
            $badges .= '<span class="inline-flex items-center gap-1 rounded-full bg-primary px-2.5 py-1 text-xs font-bold text-on-primary whitespace-nowrap">'
                .'<span class="material-symbols-outlined text-base leading-none">today</span>Hari Ini</span> ';
        }

        if ($quote->tampil_di_login) {
            $badges .= '<span class="inline-flex items-center gap-1 rounded-full bg-secondary-container px-2.5 py-1 text-xs font-bold text-on-secondary-container whitespace-nowrap">'
                .'<span class="material-symbols-outlined text-base leading-none">login</span>Login #'.e($quote->urutan_login).'</span>';
        }

        return $badges !== '' ? $badges : '<span class="text-xs text-outline">&mdash;</span>';
    }

    protected function actionButtons(Quote $quote): string
    {
        $html = '';

        if (Gate::allows('setDaily', Quote::class)) {
            $html .= $quote->is_daily
                ? $this->unsetDailyForm($quote)
                : $this->setDailyForm($quote);
        }

        if (Gate::allows('update', $quote)) {
            $html .= '<a href="'.route($this->routes['edit'], $quote).'"'
                .' class="inline-flex items-center gap-1.5 rounded-lg bg-surface-container px-2.5 py-1.5 text-xs font-semibold text-on-surface transition-colors hover:bg-surface-container-high">'
                .'<span class="material-symbols-outlined text-base leading-none">edit</span>Edit</a> ';
        }

        if (Gate::allows('delete', $quote)) {
            $html .= '<form method="POST" action="'.route($this->routes['destroy'], $quote).'" class="inline"'
                .' data-swal-confirm="Hapus quotes ini?"'
                .' data-swal-text="Quotes yang sudah dihapus tidak bisa dikembalikan."'
                .' data-swal-confirm-text="Ya, hapus" data-swal-danger="true">'
                .csrf_field()
                .method_field('DELETE')
                .'<button type="submit"'
                .' class="inline-flex items-center gap-1.5 rounded-lg bg-error-container px-2.5 py-1.5 text-xs font-semibold text-on-error-container transition-colors hover:bg-error hover:text-on-error">'
                .'<span class="material-symbols-outlined text-base leading-none">delete</span>Hapus</button>'
                .'</form>';
        }

        return trim($html);
    }

    protected function setDailyForm(Quote $quote): string
    {
        return '<form method="POST" action="'.route($this->routes['daily'], $quote).'" class="inline">'
            .csrf_field()
            .'<button type="submit"'
            .' class="inline-flex items-center gap-1.5 rounded-lg bg-primary px-2.5 py-1.5 text-xs font-semibold text-on-primary shadow-xs transition-colors hover:bg-primary-container hover:text-on-primary-container">'
            .'<span class="material-symbols-outlined text-base leading-none">today</span>QOTD</button>'
            .'</form> ';
    }

    protected function unsetDailyForm(Quote $quote): string
    {
        return '<form method="POST" action="'.route($this->routes['update'], $quote).'" class="inline">'
            .csrf_field()
            .method_field('PUT')
            .'<input type="hidden" name="judul_quotes" value="'.e($quote->judul_quotes).'">'
            .'<input type="hidden" name="category_quotes" value="'.e($quote->category_quotes).'">'
            .'<input type="hidden" name="isi_quotes" value="'.e($quote->isi_quotes).'">'
            .'<button type="submit"'
            .' class="inline-flex items-center gap-1.5 rounded-lg bg-surface-container px-2.5 py-1.5 text-xs font-semibold text-primary transition-colors hover:bg-surface-container-high">'
            .'<span class="material-symbols-outlined text-base leading-none">event_busy</span>Batalkan</button>'
            .'</form> ';
    }
}

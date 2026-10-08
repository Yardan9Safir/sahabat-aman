<?php

namespace App\DataTables;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;

class UserDataTable
{
    /**
     * Konfigurasi DataTables untuk sisi server (JSON).
     */
    public function make(): EloquentDataTable
    {
        return DataTables::eloquent($this->query())
            ->setRowId('id')
            ->editColumn('identifier', fn ($user) => $user->identifier ?: '—')
            ->editColumn('kelas', fn ($user) => $this->kelasCell($user))
            ->editColumn('role', fn ($user) => $this->roleBadge($user))
            ->editColumn('created_at', fn ($user) => $user->created_at?->diffForHumans() ?? '-')
            ->addColumn('action', fn ($user) => $this->actionButtons($user))
            ->rawColumns(['kelas', 'role', 'action'])
            ->filter(function ($query) {
                $q = trim((string) request()->query('q'));

                if ($q !== '') {
                    $query->where(function ($query) use ($q) {
                        $query->where('name', 'like', "%{$q}%")
                            ->orWhere('email', 'like', "%{$q}%")
                            ->orWhere('identifier', 'like', "%{$q}%");
                    });
                }

                $role = request()->query('role');

                if (in_array($role, User::ROLES, true)) {
                    $query->where('role', $role);
                }
            }, true);
    }

    /**
     * Konfigurasi DataTables untuk sisi klien (pencarian, paginasi).
     */
    public function html(): HtmlBuilder
    {
        return app('datatables.html')
            ->setTableId('users-table')
            ->columns($this->columns())
            ->serverSide()
            ->processing()
            ->paging()
            ->searching()
            ->info()
            ->ordering()
            ->autoWidth(false)
            ->lengthChange(false)
            ->orderBy(0, 'asc')
            ->ajax([
                'url' => route('admin.users.data'),
                'type' => 'GET',
                'data' => [
                    'q' => request()->string('q')->toString(),
                    'role' => request()->string('role')->toString(),
                ],
            ])
            ->parameters([
                'dom' => 'rt<"dt-footer flex flex-wrap items-center justify-between gap-4 mt-4"ip>',
                'pageLength' => 15,
                'searchDelay' => 350,
                'language' => [
                    'search' => 'Cari:',
                    'searchPlaceholder' => 'Cari nama, email, atau NIS/NIP...',
                    'lengthMenu' => 'Tampilkan _MENU_ baris',
                    'zeroRecords' => 'Pengguna tidak ditemukan.',
                    'info' => 'Menampilkan _START_ sampai _END_ dari _TOTAL_ pengguna',
                    'infoEmpty' => 'Tidak ada pengguna',
                    'infoFiltered' => '(disaring dari _MAX_ pengguna)',
                    'emptyTable' => 'Belum ada akun pengguna.',
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
            Column::make('id')
                ->title('ID')
                ->visible(false),

            Column::make('name')
                ->title('Nama'),

            Column::make('email')
                ->title('Email'),

            Column::make('identifier')
                ->title('Identifier'),

            Column::make('kelas')
                ->title('Kelas')
                ->searchable(false)
                ->orderable(false),

            Column::make('role')
                ->title('Peran'),

            Column::make('created_at')
                ->title('Terdaftar'),

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
        return User::query()->select([
            'id',
            'name',
            'email',
            'identifier',
            'kelas',
            'role',
            'created_at',
        ]);
    }

    protected function kelasCell(User $user): string
    {
        if ($user->isGuru()) {
            $kelas = $user->assignedKelas();

            return $kelas
                ? e(implode(', ', $kelas))
                : '<span class="text-xs text-outline">—</span>';
        }

        return $user->kelas ? e($user->kelas) : '<span class="text-xs text-outline">—</span>';
    }

    protected function roleBadge(User $user): string
    {
        $kelas = $user->isGuru()
            ? 'bg-secondary-fixed text-on-secondary-fixed'
            : ($user->isKonselorSebaya()
                ? 'bg-tertiary-container text-on-tertiary-container'
                : ($user->isAdmin()
                    ? 'bg-primary-fixed text-on-primary-fixed'
                    : 'bg-surface-container-high text-on-surface-variant'));

        return '<span class="inline-block px-2.5 py-1 rounded-md text-xs font-bold '.$kelas.'">'.e($user->roleLabel()).'</span>';
    }

    protected function actionButtons(User $user): string
    {
        $html = '<a href="'.route('admin.users.edit', $user).'"'
            .' class="inline-flex items-center justify-center bg-surface-container text-primary hover:bg-surface-container-high w-9 h-9 rounded-xl transition-colors shadow-xs"'
            .' title="Edit Pengguna">'
            .'<span class="material-symbols-outlined text-base">edit</span></a>';

        if ($user->id !== auth()->id()) {
            $html .= ' <form method="POST" action="'.route('admin.users.destroy', $user).'" class="inline"'
                .' data-swal-confirm="Hapus pengguna '.e($user->name).'?"'
                .' data-swal-text="Akun akan dihapus permanen dari database."'
                .' data-swal-icon="warning"'
                .' data-swal-confirm-text="Ya, Hapus" data-swal-cancel-text="Batal">'
                .csrf_field()
                .method_field('DELETE')
                .'<button type="submit"'
                .' class="inline-flex items-center justify-center bg-error-container text-on-error-container hover:bg-error hover:text-on-error w-9 h-9 rounded-xl transition-colors shadow-xs"'
                .' title="Hapus Pengguna">'
                .'<span class="material-symbols-outlined text-base">delete</span></button>'
                .'</form>';
        }

        return $html;
    }
}

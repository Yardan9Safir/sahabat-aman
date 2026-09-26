<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoginDisplayController extends Controller
{
    public function index()
    {
        $quotes = Quote::query()
            ->with('pembuat')
            ->orderByDesc('tampil_di_login')
            ->orderBy('urutan_login')
            ->orderByDesc('id_quotes')
            ->get();

        return view('admin.login-display', [
            'quotes' => $quotes,
            'enabled' => Setting::enabled(Setting::LOGIN_QUOTE_ENABLED),
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'enabled' => ['sometimes', 'boolean'],
            'pilihan' => ['sometimes', 'array', 'max:'.Quote::LOGIN_DISPLAY_LIMIT],
            'pilihan.*' => ['integer', 'exists:quotes,id_quotes'],
            'urutan' => ['sometimes', 'array'],
            'urutan.*' => ['sometimes', 'integer', 'min:1', 'max:'.Quote::LOGIN_DISPLAY_LIMIT],
        ]);

        $urutan = $validated['urutan'] ?? [];

        $terpilih = collect($validated['pilihan'] ?? [])
            ->unique()
            ->sortBy(fn ($id) => sprintf('%02d%020d', (int) ($urutan[$id] ?? Quote::LOGIN_DISPLAY_LIMIT + 1), (int) $id))
            ->values();

        DB::transaction(function () use ($terpilih, $request) {
            Setting::put(Setting::LOGIN_QUOTE_ENABLED, $request->boolean('enabled') ? '1' : '0');

            Quote::query()->update(['tampil_di_login' => false, 'urutan_login' => null]);

            foreach ($terpilih as $position => $id) {
                Quote::where('id_quotes', $id)->update([
                    'tampil_di_login' => true,
                    'urutan_login' => $position + 1,
                ]);
            }
        });

        return redirect()->route('admin.login-display.index')
            ->with('success', 'Tampilan quotes di halaman login berhasil disimpan.');
    }
}

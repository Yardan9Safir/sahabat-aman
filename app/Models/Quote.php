<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Quote extends Model
{
    use HasFactory;

    public const CATEGORIES = ['motivasi', 'pribadi', 'sosial', 'belajar', 'karier'];

    public const LOGIN_DISPLAY_LIMIT = 3;

    protected $table = 'quotes';

    protected $primaryKey = 'id_quotes';

    protected $fillable = [
        'id_pembuat',
        'judul_quotes',
        'category_quotes',
        'isi_quotes',
        'is_daily',
    ];

    protected function casts(): array
    {
        return [
            'is_daily' => 'boolean',
            'tampil_di_login' => 'boolean',
            'urutan_login' => 'integer',
        ];
    }

    public function pembuat()
    {
        return $this->belongsTo(User::class, 'id_pembuat');
    }

    public function scopeDaily(Builder $query): Builder
    {
        return $query->where('is_daily', true);
    }

    public function scopeTampilDiLogin(Builder $query): Builder
    {
        return $query->where('tampil_di_login', true)->orderBy('urutan_login');
    }

    public function scopeDibuatOleh(Builder $query, User $user): Builder
    {
        return $query->where('id_pembuat', $user->id);
    }

    public function scopeKategori(Builder $query, ?string $category): Builder
    {
        return in_array($category, self::CATEGORIES, true)
            ? $query->where('category_quotes', $category)
            : $query;
    }

    public function getCategoryLabelAttribute(): string
    {
        return ucfirst($this->category_quotes);
    }

    public function getCategoryIconAttribute(): string
    {
        return match ($this->category_quotes) {
            'motivasi' => 'lightbulb',
            'pribadi' => 'favorite',
            'sosial' => 'groups',
            'belajar' => 'menu_book',
            'karier' => 'rocket_launch',
            default => 'format_quote',
        };
    }

    public static function untukLogin(): Collection
    {
        if (! Setting::enabled(Setting::LOGIN_QUOTE_ENABLED)) {
            return collect();
        }

        $terpilih = static::query()
            ->with('pembuat')
            ->tampilDiLogin()
            ->limit(self::LOGIN_DISPLAY_LIMIT)
            ->get();

        return $terpilih->isNotEmpty() ? $terpilih : static::kataKataHariIni();
    }

    public static function untukBeranda(): Collection
    {
        $terpilih = static::query()
            ->with('pembuat')
            ->tampilDiLogin()
            ->limit(self::LOGIN_DISPLAY_LIMIT)
            ->get();

        return $terpilih->isNotEmpty() ? $terpilih : static::kataKataHariIni();
    }

    protected static function kataKataHariIni(): Collection
    {
        return static::query()
            ->with('pembuat')
            ->daily()
            ->latest('id_quotes')
            ->get();
    }

    public function displayAuthor(): string
    {
        return $this->pembuat?->name ?? 'Guru BK';
    }

    public function displayAuthorRole(): string
    {
        return $this->pembuat?->roleLabel() ?? 'Guru BK';
    }

    public function displayAuthorContext(): string
    {
        $user = $this->pembuat;

        if (! $user) {
            return 'Ruang Peduli Siswa';
        }

        if ($user->isGuru()) {
            return 'Ruang Peduli Siswa';
        }

        if ($user->isKonselorSebaya()) {
            return 'Pusat Curhat Aman';
        }

        return $user->kelas ? 'Siswa Kelas '.$user->kelas : 'Siswa';
    }

    public function displayAuthorIcon(): string
    {
        return match ($this->pembuat?->role) {
            User::ROLE_GURU => 'support_agent',
            User::ROLE_KONSELOR_SEBAYA => 'forum',
            default => 'school',
        };
    }
}

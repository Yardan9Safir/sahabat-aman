<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curhat extends Model
{
    use HasFactory;

    public const KATEGORI = ['pribadi', 'sosial', 'belajar', 'karier'];

    public const KATEGORI_ICON = [
        'belajar' => 'menu_book',
        'sosial' => 'group',
        'pribadi' => 'psychology',
        'karier' => 'self_improvement',
    ];

    public const STATUS = ['terkirim', 'dilihat', 'dibalas', 'ditangani'];

    protected $table = 'curhats';

    protected $fillable = [
        'siswa_id',
        'nama_pengirim',
        'penerima_id',
        'nama_penerima',
        'tujuan',
        'kategori',
        'status',
        'dilihat_at',
        'ditangani_by',
        'ditangani_at',
    ];

    protected $casts = [
        'dilihat_at' => 'datetime',
        'ditangani_at' => 'datetime',
    ];

    public function siswa()
    {
        return $this->belongsTo(User::class, 'siswa_id');
    }

    public function penerima()
    {
        return $this->belongsTo(User::class, 'penerima_id');
    }

    public function balasans()
    {
        return $this->hasMany(Balasan::class)->orderBy('created_at');
    }

    public function scopeKategori(Builder $query, ?string $kategori): Builder
    {
        return in_array($kategori, self::KATEGORI, true)
            ? $query->where('kategori', $kategori)
            : $query;
    }

    public function scopeStatus(Builder $query, ?string $status): Builder
    {
        return in_array($status, self::STATUS, true)
            ? $query->where('status', $status)
            : $query;
    }

    public function getKategoriLabelAttribute(): string
    {
        return ucfirst((string) $this->kategori);
    }

    public function getKategoriIconAttribute(): string
    {
        return self::KATEGORI_ICON[$this->kategori] ?? 'chat';
    }

    public function getPesanAttribute(): string
    {
        return $this->balasans->first()?->isi ?? '';
    }

    public function getTujuanLabelAttribute(): string
    {
        return match ($this->tujuan) {
            User::ROLE_GURU => 'Guru BK',
            default => 'Konselor Sebaya',
        };
    }

    public function getTujuanIconAttribute(): string
    {
        return match ($this->tujuan) {
            User::ROLE_GURU => 'school',
            default => 'handshake',
        };
    }

    public function setViewed(): void
    {
        if ($this->status === 'terkirim') {
            $this->status = 'dilihat';
            $this->dilihat_at = now();
            $this->save();
        }
    }

    public function setReplied(): void
    {
        if (in_array($this->status, ['terkirim', 'dilihat'], true)) {
            $this->status = 'dibalas';
            $this->save();
        }
    }
}

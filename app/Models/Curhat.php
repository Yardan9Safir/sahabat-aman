<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curhat extends Model
{
    use HasFactory;

    protected $table = 'curhats';

    protected $fillable = [
        'siswa_id',
        'nama_pengirim',
        'penerima_id',
        'tujuan',
        'nama_penerima',
        'kategori',
        'pesan',
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

    public function setHandled(User $user): void
    {
        $this->status = 'ditangani';
        $this->ditangani_by = $user->id;
        $this->ditangani_at = now();
        $this->save();
    }
}

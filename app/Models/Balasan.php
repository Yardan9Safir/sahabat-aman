<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Balasan extends Model
{
    use HasFactory;

    protected $table = 'balasans';

    protected $fillable = [
        'curhat_id',
        'pengirim_id',
        'pengirim_role',
        'isi',
    ];

    public function curhat()
    {
        return $this->belongsTo(Curhat::class);
    }

    public function pengirim()
    {
        return $this->belongsTo(User::class, 'pengirim_id');
    }

    public function getPengirimRoleLabelAttribute(): string
    {
        return match ($this->pengirim_role) {
            User::ROLE_GURU => 'Guru BK',
            User::ROLE_KONSELOR_SEBAYA => 'Konselor Sebaya',
            default => 'Siswa',
        };
    }
}

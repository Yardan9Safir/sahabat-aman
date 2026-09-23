<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    public const ROLE_SISWA = 'siswa';

    public const ROLE_GURU = 'guru';

    public const ROLE_KONSELOR_SEBAYA = 'konselor sebaya';

    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'identifier',
        'kelas',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function curhats()
    {
        return $this->hasMany(Curhat::class, 'siswa_id');
    }

    public function balasans()
    {
        return $this->hasMany(Balasan::class, 'pengirim_id');
    }

    public function roleLabel(): string
    {
        return match ($this->role) {
            self::ROLE_GURU => 'Guru BK',
            self::ROLE_KONSELOR_SEBAYA => 'Konselor Sebaya',
            default => ucfirst($this->role),
        };
    }

    public function isGuru(): bool
    {
        return $this->role === self::ROLE_GURU;
    }

    public function isKonselorSebaya(): bool
    {
        return $this->role === self::ROLE_KONSELOR_SEBAYA;
    }
}

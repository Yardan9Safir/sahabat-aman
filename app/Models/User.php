<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'identifier',
        'kelas',
        'anon_label',
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

    public function displayName(): string
    {
        if ($this->role === 'konselor') {
            return $this->anon_label
                ? "Konselor Sebaya {$this->anon_label}"
                : 'Konselor Sebaya';
        }

        return $this->name;
    }
}

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

    public const ROLE_ADMIN = 'admin';

    public const ROLES = [
        self::ROLE_SISWA,
        self::ROLE_GURU,
        self::ROLE_KONSELOR_SEBAYA,
        self::ROLE_ADMIN,
    ];

    /** Seluruh kelas yang berlaku di sekolah. */
    public const ALL_KELAS = [
        '7A', '7B', '7C', '7D', '7E', '7F', '7G', '7H', '7I', '7J',
        '8A', '8B', '8C', '8D', '8E', '8F', '8G', '8H', '8I', '8J',
        '9A', '9B', '9C', '9D', '9E', '9F', '9G', '9H', '9I', '9J', '9K',
    ];

    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'identifier',
        'kelas',
        'guru_kelas',
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
            'guru_kelas' => 'array',
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

    public function quotes()
    {
        return $this->hasMany(Quote::class, 'id_pembuat');
    }

    public function roleLabel(): string
    {
        return match ($this->role) {
            self::ROLE_GURU => 'Guru BK',
            self::ROLE_KONSELOR_SEBAYA => 'Konselor Sebaya',
            self::ROLE_ADMIN => 'Administrator',
            default => ucfirst($this->role),
        };
    }

    public function isSiswa(): bool
    {
        return $this->role === self::ROLE_SISWA;
    }

    public function isGuru(): bool
    {
        return $this->role === self::ROLE_GURU;
    }

    public function isKonselorSebaya(): bool
    {
        return $this->role === self::ROLE_KONSELOR_SEBAYA;
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    /**
     * Aturan konselor sebaya: maksimal 10 akun (KonselorA–KonselorJ).
     * Kembalikan pesan error, atau null jika lolos.
     */
    public static function validasiKonselor(?int $kecualiId = null): ?string
    {
        $jumlah = self::where('role', self::ROLE_KONSELOR_SEBAYA)
            ->when($kecualiId, fn ($query) => $query->where('id', '!=', $kecualiId))
            ->count();

        return $jumlah >= 10 ? 'KONSELOR SEBAYA SUDAH PENUH' : null;
    }

    /**
     * Aturan kelas guru: satu kelas hanya boleh diampu satu guru (tidak boleh
     * double). Kembalikan pesan error, atau null jika lolos.
     */
    public static function validasiGuruKelas(array $kelas, ?int $kecualiId = null): ?string
    {
        $terpakai = self::where('role', self::ROLE_GURU)
            ->when($kecualiId, fn ($query) => $query->where('id', '!=', $kecualiId))
            ->get()
            ->flatMap(fn (self $guru) => $guru->assignedKelas())
            ->intersect(array_values($kelas))
            ->values();

        return $terpakai->isEmpty()
            ? null
            : 'Kelas '.implode(', ', $terpakai).' sudah diampu guru lain.';
    }

    /**
     * boleh memilih kata-kata hari ini (quote of the day) — hanya guru.
     */
    public function canSetDailyQuote(): bool
    {
        return $this->isGuru();
    }

    /**
     * Daftar kelas yang diampu guru ini.
     *
     * @return array<int, string>
     */
    public function assignedKelas(): array
    {
        return array_values(array_filter($this->guru_kelas ?? []));
    }

    /**
     * Apakah guru BK ini menangani kelas tertentu? (konselor sebaya tidak
     * terikat kelas — semua siswa bisa memilih semua konselor sebaya).
     */
    public function handlesKelas(?string $kelas): bool
    {
        if ($kelas === null || $kelas === '') {
            return false;
        }

        return $this->isGuru() && in_array($kelas, $this->assignedKelas(), true);
    }
}

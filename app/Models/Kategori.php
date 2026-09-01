<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kategori extends Model
{
    use HasFactory;

    protected $table = 'kategori';

    protected $fillable = [
        'nama',
        'slug',
        'deskripsi',
        'icon',
    ];

    /**
     * Get all curhats for this category.
     */
    public function curhats(): HasMany
    {
        return $this->hasMany(Curhat::class);
    }
}

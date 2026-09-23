<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    use HasFactory;

    public const CATEGORIES = ['motivasi', 'pribadi', 'sosial', 'belajar', 'karier'];

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
        ];
    }

    public function pembuat()
    {
        return $this->belongsTo(User::class, 'id_pembuat');
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
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Balasan extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'balasan';

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
}

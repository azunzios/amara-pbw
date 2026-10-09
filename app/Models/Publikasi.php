<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Publikasi extends Model
{
    // Eloquent otomatis menghubungkan model ini ke tabel "publikasis"
    protected $fillable = ['judul', 'tanggal_rilis', 'sampul', 'abstraksi'];

    protected function casts(): array
    {
        return [
            'tanggal_rilis' => 'date',
        ];
    }
}

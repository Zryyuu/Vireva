<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Biaya extends Model
{
    /** @use HasFactory<\Database\Factories\BiayaFactory> */
    use HasFactory;

    protected $fillable = [
        'item_biaya',
        'jumlah',
        'kategori',
        'tanggal',
        'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jumlah' => 'decimal:2',
    ];
}

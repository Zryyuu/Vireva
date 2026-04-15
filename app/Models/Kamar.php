<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kamar extends Model
{
    protected $table = 'kamar';

    protected $fillable = [
        'nomor_kamar',
        'tipe_kamar',
        'harga_permalam',
        'status_kamar',
        'kapasitas',
        'deskripsi',
        'foto',
        'fasilitas',
    ];

    protected $casts = [
        'harga_permalam' => 'decimal:2',
        'fasilitas' => 'array',
    ];

    public function pemesanan()
    {
        return $this->hasMany(Pemesanan::class);
    }

    public function isAvailable()
    {
        return $this->status_kamar === 'tersedia';
    }

    public function getFormattedHargaAttribute()
    {
        return 'Rp ' . number_format($this->harga_permalam, 0, ',', '.');
    }
}

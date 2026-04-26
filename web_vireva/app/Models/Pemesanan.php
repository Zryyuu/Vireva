<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pemesanan extends Model
{
    protected $table = 'pemesanan';

    protected $fillable = [
        'tamu_id',
        'villa_id',
        'petugas_id',
        'tanggal_checkin',
        'tanggal_checkout',
        'total_hari',
        'total_biaya',
        'status_pemesanan',
        'snap_token',
        'status_pembayaran',
    ];

    protected $casts = [
        'tanggal_checkin' => 'date',
        'tanggal_checkout' => 'date',
        'total_biaya' => 'decimal:2',
    ];

    public function tamu()
    {
        return $this->belongsTo(Tamu::class);
    }

    public function villa()
    {
        return $this->belongsTo(Villa::class);
    }

    public function petugas()
    {
        return $this->belongsTo(Petugas::class);
    }

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class);
    }

    public function getFormattedBiayaAttribute()
    {
        return 'Rp ' . number_format($this->total_biaya, 0, ',', '.');
    }
}

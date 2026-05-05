<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Villa extends Model
{
    protected $table = 'villas';

    protected $fillable = [
        'nama_villa',
        'tipe_villa',
        'harga_permalam',
        'jumlah_bedroom',
        'jumlah_bathroom',
        'luas_bangunan',
        'status_villa',
        'kapasitas',
        'deskripsi',
        'foto',
        'fasilitas',
    ];

    protected $casts = [
        'harga_permalam' => 'decimal:2',
        'fasilitas' => 'array',
        'foto' => 'array',
        'jumlah_bedroom' => 'integer',
        'jumlah_bathroom' => 'integer',
        'luas_bangunan' => 'integer',
    ];

    protected $appends = ['nama', 'tipe', 'harga', 'formatted_harga', 'image_url', 'all_images', 'detail'];

    public function getNamaAttribute() { return $this->nama_villa; }
    public function getTipeAttribute() { return $this->tipe_villa; }
    public function getHargaAttribute() { return $this->harga_permalam; }

    public function getImageUrlAttribute()
    {
        if (is_array($this->foto) && count($this->foto) > 0) {
            return url('storage/' . $this->foto[0]);
        }
        return $this->foto && !is_array($this->foto) ? url('storage/' . $this->foto) : null;
    }

    public function getAllImagesAttribute()
    {
        if (is_array($this->foto)) {
            return array_map(function($path) {
                return url('storage/' . $path);
            }, $this->foto);
        }
        return $this->foto ? [url('storage/' . $this->foto)] : [];
    }

    public function getDetailAttribute()
    {
        return [
            'bedroom' => $this->jumlah_bedroom,
            'bathroom' => $this->jumlah_bathroom,
            'luas' => $this->luas_bangunan,
            'deskripsi' => $this->deskripsi,
            'images' => $this->all_images,
        ];
    }

    public function pemesanan()
    {
        return $this->hasMany(Pemesanan::class, 'villa_id');
    }

    public function isAvailable()
    {
        return $this->status_villa === 'tersedia';
    }

    public function getFormattedHargaAttribute()
    {
        return 'Rp ' . number_format((float) $this->harga_permalam, 0, ',', '.');
    }
}

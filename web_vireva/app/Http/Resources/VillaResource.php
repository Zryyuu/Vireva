<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VillaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'nama' => $this->nama_villa,
            'tipe' => $this->tipe_villa,
            'harga' => (float) $this->harga_permalam,
            'formatted_harga' => $this->formatted_harga,
            'image_url' => $this->image_url,
            'all_images' => $this->all_images,
            'raw_foto' => $this->raw_foto,
            'kapasitas' => $this->kapasitas ?? 0,
            'status_villa' => $this->status_villa,
            'detail' => [
                'bedroom' => $this->jumlah_bedroom ?? 0,
                'bathroom' => $this->jumlah_bathroom ?? 0,
                'luas' => $this->luas_bangunan ?? 0,
                'deskripsi' => $this->deskripsi ?? '',
            ],
            'status' => $this->status_villa,
            'booked_dates' => $this->pemesanan()
                ->whereIn('status_pemesanan', ['menunggu', 'aktif'])
                ->get(['tanggal_checkin', 'tanggal_checkout'])
                ->map(function ($booking) {
                    return [
                        'checkin' => $booking->tanggal_checkin,
                        'checkout' => $booking->tanggal_checkout,
                    ];
                }),
        ];
    }
}

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
            'image_url' => $this->foto ? asset('storage/' . $this->foto) : null,
            'detail' => [
                'bedroom' => $this->jumlah_bedroom ?? 0,
                'bathroom' => $this->jumlah_bathroom ?? 0,
                'luas' => $this->luas_bangunan ?? 0,
                'deskripsi' => $this->deskripsi ?? '',
            ],
            'status' => $this->status_villa,
        ];
    }
}

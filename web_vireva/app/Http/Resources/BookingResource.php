<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
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
            'kode_booking' => 'VRV-' . str_pad($this->id, 5, '0', STR_PAD_LEFT),
            'villa' => new VillaResource($this->whenLoaded('villa')),
            'tanggal_checkin' => $this->tanggal_checkin->format('Y-m-d'),
            'total_hari' => $this->total_hari,
            'total_biaya' => (float) $this->total_biaya,
            'status' => $this->status_pemesanan,
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}

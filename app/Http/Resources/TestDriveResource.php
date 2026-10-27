<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TestDriveResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'nama_customer'  => $this->nama_customer,
            'email'          => $this->email,
            'no_telp'        => $this->no_telp,
            'tanggal'        => $this->tanggal?->format('Y-m-d') ?? $this->tanggal,
            'catatan'        => $this->catatan,
            'status'         => $this->status,
            'mobil'          => new MobilResource($this->whenLoaded('mobil')),
            'created_at'     => $this->created_at,
            'updated_at'     => $this->updated_at,
        ];
    }
}

<?php

namespace App\Reservations\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'name'       => $this->name,
            'email'      => $this->email,
            'phone'      => $this->phone,
            'guests'     => $this->guests,
            'date'       => $this->date,
            'time'       => $this->time,
            'notes'      => $this->notes,
            'status'     => $this->status,
            'created_at' => $this->created_at,
        ];
    }
}

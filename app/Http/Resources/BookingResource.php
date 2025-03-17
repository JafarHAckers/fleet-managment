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
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'trip' => [
                'id' => $this->trip->id,
                'name' => $this->trip->name,
            ],
            'seat' => [
                'id' => $this->seat->id,
                'seat_number' => $this->seat->seat_number,
            ],
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
            ],
            'from_city' => [
                'id' => $this->fromStation->city->id,
                'name' => $this->fromStation->city->name,
            ],
            'to_city' => [
                'id' => $this->toStation->city->id,
                'name' => $this->toStation->city->name,
            ],
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
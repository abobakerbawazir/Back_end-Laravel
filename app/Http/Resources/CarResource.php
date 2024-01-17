<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CarResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return array_merge(parent::toArray($request), ['image_car_of_brands' => $this->image_car_brands->first()->url ?? env('APP_URL') . '/storage/' . "photo_upload/cars/112.png"]);
    }
}

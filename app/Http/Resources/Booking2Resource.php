<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Booking2Resource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return array_merge(
            parent::toArray($request),
            [
                'coustomerImage' => $this->user->image ?? "http://192.168.179.98:8000/storage/photo_upload/users/user-profile-icon-front-side.jpg"
            ],
        );
    }
}

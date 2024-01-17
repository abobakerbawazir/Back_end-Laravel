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
                'coustomerImage' => $this->user->image ?? env('APP_URL') . "/storage/photo_upload/users/404.png"
            ],
        );
    }
}

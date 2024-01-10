<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResourse extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return array_merge(parent::toArray($request), ['roles' => $this->roles->first()->name, 'image' => $this->image ?? "http://192.168.179.98:8000/storage/photo_upload/users/201.jpg"]);
    }
}

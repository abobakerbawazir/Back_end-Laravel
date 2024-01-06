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
        return parent::toArray($request);
        // 'booking' => $this
        // $request['user']['image'] = 
        //return array_merge(parent::toArray($request), ['days' => $this->total ?? "http://192.168.179.98:8000/storage/photo_upload/users/100.png"]);

        //  return array_merge(parent::toArray($request), ['user' => $this->user->image ?? "http://192.168.179.98:8000/storage/photo_upload/users/100.png"]);
        // 'booking' => ,
        // 'user' => ,
        // ''

    }
}

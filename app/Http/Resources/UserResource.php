<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return array_merge(parent::toArray($request), ['cars' => $this->cars]);

        return array_merge(parent::toArray($request), ['roles' => $this->roles->first()->name, 'image' => $this->image ==  env('APP_URL') . '/storage/'  ?  env('APP_URL') . '/storage/' . "photo_upload/users/404.png" : $this->image]);
    }
}
// Booking::with('user')->whereHas('cars',function($query){ $query->where('cars.user_id',1);})->get()
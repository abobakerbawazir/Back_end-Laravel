<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransferResourse extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return array_merge( ['balance'=>$this->balance,'id'=>$this->id]);    
        //  return array_merge( ['balance'=>$this->balance,'id'=>$this->id]);    

        return parent::toArray($request);
    }
}

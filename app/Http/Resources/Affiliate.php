<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Affiliate extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'           => $this->id,
            'affiliate_id' => $this->affiliate_id,
            'latitude'     => $this->latitude,
            'longitude'    => $this->longitude,
            'created_at'   => $this->created_at,
            'updated_at'   => $this->updated_at
        ];
    }
}

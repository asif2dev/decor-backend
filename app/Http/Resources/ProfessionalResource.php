<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfessionalResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request  $request
     */
    public function toArray($request): array
    {
        return [
            'uid' => $this->resource->uid,
            'companyName' => $this->resource->company_name,
            'about' => $this->resource->about,
            'category' => new CategoryResource($this->resource->category),
            'phone1' =>  $this->resource->phone1,
            'phone2' =>  $this->resource->phone2,
            'latLng' =>  $this->resource->lat_lng,
            'fullAddress' =>  $this->resource->full_address
        ];
    }
}

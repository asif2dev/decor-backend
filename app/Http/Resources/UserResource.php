<?php

namespace App\Http\Resources;

use App\Models\Professional;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     */
    public function toArray($request): array
    {
        $name = "{$this->resource->first_name} {$this->resource->last_name}";

        return [
            'firstName' => $this->resource->first_name,
            'lastName' => $this->resource->last_name,
            'name' =>$name,
            'email' => $this->resource->email,
            'phone' => $this->resource->phone,
            'avatar' => 'https://avatars.dicebear.com/api/initials/' . $name . '.svg?background=%237952B3',
            'professionals' => new ProfessionalResourceCollection($this->resource->professionals),
            'favorites' => $this->getFavorites($this->resource)
        ];
    }

    private function getFavorites(User $user): array
    {
        return $user->favoriteProfessionals
            ->map(fn (Professional $professional) => $professional->uid)
            ->flatten()
            ->toArray();
    }
}

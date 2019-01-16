<?php

namespace Bitfumes\Multiauth\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AdminResource extends JsonResource
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
            'name'  => $this->name,
            'id'    => $this->id,
            'email' => $this->email,
            'roles' => $this->roles()->pluck('name')
        ];
    }
}

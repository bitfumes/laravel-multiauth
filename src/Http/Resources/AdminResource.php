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
        $roleResource = config('multiauth.resources.role');
        return [
            'name'   => $this->name,
            'id'     => $this->id,
            'email'  => $this->email,
            'active' => $this->active,
            'roles'  => $roleResource::collection($this->roles)
        ];
    }
}

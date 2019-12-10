<?php

namespace Bitfumes\Multiauth\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
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
            'id'             => $this->id,
            'name'           => $this->name,
            'created_at'     => $this->created_at->diffForHumans(),
            'admins_attached'=> $this->admins->count(),
            'permissions'    => $this->permissions->map(function ($permission) {
                return $permission->only('id', 'name', 'parent');
            })->groupBy('parent'),
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
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
            'id'        => $this->id,
            'title' => $this->title,
            'description'   => $this->description,
            'user_id'   => $this->user_id,
            'members'   => $this->members,
            'members_limited'   => $this->members_limited,
            'phases'    => ProjectPhaseResource::collection($this->phases),
            'owner'     => $this->owner
        ];
    }
}

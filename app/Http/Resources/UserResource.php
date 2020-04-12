<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if($request->has('not_in_project_id'))
            return [
                'id' => $this->id,
                'fullname' => $this->fullname,
            ];

        return parent::toArray($request);
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProjectPhaseResource extends JsonResource
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
            'description'   => $this->description,
            'order'         => $this->order,
            'tasks'         => $this->tasks()->select(['project_tasks.id', 'project_tasks.title', 'project_tasks.user_id', 'project_tasks.project_id',])->with('user')->limit(10)->get()
        ];
    }
}

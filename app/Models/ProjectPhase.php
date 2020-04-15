<?php

namespace App\Models;



class ProjectPhase extends BaseModel
{
    protected $fillable = [ 'description', 'order' ];

    public function tasks()
    {
        return $this->belongsToMany(ProjectTask::class,
                                    'project_task_phases',
                                    'project_phase_id',
                                    'project_task_id')
                                    ->wherePivot('expired_at', null);
    }
}

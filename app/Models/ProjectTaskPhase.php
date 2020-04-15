<?php

namespace App\Models;



class ProjectTaskPhase extends BaseModel
{
    protected $fillable = [
        'project_task_id',
        'project_phase_id',
        'user_id',
        'expired_at',
        'remarks'
    ];

    public function task()
    {
        return $this->belongsTo(ProjectTask::class);
    }


    public function phase()
    {
        return $this->belongsTo(ProjectPhase::class);
    }
}

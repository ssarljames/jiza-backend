<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectTaskPhase extends Model
{
    protected $fillable = [
        'project_task_id',
        'project_phase_id',
        'user_id',
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

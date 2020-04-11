<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectPhase extends Model
{
    protected $fillable = [ 'description', 'order' ];

    public function tasks()
    {
        return $this->belongsToMany(ProjectTask::class, 'project_task_phases', 'project_phase_id', 'project_task_id');
    }
}

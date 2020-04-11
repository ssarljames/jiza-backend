<?php

namespace App\Models;

use App\Events\ModelEvents\ProjectTask\ProjectTaskCreated;
use App\Events\ModelEvents\ProjectTask\ProjectTaskCreating;
use App\User;
use Illuminate\Database\Eloquent\Model;

class ProjectTask extends Model
{
    protected $fillable = [
        'project_id',
        'project_module_id',
        'user_id',
        'title',
        'description',

        'current_project_phase_id',

        'type',

        'done_at'
    ];

    protected $dates = [
        'done_at'
    ];

    protected $dispatchesEvents = [
        'creating' => ProjectTaskCreating::class,
        'created'  => ProjectTaskCreated::class
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function module()
    {
        return $this->belongsTo(ProjectModule::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function phases()
    {
        return $this->belongsToMany(ProjectPhase::class, 'project_task_phases');
    }

    public function task_phases()
    {
        return $this->hasMany(ProjectTaskPhase::class);
    }

    public function current_phase()
    {
        return $this->belongsTo(ProjectPhase::class, 'current_project_phase_id');
    }
}

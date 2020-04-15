<?php

namespace App\Models;



class ProjectModule extends BaseModel
{
    protected $fillable = [
        'project_id',
        'title',
        'description'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectModule extends Model
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

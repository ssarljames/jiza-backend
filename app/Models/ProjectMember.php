<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class ProjectMember extends Model
{
    protected $fillable = [
        'project_id',
        'user_id',
        'role',
        'expired_at'
    ];

    protected $dates = [
        'expired_at'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

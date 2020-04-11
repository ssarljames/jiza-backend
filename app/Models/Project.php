<?php

namespace App\Models;

use App\Events\ModelEvents\Project\ProjectCreated;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'title',
        'description',
        'user_id',
        'archived_at',
        'photo_path',
    ];

    protected $appends = [ 'members_count', 'members_limited' ];

    protected $dates = [
        'archived_at'
    ];


    protected $dispatchesEvents = [
        'created' => ProjectCreated::class
    ];


    const LIMIT = 3;

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'project_members');
    }

    public function phases()
    {
        return $this->hasMany(ProjectPhase::class)->orderBy('order');
    }

    public function tasks()
    {
        return $this->hasMany(ProjectTask::class);
    }

    public function scopeSearch(Builder $query, $q){

        if(!trim($q))
            return $query;

        return $query->where(function(Builder $query) use ($q) {
            $query->where('projects.title', 'like', "%$q%")
                    ->orWhere('projects.description', 'like', "%$q%");
        });
    }

    public function getMembersLimitedAttribute(){
        return $this->members()->where('users.id', '<>', $this->user_id)->select('users.*')->limit(self::LIMIT)->get();
    }

    public function project_members(){
        return $this->hasMany(ProjectMember::class);
    }

    public function getMembersCountAttribute(){
        return $this->project_members()->count();
    }
}

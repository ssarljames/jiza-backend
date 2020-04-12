<?php

namespace App;

use App\Events\ModelEvents\User\UserCreating;
use App\Events\ModelEvents\User\UserUpdating;
use App\Models\Project;
use App\Models\ProjectMember;
use App\Models\StationUsageLog;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens, SoftDeletes;

    protected $fillable = [
        'username',
        'password',

        'firstname',
        'lastname',

        'email',

        'reset_password',

        'verified_at',
        'deactivated_at'
    ];

    protected $dates = ['created_at',  'updated_at', 'verified_at', 'deactivated_at'];

    protected $hidden = [ 'password' ];

    protected $appends = [ 'is_administrator', 'fullname', 'role' ];



    protected $dispatchesEvents = [
        'creating' => UserCreating::class,
        'updating' => UserUpdating::class
    ];

    public static function query(){
        return parent::query()->where('id', '>', 1);
    }

    public function checkPassword($password){
        return Hash::check($password, $this->password);
    }

    public function validatePassword($password){
        if($this->checkPassword($password) == false)
            throw ValidationException::withMessages([
                'password' => ['User password is incorrect.', $password],
            ]);
    }

    public function getIsAdministratorAttribute(){
        return $this->id == 1;
    }

    public function getFullnameAttribute(){
        return ucwords(strtolower($this->firstname . ' ' . $this->lastname));
    }


    public function getRoleAttribute(){
        return $this->id == 1 ? 'administrator' : 'encoder';
    }

    public function projects_worked()
    {
        return $this->belongsToMany(Project::class, 'project_members');
    }


    public function projects_owned()
    {
        return $this->hasMany(Project::class);
    }

    public function projects(){

        $owned = $this->projects_owned()
                            ->select('projects.id', 'projects.title', 'projects.description', 'projects.created_at', 'projects.user_id');

        $other_projects =$this->projects_worked()
                            ->select('projects.id', 'projects.title', 'projects.description', 'projects.created_at', 'projects.user_id');


        return $owned->union($other_projects);
    }

    public function project_members()
    {
        return $this->hasMany(ProjectMember::class);
    }
}

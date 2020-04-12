<?php

namespace App\Rules;

use App\Models\Project;
use App\User;
use Illuminate\Contracts\Validation\Rule;

class ProjectMembershipNotExist implements Rule
{
    private $project;
    private $user_id;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $this->user_id = $value;
        return $this->project->project_members()->where('user_id', $value)->count() == 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return User::find($this->user_id)->fullname . ' was already in the project.';
    }
}

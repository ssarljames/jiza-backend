<?php

namespace App\Rules;

use App\Models\Project;
use Illuminate\Contracts\Validation\Rule;

class CheckProjectMembership implements Rule
{
    private $project;

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
        return $this->project->project_members()->where('user_id', $value)->count() > 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'User is not member of this project.';
    }
}

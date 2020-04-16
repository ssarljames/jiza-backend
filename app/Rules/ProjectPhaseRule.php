<?php

namespace App\Rules;

use App\Models\BaseModel;
use App\Models\Project;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Log;

class ProjectPhaseRule implements Rule
{
    private $project;
    private $error_message;
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

        $newPhasesCount = count($value);
        $total = $this->project->phases()->count() + $newPhasesCount;

        $maxOrder = max(array_map(function($arr){
            return $arr['order'];
        }, $value));

        if($maxOrder >= $total){
            $this->error_message = 'Invalid phase order';
            return false;

        }

        $descriptions = array_map(function($arr){
            return $arr['description'];
        }, $value);

        $exist = $this->project->phases()->whereIn('description', $descriptions)->count() > 0;

        if($exist){
            $this->error_message = 'Phase already exist!';
            return false;
        }


        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->error_message;
    }
}

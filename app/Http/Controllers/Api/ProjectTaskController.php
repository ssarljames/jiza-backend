<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectTask;
use App\Rules\CheckProjectMembership;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProjectTaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function index(Project $project)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Project $project)
    {
        $rules = [
            'title' => [
                'required',
                'max:100',
                Rule::unique('project_tasks', 'title')->where('project_id', $project->id)
            ],
            'user_id' => [
                            'nullable',
                            new CheckProjectMembership($project)
            ],
            'project_module_id' => [
                            'nullable',
                            Rule::exists('project_modules', 'id')->where('project_id', $project->id)
            ],
            'description' => 'nullable',
            'current_project_phase_id' => [
                'nullable',
                Rule::exists('project_phases', 'id')->where('project_id', $project->id)
            ],
            'type' => 'required'
        ];

        $messages = [
            'title.unique'   => 'Title is already used in this project.',
            'user_id.exists' => 'Selected user is not a member of this project.'
        ];

        $data = $request->validate($rules, $messages);

        $task = $project->tasks()->create($data);

        return $this->show($project, $task->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @param  \App\Models\ProjectTask  $projectTask
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project, $id)
    {
        return ProjectTask::find($id)->load(['user', 'module', 'current_phase']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Project  $project
     * @param  \App\Models\ProjectTask  $projectTask
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Project $project, $id)
    {
        $projectTask = ProjectTask::find($id);

        $rules = [
            'title' => [
                'required',
                'max:100',
                Rule::unique('project_tasks', 'title')
                        ->where('project_id', $project->id)
                        ->ignore($projectTask->id)
            ],
            'user_id' => [
                            'nullable',
                            new CheckProjectMembership($project)
            ],
            'project_module_id' => [
                            'nullable',
                            Rule::exists('project_modules', 'id')->where('project_id', $project->id)
            ],
            'description' => 'nullable',
            'current_project_phase_id' => [
                'nullable',
                Rule::exists('project_phases', 'id')->where('project_id', $project->id)
            ],
            'type' => 'required'
        ];

        $messages = [
            'title.unique'   => 'Title is already used in this project.',
            'user_id.exists' => 'Selected user is not a member of this project.'
        ];

        $data = $request->validate($rules, $messages);

        $projectTask->update($data);

        return $this->show($project, $projectTask->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Project  $project
     * @param  \App\Models\ProjectTask  $projectTask
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project, ProjectTask $projectTask)
    {
        //
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use App\Rules\ProjectMembershipNotExist;
use App\Rules\ProjectPhaseRule;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = $request->user()->projects();

        $query->with(['owner', 'phases']);

        $query->search($request->q);

        $projects = $query->paginate();

        return $projects;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'title' => [
                            'required',
                            'max:100',
                            Rule::unique('projects')->where('user_id', $request->user()->id)
            ],
            'description' => 'max:1000'
        ];

        $data = $request->validate($rules, [
            'title.unique' => 'Project already exist in your account.'
        ]);

        $project = $request->user()->projects_owned()->create($data);

        return $project;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project)
    {
        // return $project->load(['members', 'phases.tasks.user', 'owner']);
        return new ProjectResource($project);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Project $project)
    {
        $rules = [
            'title' => [
                            'required',
                            'max:100',
                            Rule::unique('projects')->where('user_id', $request->user()->id)->ignore($project->id)
            ],
            'description' => 'max:1000',
            'new_members.*.user_id' => [
                'nullable',
                new ProjectMembershipNotExist($project)
            ],
            'new_phases' => new ProjectPhaseRule($project)
        ];

        $data = $request->validate($rules, [
            'title.unique' => 'Project already exist in your account.',
            'new_phases.*.order.min' => 'Invalid phases order number'
        ]);

        try{
            DB::beginTransaction();
            $project->update($data);

            if($request->new_members)
                foreach($request->new_members as $n){
                    $project->project_members()->create([
                        'user_id' => $n['user_id']
                    ]);
                }


            if($request->members_to_remove){
                $ids = [];
                foreach($request->members_to_remove as $n)
                    array_push($ids, $n['user_id']);

                $project->project_members()->whereIn('user_id', $ids)->delete();
            }

            if($request->new_phases){
                foreach ($request->new_phases as $phase) {

                    $project->phases()->where('order', '>=', $phase['order'])->update([
                        'order' => DB::raw('project_phases.order + 1')
                    ]);
                    $project->phases()->create($phase);
                }
            }

            DB::commit();

        }catch(Exception $e){
            DB::rollBack();

            return response()->json([
                'message' => 'Unkown error occured',
                'exception' => $e->getMessage()
            ], 500);
        }

        return $this->show($project);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
        //
    }
}

<?php

namespace App\Events\ModelEvents\ProjectTask;

use App\Models\ProjectTask;
use Exception;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProjectTaskUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(ProjectTask $task)
    {
        if($task->task_phases()->latest()->first()->project_phase_id != $task->current_project_phase_id){
            try{
                DB::beginTransaction();

                $task->task_phases()->latest()->first()->update([
                    'expired_at' => now()
                ]);

                $task->task_phases()->create([
                    'project_phase_id' => $task->current_project_phase_id,
                    'user_id' => auth()->user()->id
                ]);

                DB::commit();
            }catch(Exception $e){

                Log::error('ProjectTaskUpdated: ' . $e->getMessage());

                DB::rollback();
            }
        }
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}

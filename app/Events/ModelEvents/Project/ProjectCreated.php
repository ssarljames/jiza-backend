<?php

namespace App\Events\ModelEvents\Project;

use App\Models\Project;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProjectCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Project $project)
    {
        $project->phases()->create([
            'description' => 'Todo',
            'order'       => 1
        ]);

        $project->phases()->create([
            'description' => 'On-Progress',
            'order'       => 2
        ]);

        $project->phases()->create([
            'description' => 'Done',
            'order'       => 3
        ]);

        $project->project_members()->create([
            'user_id' => $project->user_id
        ]);
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

<?php

namespace App\Events;

use App\Models\Schedule;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ScheduleActionBroadcast implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $schedule;
    public $action;

    public function __construct(Schedule $schedule, string $action)
    {
        $this->schedule = $schedule;
        $this->action = $action;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('schedules-channel'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'ScheduleActionBroadcast';
    }
}
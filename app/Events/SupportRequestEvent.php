<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SupportRequestEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $validated;

    /**
     * Create a new event instance.
     */
    public function __construct(array $validated)
    {
        $this->validated = $validated;
        
        $this->name = $validated['name'];
        $this->email = $validated['email'];
        $this->subject = $validated['subject'];
        $this->message = $validated['message'];

    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}

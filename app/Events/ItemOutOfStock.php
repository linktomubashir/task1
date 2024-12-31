<?php

namespace App\Events;

use App\Models\Item;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ItemOutOfStock implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $item; // Item to be broadcasted

    /**
     * Create a new event instance.
     *
     * @param  Item  $item
     */
    public function __construct(Item $item)
    {
        $this->item = $item;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel
     */
    public function broadcastOn()
    {
        return new Channel('item-out-of-stock');
    }

    /**
     * Broadcast additional data.
     *
     * @return array
     */
    public function broadcastWith()
    {

        // dd($this->item);
        return [
            'item_id' => $this->item->id,
            'item_name' => $this->item->name,
            // 'status' => $this->item->status,
        ];
    }
    public function broadcastAs()
    {
        return 'item-out-of-stock';
    }

}

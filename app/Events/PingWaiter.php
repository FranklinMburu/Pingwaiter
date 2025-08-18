<?php

namespace App\Events;

use App\Models\Table;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PingWaiter implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $table;

    public $restaurantId;

    public function __construct(Table $table)
    {
        // \Log::info('PingWaiter event constructed for table: '.$table->restaurant->id);
        $this->table = $table;
        $this->restaurantId = $table->restaurant->id;
    }

    public function broadcastOn(): Channel
    {
        return new PrivateChannel('restaurant.'.$this->restaurantId);
    }

    public function broadcastWith(): array
    {
        return [
            'table_id' => $this->table->id,
            'table_code' => $this->table->table_code,
        ];
    }

    public function broadcastAs(): string
    {
        return 'ping.waiter';
    }
}

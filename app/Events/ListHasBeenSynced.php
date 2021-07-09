<?php

namespace App\Events;

use App\Http\Resources\CatalogResource;
use App\Models\Catalog;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ListHasBeenSynced implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Catalog $catalog;

    public string $queue = 'broadcasts';

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Catalog $catalog)
    {
        $this->catalog = $catalog;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('lists.forUser.' . $this->catalog->author->getKey());
    }

    public function broadcastAs(): string
    {
        return 'ListHasBeenSynced';
    }

    public function broadcastWith(): array
    {
        $catalog = $this->catalog
            ->load('integrations')
            ->loadCount('contacts');

        return (new CatalogResource($catalog))->resolve();
    }
}

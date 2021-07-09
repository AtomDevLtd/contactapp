<?php

namespace App\Events;

use App\Http\Resources\ContactResource;
use App\Models\Contact;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ContactHasBeenSynced implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Contact $contact;

    public string $queue = 'broadcasts';

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Contact $contact)
    {
        $this->contact = $contact->withoutRelations();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('contacts.forList.' . $this->contact->catalog->getKey());
    }

    public function broadcastAs(): string
    {
        return 'ContactHasBeenSynced';
    }

    public function broadcastWith(): array
    {
        $contact = $this->contact->load('integrations');

        return (new ContactResource($contact))->resolve();
    }
}

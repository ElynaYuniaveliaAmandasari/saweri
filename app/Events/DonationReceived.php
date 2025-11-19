<?php

namespace App\Events;

use App\Models\Donation;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DonationReceived implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $donation;

    /**
     * Create a new event instance.
     */
    public function __construct(Donation $donation)
    {
        $this->donation = $donation;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn()
    {
        return new PrivateChannel('donations.user.' . $this->donation->user_id);
    }

    /**
     * Data sent with the event.
     */
    public function broadcastWith(): array
    {
        return [
            'donation' => [
                'amount' => $this->donation->amount,
                'name' => $this->donation->name,
                'message' => $this->donation->message,
                'created_at' => $this->donation->created_at->toDateTimeString(),
            ],
        ];
    }

    /**
     * Broadcast name of event.
     */
    public function broadcastAs(): string
    {
        return 'donation.received';
    }
}

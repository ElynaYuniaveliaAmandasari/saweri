<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
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
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('donation.user.'.$this->donation->user_id),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'donation' => [
                'amount' => $this->donation->amount,
                'name' => $this->donation->name,
                'message' => $this->donation->message,
            ],
        ];
    }
}

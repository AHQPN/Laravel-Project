<?php

namespace App\Events;

use App\Models\Ve;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SeatBooked implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $tripId;
    public $seatNumber;
    public $status;
    public $timestamp;

    /**
     * Create a new event instance.
     * 
     * @param string $tripId
     * @param string $seatNumber
     * @param string $status
     */
    public function __construct(string $tripId, string $seatNumber, string $status)
    {
        $this->tripId = $tripId;
        $this->seatNumber = $seatNumber;
        $this->status = $status;
        $this->timestamp = now()->toIso8601String();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('trip.' . $this->tripId),
        ];
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs(): string
    {
        return 'seat.booked';
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith(): array
    {
        return [
            'trip_id' => $this->tripId,
            'seat_number' => $this->seatNumber,
            'status' => $this->status,
            'timestamp' => $this->timestamp,
        ];
    }
}

<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserLocationUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $latitude;
    public $longitude;
    public $userId;

    public function __construct($latitude, $longitude, $userId)
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->userId = $userId;
    }

    public function broadcastOn()
    {
        return ['location-updates'];
    }
}

<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class UserLocationUpdated implements ShouldBroadcast
{
    use SerializesModels;

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
        return new Channel('location-updates');
    }
}

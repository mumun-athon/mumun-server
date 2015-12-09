<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class NewIlegalReport extends Event implements ShouldBroadcast
{
    use SerializesModels;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $ktp;

    /**
     * @var string
     */
    public $description;

    /**
     * @var string
     */
    public $longitude;

    /**
     * @var string
     */
    public $latitude;

    /**
     * @var string
     */
    public $photo;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(array $playload)
    {
        $this->name = $playload['name'];
        $this->ktp = $playload['ktp'];
        $this->description = $playload['description'];
        $this->longitude = $playload['longitude'];
        $this->latitude = $playload['latitude'];
        $this->photo = $playload['photo'];
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return ['ilegal_reports'];
    }
}

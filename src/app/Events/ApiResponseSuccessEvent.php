<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;

class ApiResponseSuccessEvent extends AbstractApiResponseEvent
{
    public const NAME = 'api.response.success';
    /**
     * @var array
     */
    private $payload;
    /**
     * Create a new event instance.
     *
     * @param mixed $payload
     */

    /**
     * @param mixed $payload
     */
    public function __construct($payload)
    {
        $this->payload = $payload;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array|\Illuminate\Broadcasting\Channel
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }

    /**
     * @return mixed
     */
    public function getPayload()
    {
        return $this->payload;
    }
}

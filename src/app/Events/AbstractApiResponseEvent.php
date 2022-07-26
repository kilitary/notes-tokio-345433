<?php

namespace App\Events;

use App\Http\Responses\AbstractApiResponse;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

abstract class AbstractApiResponseEvent
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * @var AbstractApiResponse
     */
    protected $response;

    /**
     * Create a new event instance.
     */
    public function __construct()
    {
    }

    /**
     * @return AbstractApiResponse
     */
    public function getResponse(): AbstractApiResponse
    {
        return $this->response;
    }

    /**
     * @param AbstractApiResponse $response
     *
     * @return $this
     */
    public function setResponse(AbstractApiResponse $response)
    {
        $this->response = $response;

        return $this;
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
}

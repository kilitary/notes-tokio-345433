<?php

namespace App\Events;

use App\Exceptions\ApiException;
use Illuminate\Broadcasting\PrivateChannel;

class ApiResponseErrorEvent extends AbstractApiResponseEvent
{
    public const NAME = 'api.response.error';

    /**
     * @var ApiException
     */
    private $exception;

    /**
     * @param ApiException $exception
     */
    public function __construct(ApiException $exception)
    {
        $this->exception = $exception;
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
     * @return ApiException
     */
    public function getException(): ApiException
    {
        return $this->exception;
    }
}

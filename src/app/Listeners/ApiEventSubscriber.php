<?php

namespace App\Listeners;

use App\Events\ApiResponseErrorEvent;
use App\Events\ApiResponseSuccessEvent;
use App\Exceptions\ApiException;
use App\Http\Responses\ApiResponseError;
use App\Http\Responses\ApiResponseSuccess;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Ramsey\Uuid\Uuid;
use function Psy\debug;

class ApiEventSubscriber
{
    /**
     * Handle user login events.
     * @param $event
     * @return ApiResponseSuccessEvent
     */
    public function handleApiResponseSuccess(ApiResponseSuccessEvent $event) {
        Log::debug('Generating the API response');
        $responseTime =  \round(\microtime(true) - request()->server->get('REQUEST_TIME'), 2)*100;
        $responseUuid = Uuid::uuid4()->toString();
        $payload = $event->getPayload();
        $apiResponse = new ApiResponseSuccess();
        $apiResponse->setId($responseUuid);
        $apiResponse->setCode(JsonResponse::HTTP_OK);
        $apiResponse->setMessage('success');
        $apiResponse->setData($payload);
        $apiResponse->setVersion(request()->header('x-app-version'));
        $apiResponse->setResponseTime((float) $responseTime);
        $event->setResponse($apiResponse);
        Log::debug('API response generated successfully');
        return $event;
    }

    /**
     * Handle user logout events.
     * @param $event
     * @return ApiResponseErrorEvent
     */
    public function handleApiResponseError(ApiResponseErrorEvent $event) {
        Log::debug('Generating the API exception response');

        $responseTime =  \round((\microtime(true) - request()->server->get('REQUEST_TIME')), 2)*100;

        /**
         * @var ApiException $exception
         */
        $exception = $event->getException();

        $responseUuid = Uuid::uuid4()->toString();

        /** @var ApiResponseError $response */
        $apiResponse = new ApiResponseError();
        $apiResponse->setId($responseUuid);
        $apiResponse->setCode($exception->getStatusCode());
        $apiResponse->setMessage($exception->getMessage());
        $apiResponse->setErrors($exception->getErrors());
        $apiResponse->setVersion(request()->header('x-app-version'));
        $apiResponse->setResponseTime((int) $responseTime);
        $event->setResponse($apiResponse);
        Log::debug('API exception response generated successfully');
        return $event;
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  \Illuminate\Events\Dispatcher  $events
     * @return void
     */
    public function subscribe($events)
    {
        $events->listen(
            'App\Events\ApiResponseSuccessEvent',
            [ApiEventSubscriber::class, 'handleApiResponseSuccess']
        );

        $events->listen(
            'App\Events\ApiResponseErrorEvent',
            [ApiEventSubscriber::class, 'handleApiResponseError']
        );
    }
}

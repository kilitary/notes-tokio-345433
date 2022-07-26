<?php

namespace App\Services;

use App\Errors;
use App\Events\ApiResponseErrorEvent;
use App\Events\ApiResponseSuccessEvent;
use App\Exceptions\ApiException;
use App\Exceptions\ValidationException;
use App\Http\Responses\ApiResponse;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractApiService
{
    /**
     * @param $responseData
     *
     * @return ApiResponse
     */
    protected function handleSuccess($responseData)
    {
        // Dispatch event and return the response
        $event = event(new ApiResponseSuccessEvent($responseData));
        return $event[0]->getResponse();
    }

    /**
     * @param ApiException $e
     *
     * @return ApiResponse
     */
    protected function handleApiException(ApiException $e)
    {
        if ($e->getErrors()) {
            $serializedData = $e->getErrors();
        } else {
            $serializedData = [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ];
        }
        Log::critical('API Exception', $serializedData);

        $apiException = new ApiException(
            Response::HTTP_INTERNAL_SERVER_ERROR,
            \sprintf(
                'Internal server error. [Err: %s]',
                Errors::NOTES_INTERNAL_SERVER_ERROR
            ),
            $serializedData,
            $e
        );
        $apiException = new ApiException(
            $e->getStatusCode(),
            $e->getMessage(),
            $serializedData,
            $e
        );
        $event = event(new ApiResponseErrorEvent($apiException));

        return $event[0]->getResponse();
    }

    /**
     * @param ApiException $e
     *
     * @return ApiResponse
     */
    protected function handleValidationException(ValidationException $e)
    {
        if ($e->getErrors()) {
            $serializedData = $e->getErrors();
        } else {
            $serializedData = [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ];
        }
        Log::critical('Api Exception', $serializedData);

        $apiException = new ApiException(
            Response::HTTP_INTERNAL_SERVER_ERROR,
            \sprintf(
                'Validation Exception. [Err: %s]',
                Errors::NOTES_INTERNAL_SERVER_ERROR
            ),
            $serializedData,
            $e
        );
        $apiException = new ApiException(
            $e->getCode(),
            $e->getMessage(),
            $serializedData,
            $e
        );
        $event = event(new ApiResponseErrorEvent($apiException));

        return $event[0]->getResponse();
    }

    /**
     * @param \Exception $e
     *
     * @return ApiResponse
     */
    protected function handleException(\Exception $e)
    {
        $serializedData = [
            ['exception' => ['message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(), ],
            ],
        ];
        Log::critical('API Exception', $serializedData);
        /** @var ApiException $apiException */
        $apiException = new ApiException(
            Response::HTTP_INTERNAL_SERVER_ERROR,
            \sprintf(
                'Internal server error. [Err: %s]',
                Errors::NOTES_INTERNAL_SERVER_ERROR
            ),
            $serializedData,
            $e
        );

        $event = event(new ApiResponseErrorEvent($apiException));

        return $event[0]->getResponse();
    }
}

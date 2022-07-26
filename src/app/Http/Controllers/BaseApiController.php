<?php

namespace App\Http\Controllers;

use App\Errors;
use App\Events\ApiResponseErrorEvent;
use App\Events\ApiResponseSuccessEvent;
use App\Exceptions\ApiException;
use App\Http\Responses\ApiResponse;
use Dropelikeit\LaravelJmsSerializer\ResponseFactory;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use JMS\Serializer\SerializationContext;
use Symfony\Component\HttpFoundation\Response;

class BaseApiController extends Controller
{
    /**
     * @var ResponseFactory
     */
    private $responseFactory;

    public function __construct(ResponseFactory $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

    public function render(ApiResponse $response, $groups = [])
    {
        if (!empty($groups)) {
            $context = SerializationContext::create()->setSerializeNull(false);
            $context->setVersion(request()->headers->get('x-app-version',1));
            $context->setGroups($groups);
            $this->responseFactory->withContext($context);
        }

        $responseCode = $response->getCode();
        $jmsResponse = $this->responseFactory->create($response);
        return new JsonResponse(\json_decode($jmsResponse->getContent()), $responseCode);
    }
    /**
     * @param ApiResponse $response
     * @param null        $responseCode
     * @param array       $groups
     *
     * @return JsonResponse
     */
    public function renderResponse(ApiResponse $response, $responseCode = null, $groups = [])
    {
        if (!empty($groups)) {
            $context = SerializationContext::create()->setSerializeNull(false);
            $context->setVersion(request()->headers->get('x-app-version',1));
            $context->setGroups($groups);
            $this->responseFactory->withContext($context);
        }

        if (!\is_null($responseCode)) {
            $response->setCode($responseCode);
        } else {
            $responseCode = $response->getCode();
        }

        $jmsResponse = $this->responseFactory->create($response);
        $jmsResponse->setStatusCode($responseCode);
        return new JsonResponse($jmsResponse->send(),$responseCode);
    }

    /**
     * @param $responseData
     *
     * @return JsonResponse
     */
    public function handleSuccess($responseData)
    {
        // Dispatch event and return the response
        $event = event(new ApiResponseSuccessEvent($responseData));
        return response()->json($event[0]->getResponse(), JsonResponse::HTTP_OK);
    }

    /**
     * @param ApiException $e
     *
     * @return JsonResponse
     */
    public function handleApiException(ApiException $e)
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

        return response()->json($event[0]->getResponse(), $e->getStatusCode());
    }

    public function handleException(Exception $e)
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

        return response()->json($event[0]->getResponse(), JsonResponse::HTTP_CREATED);
    }
}

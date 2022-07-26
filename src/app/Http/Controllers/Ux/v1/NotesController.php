<?php

namespace App\Http\Controllers\Ux\v1;

use App\Http\Controllers\BaseApiController;
use App\Http\Responses\ApiResponse;
use App\Services\Ux\NotesService;
use Dropelikeit\LaravelJmsSerializer\ResponseFactory;
//use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotesController extends UxController
{
    use DispatchesJobs, ValidatesRequests;

    public function __construct(ResponseFactory $responseFactory)
    {
        //$this->middleware('auth:ux');
        parent::__construct($responseFactory);
    }

    /**
     * List
     * @param Request $request
     * @param NotesService $notesService
     * @return JsonResponse
     */
    public function list(Request $request, NotesService $notesService)
    { 
        try {
            /**
             * @var ApiResponse $response
             */
            $response = $notesService->list($request);
            return $this->render($response,  ['api']);
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * get by id
     * @param Request $request
     * @param NotesService $notesService
     * @return JsonResponse
     */
    public function getone(Request $request, NotesService $notesService)
    {
        try {
            $response = $notesService->getone($request);
            return $this->render($response, ['api']);
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Create new 
     * @param Request $request
     * @param NotesService $notesService
     * @return JsonResponse
     */
    public function create(Request $request, NotesService $notesService)
    {
        try {
            $response = $notesService->create($request);
            return $this->render($response, ['api']);
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Update by id
     * @param Request $request
     * @param NotesService $notesService
     * @return JsonResponse
     */
    public function update(Request $request, NotesService $notesService)
    {
        try {
            $response = $notesService->update($request);
            return $this->render($response, ['api']);
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Delete by id
     * @param Request $request
     * @param NotesService $notesService
     * @return JsonResponse
     */
    public function delete(Request $request, NotesService $notesService)
    {
        try {
            $response = $notesService->delete($request);
            return $this->render($response,  ['api']);
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }
}

<?php

namespace App\Services\Ux;

use App\Exceptions\ApiException;
use App\Http\Responses\ApiResponse;
use App\Models\Notes;
use App\Services\AbstractApiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class NotesService extends AbstractApiService
{
    /**
     * @param Request $request
     *
     * @return ApiResponse
     */
    public function list(Request $request)
    {
        try {
            $data = Notes::all()->sortBy('id');
            return $this->handleSuccess($data->getIterator());
        } catch (ApiException $e) {
            return $this->handleApiException($e);
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * @param Request $request
     *
     * @return ApiResponse
     */
    public function getone(Request $request)
    {
        try {
            if (isset($request->id) && \is_numeric($request->id)) {
                $rec = Notes::where('id', $request->id)->first();
            }
            return $this->handleSuccess($rec);
        } catch (ApiException $e) {
            return $this->handleApiException($e);
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * @return ApiResponse
     */
    public function create(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:255',
                'body' => 'required',
            ]);

            if ($validator->fails()) {
                throw new ApiException(JsonResponse::HTTP_UNPROCESSABLE_ENTITY, 'Validation Exception', $validator->getMessageBag()->getMessages());
            }


            $data = [
                'name' => $request->get('name'),
                'body' => $request->get('body'),
            ];

            $note = new Notes();
            $id = $note->insertGetId($data);
            
            $rec = Notes::where('id', $id)->first();
            
            return $this->handleSuccess($rec);
        } catch (ApiException $e) {
            return $this->handleApiException($e);
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * @return ApiResponse
     */
    public function update(Request $request)
    {
        try {
            
            $data=[];

            $validator = Validator::make($request->all(), [
                'name' => 'required|max:255',
                'body' => 'required',
            ]);

            if ($validator->fails()) {
                throw new ApiException(JsonResponse::HTTP_UNPROCESSABLE_ENTITY, 'Validation Exception', $validator->getMessageBag()->getMessages());
            }

            if (isset($request->id) && \is_numeric($request->id)) {


                $rec = Notes::where('id', $request->id)->first();

                if (null !== $rec) {
                    // update
                    $data = [
                        'name' => $request->get('name'),
                        'body' => $request->get('body'),
                    ];

                    $rec->where('id',$request->id)->update($data);
                    $id = $request->id;

                    $data = Notes::where('id', $id)->first();
                } else {
                    throw new ApiException(JsonResponse::HTTP_FORBIDDEN, 'invalid id');
                }
            }


            return $this->handleSuccess($data);
        } catch (ApiException $e) {
            return $this->handleApiException($e);
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    public function delete(Request $request)
    {
        try {
            if (isset($request->id) && \is_numeric($request->id)) {
                $rec = Notes::where('id', $request->id)->first();

                if (null !== $rec) {
                    $rec->delete();
                } else {
                    throw new ApiException(JsonResponse::HTTP_FORBIDDEN, 'invalid id');
                }

            }

            return $this->handleSuccess([]);
        } catch (ApiException $e) {
            return $this->handleApiException($e);
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

}

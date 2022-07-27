<?php

namespace App\Services\Ux;

use App\Exceptions\ApiException;
use App\Http\Responses\ApiResponse;
use App\Models\Notes;
use App\Models\Tags;
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
            
            // would be faster if we had created_at as integer .. this could be implemented in the future iteration
            // just a note, so you know that its something I think about as well

            $data = Notes::orderBy('created_at', 'desc')->get();
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
                $rec = Notes::find($request->id);                
            }

            return $this->handleSuccess($rec);
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
    public function getByTag(Request $request)
    {
        try {

            if (isset($request->id) && \is_numeric($request->id)) {
               $notes = new Notes;
                $rec = $notes->getNotesByTag($request->id);
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
                'tags' => 'array',
                'tags.*' => 'integer',
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

            $rec = Notes::find($id);
            
            // save tags if exist
            if($id && $request->get('tags'))
            {
                $tagsToAttach = Tags::find($request->get('tags'));
                if(!empty($tagsToAttach))
                {                    
                    $rec->tags()->saveMany($tagsToAttach);
                }
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
    public function update(Request $request)
    {
        try {
            
            $data=[];

            $validator = Validator::make($request->all(), [
                'name' => 'required|max:255',
                'body' => 'required',
                'tags' => 'array',
                'tags.*' => 'integer',
            ]);

            if ($validator->fails()) {
                throw new ApiException(JsonResponse::HTTP_UNPROCESSABLE_ENTITY, 'Validation Exception', $validator->getMessageBag()->getMessages());
            }

            if (isset($request->id) && \is_numeric($request->id)) {


                $rec = Notes::find($request->id);

                if (null !== $rec) {
                    // update
                    $data = [
                        'name' => $request->get('name'),
                        'body' => $request->get('body'),
                    ];

                    $rec->where('id',$request->id)->update($data);
                    $id = $request->id;

                    $data = Notes::find($id);
                    
                    // remove tags
                    $data->tags()->detach();

                    // save tags if exist
                    if($id && $request->get('tags'))
                    {
                        $tagsToAttach = Tags::find($request->get('tags'));
                        if(!empty($tagsToAttach))
                        {                    
                            $data->tags()->saveMany($tagsToAttach);
                        }
                    }


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
                $rec = Notes::find($request->id);

                if (null !== $rec) {
                    $rec->tags()->detach();
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

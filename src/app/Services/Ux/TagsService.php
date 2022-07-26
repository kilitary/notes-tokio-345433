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

class TagsService extends AbstractApiService
{
    /**
     * @param Request $request
     *
     * @return ApiResponse
     */
    public function list(Request $request)
    {
        try {
            $data = Tags::all()->sortBy('id');
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
                $rec = Tags::where('id', $request->id)->first();
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
            ]);

            if ($validator->fails()) {
                throw new ApiException(JsonResponse::HTTP_UNPROCESSABLE_ENTITY, 'Validation Exception', $validator->getMessageBag()->getMessages());
            }


            $data = [
                'name' => $request->get('name'),
            ];

            $note = new Tags();
            $id = $note->insertGetId($data);
            
            $rec = Tags::where('id', $id)->first();
            
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
            ]);

            if ($validator->fails()) {
                throw new ApiException(JsonResponse::HTTP_UNPROCESSABLE_ENTITY, 'Validation Exception', $validator->getMessageBag()->getMessages());
            }

            if (isset($request->id) && \is_numeric($request->id)) {


                $rec = Tags::where('id', $request->id)->first();

                if (null !== $rec) {
                    // update
                    $data = [
                        'name' => $request->get('name'),
                    ];

                    $rec->where('id',$request->id)->update($data);
                    $id = $request->id;

                    $data = Tags::where('id', $id)->first();
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
                $rec = Tags::where('id', $request->id)->first();

                if (null !== $rec) {

                    $notes = new Notes();
                    
                    $notesByTag = $notes->getNotesByTag($request->id);
                
                    if(!empty($notesByTag))
                    {
                        foreach($notesByTag as $note)
                        {
                            $noteRecord = $notes->find($note['id']);
                            $tagsToKeep = []; 

                            if(!empty($noteRecord['tags']))
                            {
                                foreach($noteRecord['tags'] as $tags)
                                {
                                    if($request->id != $tags['id'])
                                    {
                                        $tagsToKeep[] = $tags['id'];
                                    }
                                    
                                }
                                
                            }

                            $noteRecord->tags()->sync($tagsToKeep);
                        }
                    }


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

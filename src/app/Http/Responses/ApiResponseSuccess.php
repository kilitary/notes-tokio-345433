<?php

namespace App\Http\Responses;

use JMS\Serializer\Annotation as Serializer;
use JsonSerializable;

/**
 * @Serializer\XmlRoot("response")
 * @Serializer\AccessorOrder("custom", custom={
 *     "version",
 *     "id",
 *     "code",
 *     "responseTime",
 *     "message",
 *     "data"
 * })
 *
 * @Serializer\ExclusionPolicy("none")
 */
class ApiResponseSuccess extends ApiResponse implements JsonSerializable
{

    /**
     * @Serializer\Since("1.0")
     * @Serializer\Groups({"api", "default"})
     * @Serializer\SerializedName("data")
     * @Serializer\XmlElement(cdata=false)
     */
    protected $data;

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     *
     * @return ApiResponseSuccess
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return [
            'version' => $this->version,
            'id' => $this->id,
            'code' => $this->code,
            'responseTime' => $this->responseTime,
            'message' => parent::MESSAGE_SUCCESS,
            'data' => $this->data
        ];

    }

}

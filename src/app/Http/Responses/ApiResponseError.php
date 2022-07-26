<?php

namespace App\Http\Responses;

use JsonSerializable;
use JMS\Serializer\Annotation as Serializer;


/**
 * @Serializer\XmlRoot("response")
 * @Serializer\AccessorOrder("custom", custom={
 *     "version",
 *     "id",
 *     "code",
 *     "responseTime",
 *     "message",
 *     "errors"
 * })
 */
class ApiResponseError extends ApiResponse implements JsonSerializable
{

    /**
     * @Serializer\Since("1.0")
     * @Serializer\SerializedName("errors")
     * @Serializer\Groups({"api", "default"})
     * @Serializer\XmlElement(cdata=false)
     */
    protected $errors;

    /**
     * @return mixed
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @param mixed $errors
     *
     * @return ApiResponseError
     */
    public function setErrors($errors)
    {
        $this->errors = $errors;
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
            'message' => $this->message,
            'errors' => $this->errors
        ];

    }
}

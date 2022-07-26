<?php

namespace App\Http\Responses;
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
abstract class AbstractApiResponse {

    /**
     * @Serializer\Since("1.0")
     * @Serializer\Type("string")
     * @Serializer\SerializedName("version")
     * @Serializer\XmlElement(cdata=false)
     * @Serializer\Groups({"api"})
     * @Serializer\Expose
     */
    protected $version;

    /**
     * @Serializer\Since("1.0")
     * @Serializer\Type("string")
     * @Serializer\SerializedName("id")
     * @Serializer\XmlElement(cdata=false)
     * @Serializer\Groups({"api"})
     * @Serializer\Expose
     */
    protected $id;

    /**
     * @Serializer\Since("1.0")
     * @Serializer\Type("int")
     * @Serializer\SerializedName("code")
     * @Serializer\Expose
     * @Serializer\Groups({"api"})
     */
    protected $code;

    /**
     * @Serializer\Since("1.0")
     * @Serializer\Type("float")
     * @Serializer\SerializedName("response_time")
     * @Serializer\Expose
     * @Serializer\Groups({"api"})
     */
    protected $responseTime;

    /**
     * @Serializer\Since("1.0")
     * @Serializer\Type("string")
     * @Serializer\SerializedName("message")
     * @Serializer\XmlElement(cdata=false)
     * @Serializer\Expose
     * @Serializer\Groups({"api"})
     */
    protected $message;

    /**
     * @Serializer\SkipWhenEmpty
     * @Serializer\Since("1.0")
     * @Serializer\Type("array")
     * @Serializer\SerializedName("errors")
     * @Serializer\XmlKeyValuePairs
     * @Serializer\XmlElement(cdata=false)
     * @Serializer\Groups({"api"})
     */
    protected $errors;

    /**
     * @return mixed
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param mixed $version
     *
     * @return AbstractApiResponse
     */
    public function setVersion($version)
    {
        $this->version = $version;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     *
     * @return AbstractApiResponse
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $code
     *
     * @return AbstractApiResponse
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getResponseTime()
    {
        return $this->responseTime;
    }

    /**
     * @param mixed $responseTime
     *
     * @return AbstractApiResponse
     */
    public function setResponseTime($responseTime)
    {
        $this->responseTime = $responseTime;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $message
     *
     * @return AbstractApiResponse
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @param array $errors
     *
     * @return AbstractApiResponse
     */
    public function setErrors($errors)
    {
        $this->errors = $errors;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasErrors()
    {
        return !empty($this->errors);
    }
}

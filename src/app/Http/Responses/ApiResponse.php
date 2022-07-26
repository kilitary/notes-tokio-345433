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
 */
class ApiResponse extends AbstractApiResponse implements JsonSerializable
{
    const MESSAGE_SUCCESS = 'success';
    const MESSAGE_ERROR = 'error';
    /**
     * @Serializer\Since("1.0")
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
     * @return ApiResponse
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
        // TODO: Implement jsonSerialize() method.
    }
}

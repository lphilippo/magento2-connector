<?php

namespace LPhilippo\Magento2Connector\Response;

use Psr\Http\Message\ResponseInterface;

abstract class AdapterResponse
{
    /**
     * @var array|null
     */
    protected $content;

    /**
     * @var int
     */
    protected $statusCode;

    /**
     * @param ResponseInterface $response
     */
    public function __construct(ResponseInterface $response = null)
    {
        if (!$response) {
            return;
        }

        $this->statusCode = $response->getStatusCode();
    }

    /**
     * Return the HTTP Status Code of the response.
     *
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Return the content of the response.
     *
     * @return array|string|null
     */
    public function getContent()
    {
        return $this->content;
    }
}

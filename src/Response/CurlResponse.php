<?php

namespace LPhilippo\Magento2Connector\Response;

use Psr\Http\Message\ResponseInterface;

class CurlResponse extends AdapterResponse
{
    /**
     * @param ResponseInterface $response
     */
    public function __construct(ResponseInterface $response)
    {
        parent::__construct($response);

        $content = mb_convert_encoding($response->getBody()->getContents(), 'UTF-8');

        $this->content = json_decode($content, true);
    }
}

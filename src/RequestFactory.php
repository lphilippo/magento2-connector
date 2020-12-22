<?php

namespace LPhilippo\Magento2Connector;

use LPhilippo\Magento2Connector\Adapter\CurlAdapter;

class RequestFactory
{
    /**
     * @param string $uri
     * @param array $query
     *
     * @return Request
     */
    public static function make(string $uri, array $query)
    {
        $request = new Request();

        $request
            ->addHeaders([
                'Content-Type' => 'application/json',
            ])
            ->setMethod(CurlAdapter::METHOD_GET)
            ->setQuery($query)
            ->setUri($uri);

        return $request;
    }

    /**
     * @param string $uri
     * @param array $data
     *
     * @return Request
     */
    public static function makeForPost(string $uri, array $data)
    {
        $request = new Request();

        $request
            ->addHeaders([
                'Content-Type' => 'application/json',
            ])
            ->setMethod(CurlAdapter::METHOD_POST)
            ->setPayload($data)
            ->setUri($uri);

        return $request;
    }
}

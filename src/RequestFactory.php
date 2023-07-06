<?php

namespace LPhilippo\Magento2Connector;

use LPhilippo\Magento2Connector\Adapter\CurlAdapter;

class RequestFactory
{
    /**
     * @param string $uri
     * @param array $query
     * @param bool $isBulkRequest
     *
     * @return Request
     */
    public static function make(string $uri, array $query, bool $isBulkRequest = false)
    {
        $request = $isBulkRequest ? new BulkRequest() : new Request();

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
     * @param bool $isBulkRequest
     *
     * @return Request
     */
    public static function makeForPost(string $uri, array $data, bool $isBulkRequest = false)
    {
        $request = $isBulkRequest ? new BulkRequest() : new Request();

        $request
            ->addHeaders([
                'Content-Type' => 'application/json',
            ])
            ->setMethod(CurlAdapter::METHOD_POST)
            ->setPayload($data)
            ->setUri($uri);

        return $request;
    }

    /**
     * @param string $uri
     * @param array $data
     * @param bool $isBulkRequest
     *
     * @return Request
     */
    public static function makeForPut(string $uri, array $data, bool $isBulkRequest = false)
    {
        $request = $isBulkRequest ? new BulkRequest() : new Request();

        $request
            ->addHeaders([
                'Content-Type' => 'application/json',
            ])
            ->setMethod(CurlAdapter::METHOD_PUT)
            ->setPayload($data)
            ->setUri($uri);

        return $request;
    }
}

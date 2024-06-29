<?php

namespace LPhilippo\Magento2Connector;

use GuzzleHttp\Client;

class ResourceClient
{
    /**
     * @var Client
     */
    private static $instance;

    /**
     * Return and/or create a Client to allow all calls to be done simultaneously.
     *
     * @return Client
     */
    public static function get(): Client
    {
        if (! self::$instance) {
            return self::$instance = new Client();
        }

        return self::$instance;
    }
}

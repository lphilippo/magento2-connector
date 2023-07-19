<?php

namespace LPhilippo\Magento2Connector;

use LPhilippo\Magento2Connector\Adapter\CurlAdapter;

class ClientFactory
{
    /**
     * @param mixed $options
     *
     * @return Client
     */
    public static function make($options)
    {
        $client = new Client(new CurlAdapter($options));

        return $client->authenticate(
            $options['username'],
            $options['password'],
            array_key_exists('google_secret', $options) ? $options['google_secret'] : null
        );
    }
}

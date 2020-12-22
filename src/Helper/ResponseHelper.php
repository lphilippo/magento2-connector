<?php

namespace LPhilippo\Magento2Connector\Helper;

class ResponseHelper
{
    /**
     * @param array $content
     *
     * @return array
     */
    public static function getFirstItemOrNull(array $content)
    {
        if (isset($content['items']) && is_array($content['items'])) {
            return array_shift($content['items']);
        }
    }
}

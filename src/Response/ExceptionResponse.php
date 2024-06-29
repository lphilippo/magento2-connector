<?php

namespace LPhilippo\Magento2Connector\Response;

use GuzzleHttp\Exception\RequestException;
use Throwable;

class ExceptionResponse extends AdapterResponse
{
    /**
     * @param Throwable $exception
     */
    public function __construct(Throwable $exception)
    {
        if ($exception instanceof RequestException && $exception->getResponse()) {
            $response = $exception->getResponse();

            $this->content = json_decode($response->getBody()->getContents(), true);
        }

        if (! $this->content) {
            $this->content = [
                'code' => $exception->getCode(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'message' => $exception->getMessage(),
            ];
        }
    }
}

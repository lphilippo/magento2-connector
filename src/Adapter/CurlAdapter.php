<?php

namespace LPhilippo\Magento2Connector\Adapter;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\RequestOptions;
use Illuminate\Support\Arr;
use LPhilippo\Magento2Connector\Exception\AdapterException;
use LPhilippo\Magento2Connector\Request;
use LPhilippo\Magento2Connector\ResourceClient;
use LPhilippo\Magento2Connector\Response\CurlResponse;
use LPhilippo\Magento2Connector\Response\ExceptionResponse;

/**
 * Adapter implementation using cURL.
 */
class CurlAdapter
{
    public const METHOD_POST = 'post';
    public const METHOD_GET = 'get';
    public const METHOD_DELETE = 'delete';
    public const METHOD_PATCH = 'patch';
    public const METHOD_PUT = 'put';

    /**
     * @var array
     */
    protected $options = [];

    /**
     * @param array $options
     *
     * @throws AdapterException
     */
    public function __construct(array $options = [])
    {
        $this->options = $options;

        if (!array_key_exists('endpoint', $options)) {
            throw new AdapterException('Endpoint not specified');
        }
    }

    /**
     * @return int
     */
    private function getTimeoutInSeconds()
    {
        return (int) Arr::get($this->options, 'timeout_in_seconds', 10);
    }

    /**
     * @param Request $request
     *
     * @throws AdapterException
     */
    public function call(Request $request): PromiseInterface
    {
        if (!$request->getMethod()) {
            throw new AdapterException('Method not specified');
        }

        $guzzleClient = ResourceClient::get();

        return $guzzleClient->requestAsync(
            $request->getMethod(),
            $this->options['endpoint'] . $request->getUri(),
            $this->createOptions($request)
        )->then(
            function (Response $response) use ($request) {
                return new CurlResponse($response);
            },
            function (GuzzleException $guzzleException) {
                return new ExceptionResponse($guzzleException);
            }
        );
    }

    /**
     * Prepare the options for the call.
     *
     * @param AbstractRequest $request
     *
     * @return array
     */
    private function createOptions(Request $request): array
    {
        $headers = array_merge(
            [
                'Accept' => 'application/json',
                'User-Agent' => 'magento2-connector-v3/1.0',
            ],
            $request->getHeaders()
        );

        $parameters = [
            RequestOptions::CONNECT_TIMEOUT => $this->getTimeoutInSeconds(),
            RequestOptions::HEADERS => $headers,
            RequestOptions::QUERY => $request->getQuery(),
            RequestOptions::TIMEOUT => $this->getTimeoutInSeconds(),
        ];

        $requestContentType = $headers['Content-Type'] ?? null;

        if ($requestContentType === 'application/json') {
            $parameters['json'] = $request->getPayload();
        } else {
            $parameters['form_params'] = $request->getPayload();
        }

        return $parameters;
    }
}

<?php

namespace LPhilippo\Magento2Connector;

/**
 * Generic class with shared functions between the different request objects.
 */
class Request
{
    /**
     * @var array
     */
    protected $headers = [];

    /**
     * @var string
     */
    protected $method;

    /**
     * @var array
     */
    protected $payload = [];

    /**
     * @var array
     */
    protected $query = [];

    /**
     * @var array
     */
    protected $result = null;

    /**
     * @var string
     */
    protected $uri;

    /**
     * Add a set of headers.
     *
     * @param array $headers
     *
     * @return Request
     */
    public function addHeaders(array $headers): self
    {
        $this->headers = array_merge($this->headers, $headers);

        return $this;
    }

    /**
     * Return the headers.
     *
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Return the method.
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Return the payload.
     *
     * @return array
     */
    public function getPayload()
    {
        return $this->payload;
    }

    /**
     * Return the query.
     *
     * @return array
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * Return the query.
     *
     * @return array
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Return the full URL.
     *
     * @return string|null
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * Set the method.
     *
     * @param string|null $method
     *
     * @return Request
     */
    public function setMethod(string $method = null): self
    {
        $this->method = $method;

        return $this;
    }

    /**
     * Set the payload.
     *
     * @param array $payload
     *
     * @return Request
     */
    public function setPayload(array $payload): self
    {
        $this->payload = $payload;

        return $this;
    }

    /**
     * Set the query.
     *
     * @param array $query
     *
     * @return Request
     */
    public function setQuery(array $query): self
    {
        $this->query = $query;

        return $this;
    }

    /**
     * Set the result of the request.
     *
     * @param $result
     *
     * @return Request
     */
    public function setResult($result): self
    {
        $this->result = $result;

        return $this;
    }

    /**
     * Set the URI.
     *
     * @param string $uri
     *
     * @return Request
     */
    public function setUri(string $uri): self
    {
        $this->uri = $uri;

        return $this;
    }
}

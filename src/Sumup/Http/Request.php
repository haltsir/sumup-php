<?php

namespace Sumup\Api\Http;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;

class Request
{
    /**
     * @var string
     */
    protected $method = 'GET';

    /**
     * @var string
     */
    protected $uri;

    /**
     * @var array
     */
    protected $query = [];

    /**
     * @var array
     */
    protected $body = [];

    /**
     * @var Client
     */
    protected $httpClient;

    public function __construct(ClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @param string $method
     * @return $this
     * @throws \Exception
     */
    public function setMethod(string $method)
    {
        if (!in_array($method, ['GET', 'POST', 'PUT', 'DELETE'])) {
            throw new \Exception('Unsupported HTTP method.');
        }

        $this->method = $method;

        return $this;
    }

    /**
     * @return string
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * @param string $uri
     * @return $this
     * @throws \Exception
     */
    public function setUri(string $uri)
    {
        if (false === filter_var($uri, FILTER_VALIDATE_URL)) {
            throw new \Exception('Invalid URI provided.');
        }
        $this->uri = $uri;

        return $this;
    }

    /**
     * @return string
     */
    public function getQuery(): ?string
    {
        return http_build_query($this->query);
    }

    /**
     * @param string|array $query
     * @return $this
     */
    public function setQuery($query)
    {
        $this->query = $query;

        return $this;
    }

    /**
     * @return array
     */
    public function getBody(): array
    {
        return $this->body;
    }

    /**
     * @param array $body
     * @return $this
     */
    public function setBody(array $body)
    {
        $this->body = $body;

        return $this;
    }

    public function setJson(string $content)
    {
        return $this->setBody(json_decode($content, true));
    }

    /**
     * @param array $options
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function send(array $options = [])
    {
        if (in_array($this->getMethod(), ['POST', 'PUT'])) {
            $options += [
                'json' => $this->getBody(),
                'query' => $this->getQuery()
            ];
        }

        return $this->httpClient->request($this->getMethod(), $this->getUri(), $options);
    }
}

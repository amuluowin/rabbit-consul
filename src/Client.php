<?php

namespace rabbit\consul;

use Psr\Http\Message\ResponseInterface;
use rabbit\consul\Exception\ClientException;
use rabbit\consul\Exception\ServerException;

final class Client
{
    /** @var \rabbit\httpclient\Client */
    private $client;

    /**
     * Client constructor.
     * @param \rabbit\httpclient\Client $client
     */
    public function __construct(\rabbit\httpclient\Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param $name
     * @param $arguments
     * @return ConsulResponse
     */
    public function __call($name, $arguments)
    {
        /** @var ResponseInterface $response */
        $response = call_user_func_array([$this->client, $name], $arguments);
        return new ConsulResponse($response->getHeaders(), (string)$response->getBody(), $response->getStatusCode());
    }
}

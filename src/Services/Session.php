<?php

namespace rabbit\consul\Services;

use rabbit\consul\Client;
use rabbit\consul\ConsulResponse;
use rabbit\consul\OptionsResolver;

/**
 * Class Session
 * @package rabbit\consul\Services
 */
final class Session implements SessionInterface
{
    private $client;

    /**
     * Session constructor.
     * @param Client|null $client
     */
    public function __construct(Client $client = null)
    {
        $this->client = $client ?: new Client();
    }

    /**
     * @param null $body
     * @param array $options
     * @return ConsulResponse
     */
    public function create($body = null, array $options = array()): ConsulResponse
    {
        $params = array(
            'body' => $body,
            'query' => OptionsResolver::resolve($options, array('dc')),
        );

        return $this->client->put('/v1/session/create', $params);
    }

    /**
     * @param $sessionId
     * @param array $options
     * @return ConsulResponse
     */
    public function destroy($sessionId, array $options = array()): ConsulResponse
    {
        $params = array(
            'query' => OptionsResolver::resolve($options, array('dc')),
        );

        return $this->client->put('/v1/session/destroy/' . $sessionId, $params);
    }

    /**
     * @param $sessionId
     * @param array $options
     * @return ConsulResponse
     */
    public function info($sessionId, array $options = array()): ConsulResponse
    {
        $params = array(
            'query' => OptionsResolver::resolve($options, array('dc')),
        );

        return $this->client->get('/v1/session/info/' . $sessionId, $params);
    }

    /**
     * @param $node
     * @param array $options
     * @return ConsulResponse
     */
    public function node($node, array $options = array()): ConsulResponse
    {
        $params = array(
            'query' => OptionsResolver::resolve($options, array('dc')),
        );

        return $this->client->get('/v1/session/node/' . $node, $params);
    }

    /**
     * @param array $options
     * @return ConsulResponse
     */
    public function all(array $options = array()): ConsulResponse
    {
        $params = array(
            'query' => OptionsResolver::resolve($options, array('dc')),
        );

        return $this->client->get('/v1/session/list', $params);
    }

    /**
     * @param $sessionId
     * @param array $options
     * @return ConsulResponse
     */
    public function renew($sessionId, array $options = array()): ConsulResponse
    {
        $params = array(
            'query' => OptionsResolver::resolve($options, array('dc')),
        );

        return $this->client->put('/v1/session/renew/' . $sessionId, $params);
    }
}

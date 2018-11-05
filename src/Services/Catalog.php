<?php

namespace rabbit\consul\Services;

use rabbit\consul\Client;
use rabbit\consul\ConsulResponse;
use rabbit\consul\OptionsResolver;
use rabbit\core\ObjectFactory;

/**
 * Class Catalog
 * @package rabbit\consul\Services
 */
final class Catalog implements CatalogInterface
{
    /**
     * @var Client
     */
    private $client;

    /**
     * Catalog constructor.
     * @param Client|null $client
     */
    public function __construct(Client $client = null)
    {
        $this->client = $client ?: new Client(ObjectFactory::get('httpclient'));
    }

    /**
     * @param $node
     * @return ConsulResponse
     */
    public function register($node): ConsulResponse
    {
        $params = array(
            'body' => (string)$node,
        );

        return $this->client->get('/v1/catalog/register', $params);
    }

    /**
     * @param $node
     * @return ConsulResponse
     */
    public function deregister($node): ConsulResponse
    {
        $params = array(
            'body' => (string)$node,
        );

        return $this->client->get('/v1/catalog/deregister', $params);
    }

    /**
     * @return ConsulResponse
     */
    public function datacenters(): ConsulResponse
    {
        return $this->client->get('/v1/catalog/datacenters');
    }

    /**
     * @param array $options
     * @return ConsulResponse
     */
    public function nodes(array $options = array()): ConsulResponse
    {
        $params = array(
            'query' => OptionsResolver::resolve($options, array('dc')),
        );

        return $this->client->get('/v1/catalog/nodes', $params);
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

        return $this->client->get('/v1/catalog/node/' . $node, $params);
    }

    /**
     * @param array $options
     * @return ConsulResponse
     */
    public function services(array $options = array()): ConsulResponse
    {
        $params = array(
            'query' => OptionsResolver::resolve($options, array('dc')),
        );

        return $this->client->get('/v1/catalog/services', $params);
    }

    /**
     * @param $service
     * @param array $options
     * @return ConsulResponse
     */
    public function service($service, array $options = array()): ConsulResponse
    {
        $params = array(
            'query' => OptionsResolver::resolve($options, array('dc', 'tag')),
        );

        return $this->client->get('/v1/catalog/service/' . $service, $params);
    }
}

<?php

namespace rabbit\consul\Services;

use rabbit\consul\Client;
use rabbit\consul\ConsulResponse;
use rabbit\consul\OptionsResolver;
use rabbit\core\ObjectFactory;

/**
 * Class Health
 * @package rabbit\consul\Services
 */
final class Health implements HealthInterface
{
    /**
     * @var Client
     */
    private $client;

    /**
     * Health constructor.
     * @param Client|null $client
     */
    public function __construct(Client $client = null)
    {
        $this->client = $client ?: new Client(ObjectFactory::get('httpclient'));
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

        return $this->client->get('/v1/health/node/' . $node, $params);
    }

    /**
     * @param $service
     * @param array $options
     * @return ConsulResponse
     */
    public function checks($service, array $options = array()): ConsulResponse
    {
        $params = array(
            'query' => OptionsResolver::resolve($options, array('dc')),
        );

        return $this->client->get('/v1/health/checks/' . $service, $params);
    }

    /**
     * @param $service
     * @param array $options
     * @return ConsulResponse
     */
    public function service($service, array $options = array()): ConsulResponse
    {
        $params = array(
            'query' => OptionsResolver::resolve($options, array('dc', 'tag', 'passing')),
        );

        return $this->client->get('/v1/health/service/' . $service, $params);
    }

    /**
     * @param $state
     * @param array $options
     * @return ConsulResponse
     */
    public function state($state, array $options = array()): ConsulResponse
    {
        $params = array(
            'query' => OptionsResolver::resolve($options, array('dc')),
        );

        return $this->client->get('/v1/health/state/' . $state, $params);
    }
}

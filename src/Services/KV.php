<?php

namespace rabbit\consul\Services;

use rabbit\consul\Client;
use rabbit\consul\ConsulResponse;
use rabbit\consul\OptionsResolver;
use rabbit\core\ObjectFactory;

/**
 * Class KV
 * @package rabbit\consul\Services
 */
final class KV
{
    const SERVICE_NAME = 'kv';
    /**
     * @var Client
     */
    private $client;

    /**
     * KV constructor.
     * @param Client|null $client
     */
    public function __construct(Client $client = null)
    {
        $this->client = $client ?: new Client(ObjectFactory::get('httpclient'));
    }

    /**
     * @param $key
     * @param array $options
     * @return ConsulResponse
     */
    public function get($key, array $options = array()): ConsulResponse
    {
        $params = array(
            'query' => OptionsResolver::resolve($options, array('dc', 'recurse', 'keys', 'separator', 'raw')),
        );

        return $this->client->get('v1/kv/' . $key, $params);
    }

    /**
     * @param $key
     * @param $value
     * @param array $options
     * @return ConsulResponse
     */
    public function put($key, $value, array $options = array()): ConsulResponse
    {
        $params = array(
            'body' => $value,
            'query' => OptionsResolver::resolve($options, array('dc', 'flags', 'cas', 'acquire', 'release')),
        );

        return $this->client->put('v1/kv/' . $key, $params);
    }

    /**
     * @param $key
     * @param array $options
     * @return ConsulResponse
     */
    public function delete($key, array $options = array()): ConsulResponse
    {
        $params = array(
            'query' => OptionsResolver::resolve($options, array('dc', 'recurse')),
        );

        return $this->client->delete('v1/kv/' . $key, $params);
    }
}

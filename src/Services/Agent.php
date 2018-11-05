<?php

namespace rabbit\consul\Services;

use rabbit\consul\Client;
use rabbit\consul\ConsulResponse;
use rabbit\consul\OptionsResolver;
use rabbit\core\ObjectFactory;

/**
 * Class Agent
 * @package rabbit\consul\Services
 */
final class Agent extends AbstractService
{
    const SERVICE_NAME = 'agent';
    /**
     * @var Client
     */
    private $client;

    /**
     * Agent constructor.
     * @param Client|null $client
     */
    public function __construct(Client $client = null)
    {
        $this->client = $client ?: new Client(ObjectFactory::get('httpclient'));
    }

    /**
     * @return ConsulResponse
     */
    public function checks(): ConsulResponse
    {
        return $this->client->get('/v1/agent/checks');
    }

    /**
     * @return ConsulResponse
     */
    public function services(): ConsulResponse
    {
        return $this->client->get('/v1/agent/services');
    }

    /**
     * @param array $options
     * @return ConsulResponse
     */
    public function members(array $options = array()): ConsulResponse
    {
        $params = array(
            'query' => OptionsResolver::resolve($options, array('wan')),
        );

        return $this->client->get('/v1/agent/members', $params);
    }

    /**
     * @return ConsulResponse
     */
    public function self(): ConsulResponse
    {
        return $this->client->get('/v1/agent/self');
    }

    /**
     * @param $address
     * @param array $options
     * @return ConsulResponse
     */
    public function join($address, array $options = array()): ConsulResponse
    {
        $params = array(
            'query' => OptionsResolver::resolve($options, array('wan')),
        );

        return $this->client->get('/v1/agent/join/' . $address, $params);
    }

    /**
     * @param $node
     * @return ConsulResponse
     */
    public function forceLeave($node): ConsulResponse
    {
        return $this->client->get('/v1/agent/force-leave/' . $node);
    }

    /**
     * @param $check
     * @return ConsulResponse
     */
    public function registerCheck($check): ConsulResponse
    {
        $params = array(
            'body' => $check,
        );

        return $this->client->put('/v1/agent/check/register', $params);
    }

    /**
     * @param $checkId
     * @return ConsulResponse
     */
    public function deregisterCheck($checkId): ConsulResponse
    {
        return $this->client->put('/v1/agent/check/deregister/' . $checkId);
    }

    /**
     * @param $checkId
     * @param array $options
     * @return ConsulResponse
     */
    public function passCheck($checkId, array $options = array()): ConsulResponse
    {
        $params = array(
            'query' => OptionsResolver::resolve($options, array('note')),
        );

        return $this->client->put('/v1/agent/check/pass/' . $checkId, $params);
    }

    /**
     * @param $checkId
     * @param array $options
     * @return ConsulResponse
     */
    public function warnCheck($checkId, array $options = array()): ConsulResponse
    {
        $params = array(
            'query' => OptionsResolver::resolve($options, array('note')),
        );

        return $this->client->put('/v1/agent/check/warn/' . $checkId, $params);
    }

    /**
     * @param $checkId
     * @param array $options
     * @return ConsulResponse
     */
    public function failCheck($checkId, array $options = array()): ConsulResponse
    {
        $params = array(
            'query' => OptionsResolver::resolve($options, array('note')),
        );

        return $this->client->put('/v1/agent/check/fail/' . $checkId, $params);
    }

    /**
     * @param $service
     * @return ConsulResponse
     */
    public function registerService($service): ConsulResponse
    {
        $params = array(
            'body' => $service,
        );

        return $this->client->put('/v1/agent/service/register', $params);
    }

    /**
     * @param $serviceId
     * @return ConsulResponse
     */
    public function deregisterService($serviceId): ConsulResponse
    {
        return $this->client->put('/v1/agent/service/deregister/' . $serviceId);
    }
}

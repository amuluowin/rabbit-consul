<?php

namespace rabbit\consul;

use rabbit\consul\Services\AbstractService;
use rabbit\consul\Services\Agent;
use rabbit\consul\Services\AgentInterface;
use rabbit\consul\Services\Catalog;
use rabbit\consul\Services\CatalogInterface;
use rabbit\consul\Services\Health;
use rabbit\consul\Services\HealthInterface;
use rabbit\consul\Services\KV;
use rabbit\consul\Services\KVInterface;
use rabbit\consul\Services\Session;
use rabbit\consul\Services\SessionInterface;
use rabbit\core\ObjectFactory;

/**
 * Class ServiceFactory
 * @package rabbit\consul
 */
final class ServiceFactory
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var array
     */
    private $services = [
        // for backward compatibility:
        Agent::SERVICE_NAME => Agent::class,
        Catalog::SERVICE_NAME => Catalog::class,
        Health::SERVICE_NAME => Health::class,
        Session::SERVICE_NAME => Session::class,
        KV::SERVICE_NAME => KV::class,
    ];

    /**
     * ServiceFactory constructor.
     * @param array $options
     * @param array $driver
     */
    public function __construct()
    {
        $this->client = new Client(ObjectFactory::get('httpclient'));
    }

    /**
     * @param $service
     * @return AbstractService
     */
    public function get($service): AbstractService
    {
        if (!array_key_exists($service, $this->services)) {
            throw new \InvalidArgumentException(sprintf('The service "%s" is not available. Pick one among "%s".', $service, implode('", "', array_keys($this->services))));
        }

        if (is_string($this->services[$service])) {
            $class = $this->services[$service];
            $this->services[$service] = new $class($this->client);
        }

        return $this->services[$service];
    }
}

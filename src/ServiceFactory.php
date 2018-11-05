<?php

namespace rabbit\consul;

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
    private $services = [];

    /**
     * @var array
     */
    private static $definition = array(
        // for backward compatibility:
        Agent::SERVICE_NAME => Agent::class,
        Catalog::SERVICE_NAME => Catalog::class,
        Health::SERVICE_NAME => Health::class,
        Session::SERVICE_NAME => Session::class,
        KV::SERVICE_NAME => KV::class,
    );

    /**
     * ServiceFactory constructor.
     * @param array $options
     * @param array $driver
     */
    public function __construct()
    {
        $this->client = new Client(ObjectFactory::get('httpclient'));
        foreach (self::$definition as $name => $service) {
            $this->services[$name] = new $service($this->client);
        }
    }

    /**
     * @return Agent
     */
    public function getAgent(): Agent
    {
        return $this->services[Agent::SERVICE_NAME];
    }

    /**
     * @return Catalog
     */
    public function getCatalog(): Catalog
    {
        return $this->services[Catalog::SERVICE_NAME];
    }

    /**
     * @return Health
     */
    public function getHealth(): Health
    {
        return $this->services[Health::SERVICE_NAME];
    }

    /**
     * @return KV
     */
    public function getKV(): KV
    {
        return $this->services[KV::SERVICE_NAME];
    }

    /**
     * @return Session
     */
    public function getSession(): Session
    {
        return $this->services[Session::SERVICE_NAME];
    }
}

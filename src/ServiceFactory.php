<?php

namespace rabbit\consul;

use Psr\Log\LoggerInterface;
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
use Swlib\Saber;

/**
 * Class ServiceFactory
 * @package rabbit\consul
 */
final class ServiceFactory
{
    /**
     * @var array
     */
    private static $services = array(
        AgentInterface::class => Agent::class,
        CatalogInterface::class => Catalog::class,
        HealthInterface::class => Health::class,
        SessionInterface::class => Session::class,
        KVInterface::class => KV::class,

        // for backward compatibility:
        AgentInterface::SERVICE_NAME => Agent::class,
        CatalogInterface::SERVICE_NAME => Catalog::class,
        HealthInterface::SERVICE_NAME => Health::class,
        SessionInterface::SERVICE_NAME => Session::class,
        KVInterface::SERVICE_NAME => KV::class,
    );
    /**
     * @var Client
     */
    private $client;

    /**
     * @var array
     */
    private $instanceList = [];

    /**
     * ServiceFactory constructor.
     * @param array $options
     * @param LoggerInterface|null $logger
     * @param Saber|null $saber
     */
    public function __construct(array $options = array(), LoggerInterface $logger = null, Saber $saber = null)
    {
        $this->client = new Client($options, $logger, $saber);
    }

    /**
     * @param $service
     * @return mixed
     */
    public function get($service)
    {
        if (!array_key_exists($service, self::$services)) {
            throw new \InvalidArgumentException(sprintf('The service "%s" is not available. Pick one among "%s".', $service, implode('", "', array_keys(self::$services))));
        }

        if (!isset($this->instanceList[$service])) {
            $class = self::$services[$service];
            $this->instanceList[$service] = new $class($this->client);
        }

        return $this->instanceList[$service];
    }
}

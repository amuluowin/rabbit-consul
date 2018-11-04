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

final class ServiceFactory
{
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

    private $client;

    public function __construct(array $options = array(), LoggerInterface $logger = null, Saber $saber = null)
    {
        $this->client = new Client($options, $logger, $saber);
    }

    public function get($service)
    {
        if (!array_key_exists($service, self::$services)) {
            throw new \InvalidArgumentException(sprintf('The service "%s" is not available. Pick one among "%s".', $service, implode('", "', array_keys(self::$services))));
        }

        $class = self::$services[$service];

        return new $class($this->client);
    }
}

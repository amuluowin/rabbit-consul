<?php


namespace rabbit\consul\Services;


use rabbit\consul\ConsulResponse;

/**
 * Interface CatalogInterface
 * @package rabbit\consul\Services
 */
interface CatalogInterface
{
    const SERVICE_NAME = 'catalog';

    public function register($node): ConsulResponse;

    public function deregister($node): ConsulResponse;

    public function datacenters(): ConsulResponse;

    public function nodes(array $options = array()): ConsulResponse;

    public function node($node, array $options = array()): ConsulResponse;

    public function services(array $options = array()): ConsulResponse;

    public function service($service, array $options = array()): ConsulResponse;
}

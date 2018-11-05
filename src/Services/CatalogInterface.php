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

    /**
     * @param $node
     * @return ConsulResponse
     */
    public function register($node): ConsulResponse;

    /**
     * @param $node
     * @return ConsulResponse
     */
    public function deregister($node): ConsulResponse;

    /**
     * @return ConsulResponse
     */
    public function datacenters(): ConsulResponse;

    /**
     * @param array $options
     * @return ConsulResponse
     */
    public function nodes(array $options = array()): ConsulResponse;

    /**
     * @param $node
     * @param array $options
     * @return ConsulResponse
     */
    public function node($node, array $options = array()): ConsulResponse;

    /**
     * @param array $options
     * @return ConsulResponse
     */
    public function services(array $options = array()): ConsulResponse;

    /**
     * @param $service
     * @param array $options
     * @return ConsulResponse
     */
    public function service($service, array $options = array()): ConsulResponse;
}

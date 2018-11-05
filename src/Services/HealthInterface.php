<?php


namespace rabbit\consul\Services;


use rabbit\consul\ConsulResponse;

/**
 * Interface HealthInterface
 * @package rabbit\consul\Services
 */
interface HealthInterface
{
    const SERVICE_NAME = 'health';

    /**
     * @param $node
     * @param array $options
     * @return ConsulResponse
     */
    public function node($node, array $options = array()): ConsulResponse;

    /**
     * @param $service
     * @param array $options
     * @return ConsulResponse
     */
    public function checks($service, array $options = array()): ConsulResponse;

    /**
     * @param $service
     * @param array $options
     * @return ConsulResponse
     */
    public function service($service, array $options = array()): ConsulResponse;

    /**
     * @param $state
     * @param array $options
     * @return ConsulResponse
     */
    public function state($state, array $options = array()): ConsulResponse;
}

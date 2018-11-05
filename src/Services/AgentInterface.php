<?php


namespace rabbit\consul\Services;


use rabbit\consul\ConsulResponse;

/**
 * Interface AgentInterface
 * @package rabbit\consul\Services
 */
interface AgentInterface
{
    const SERVICE_NAME = 'agent';

    /**
     * @return ConsulResponse
     */
    public function checks(): ConsulResponse;

    /**
     * @return ConsulResponse
     */
    public function services(): ConsulResponse;

    /**
     * @param array $options
     * @return ConsulResponse
     */
    public function members(array $options = array()): ConsulResponse;

    /**
     * @return ConsulResponse
     */
    public function self(): ConsulResponse;

    /**
     * @param $address
     * @param array $options
     * @return ConsulResponse
     */
    public function join($address, array $options = array()): ConsulResponse;

    /**
     * @param $node
     * @return ConsulResponse
     */
    public function forceLeave($node): ConsulResponse;

    /**
     * @param $check
     * @return ConsulResponse
     */
    public function registerCheck($check): ConsulResponse;

    /**
     * @param $checkId
     * @return ConsulResponse
     */
    public function deregisterCheck($checkId): ConsulResponse;

    /**
     * @param $checkId
     * @param array $options
     * @return ConsulResponse
     */
    public function passCheck($checkId, array $options = array()): ConsulResponse;

    /**
     * @param $checkId
     * @param array $options
     * @return ConsulResponse
     */
    public function warnCheck($checkId, array $options = array()): ConsulResponse;

    /**
     * @param $checkId
     * @param array $options
     * @return ConsulResponse
     */
    public function failCheck($checkId, array $options = array()): ConsulResponse;

    /**
     * @param $service
     * @return ConsulResponse
     */
    public function registerService($service): ConsulResponse;

    /**
     * @param $serviceId
     * @return ConsulResponse
     */
    public function deregisterService($serviceId): ConsulResponse;
}

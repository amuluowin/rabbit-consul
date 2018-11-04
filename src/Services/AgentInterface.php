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

    public function checks(): ConsulResponse;

    public function services(): ConsulResponse;

    public function members(array $options = array()): ConsulResponse;

    public function self(): ConsulResponse;

    public function join($address, array $options = array()): ConsulResponse;

    public function forceLeave($node): ConsulResponse;

    public function registerCheck($check): ConsulResponse;

    public function deregisterCheck($checkId): ConsulResponse;

    public function passCheck($checkId, array $options = array()): ConsulResponse;

    public function warnCheck($checkId, array $options = array()): ConsulResponse;

    public function failCheck($checkId, array $options = array()): ConsulResponse;

    public function registerService($service): ConsulResponse;

    public function deregisterService($serviceId): ConsulResponse;
}

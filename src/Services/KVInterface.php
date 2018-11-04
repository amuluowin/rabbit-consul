<?php


namespace rabbit\consul\Services;

use rabbit\consul\ConsulResponse;

/**
 * Interface KVInterface
 * @package rabbit\consul\Services
 */
interface KVInterface
{
    const SERVICE_NAME = 'kv';

    /**
     * @param $key
     * @param array $options
     * @return ConsulResponse
     */
    public function get($key, array $options = array()): ConsulResponse;

    /**
     * @param $key
     * @param $value
     * @param array $options
     * @return ConsulResponse
     */
    public function put($key, $value, array $options = array()): ConsulResponse;

    /**
     * @param $key
     * @param array $options
     * @return ConsulResponse
     */
    public function delete($key, array $options = array()): ConsulResponse;
}

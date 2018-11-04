<?php


namespace rabbit\consul\Services;
use rabbit\consul\ConsulResponse;

/**
 * Interface SessionInterface
 * @package rabbit\consul\Services
 */
interface SessionInterface
{
    const SERVICE_NAME = 'session';

    /**
     * @param null $body
     * @param array $options
     * @return ConsulResponse
     */
    public function create($body = null, array $options = array()):ConsulResponse;

    /**
     * @param $sessionId
     * @param array $options
     * @return ConsulResponse
     */
    public function destroy($sessionId, array $options = array()):ConsulResponse;

    /**
     * @param $sessionId
     * @param array $options
     * @return ConsulResponse
     */
    public function info($sessionId, array $options = array()):ConsulResponse;

    /**
     * @param $node
     * @param array $options
     * @return ConsulResponse
     */
    public function node($node, array $options = array()):ConsulResponse;

    /**
     * @param array $options
     * @return ConsulResponse
     */
    public function all(array $options = array()):ConsulResponse;

    /**
     * @param $sessionId
     * @param array $options
     * @return ConsulResponse
     */
    public function renew($sessionId, array $options = array()):ConsulResponse;
}

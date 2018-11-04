<?php

namespace rabbit\consul\Helper;

use rabbit\consul\ConsulResponse;
use rabbit\consul\Services\KV;
use rabbit\consul\Services\Session;

/**
 * Class LockHandler
 * @package rabbit\consul\Helper
 */
final class LockHandler
{
    private $key;
    private $value;
    private $session;
    private $kv;

    private $sessionId;

    /**
     * LockHandler constructor.
     * @param $key
     * @param null $value
     * @param Session|null $session
     * @param KV|null $kv
     */
    public function __construct($key, $value = null, Session $session = null, KV $kv = null)
    {
        $this->key = $key;
        $this->value = $value;
        $this->session = $session ?: new Session();
        $this->kv = $kv ?: new KV();
    }

    /**
     * @return bool
     */
    public function lock(): bool
    {
        // Start a session
        $session = $this->session->create()->json();
        $this->sessionId = $session['ID'];

        // Lock a key / value with the current session
        $lockAcquired = $this->kv->put($this->key, (string)$this->value, ['acquire' => $this->sessionId])->json();

        if (false === $lockAcquired) {
            $this->session->destroy($this->sessionId);

            return false;
        }

        register_shutdown_function(array($this, 'release'));

        return true;
    }

    /**
     *
     */
    public function release(): ConsulResponse
    {
        $this->kv->delete($this->key);
        $this->session->destroy($this->sessionId);
    }
}

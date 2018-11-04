<?php

namespace rabbit\consul;
/**
 * Class ConsulResponse
 * @package rabbit\consul
 */
final class ConsulResponse
{
    /**
     * @var array
     */
    private $headers;
    /**
     * @var mixed
     */
    private $body;
    /** @var int */
    private $status;

    /**
     * ConsulResponse constructor.
     * @param $headers
     * @param $body
     * @param int $status
     */
    public function __construct(array $headers, $body, int $status = 200)
    {
        $this->headers = $headers;
        $this->body = $body;
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function getStatusCode(): int
    {
        return $this->status;
    }

    public function json(): array
    {
        return json_decode($this->body, true);
    }
}

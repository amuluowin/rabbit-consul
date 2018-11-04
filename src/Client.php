<?php

namespace rabbit\consul;

use GuzzleHttp\Exception\TransferException;
use GuzzleHttp\Psr7\Response;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use rabbit\consul\Exception\ClientException;
use rabbit\consul\Exception\ServerException;
use Swlib\Saber;

final class Client implements ClientInterface
{
    /** @var Saber */
    private $client;
    /** @var LoggerInterface */
    private $logger;

    public function __construct(array $options = array(), LoggerInterface $logger = null, Saber $client = null)
    {
        $baseUri = 'http://127.0.0.1:8500';

        if (isset($options['base_uri'])) {
            $baseUri = $options['base_uri'];
        } else if (getenv('CONSUL_HTTP_ADDR') !== false) {
            $baseUri = getenv('CONSUL_HTTP_ADDR');
        }

        $options = array_replace(array(
            'base_uri' => $baseUri,
        ), $options);

        $this->client = $client ?: Saber::create($options);
        $this->logger = $logger ?: new NullLogger();
    }

    public function get($url = null, array $options = array())
    {
        return $this->doRequest('GET', $url, $options);
    }

    public function head($url, array $options = array())
    {
        return $this->doRequest('HEAD', $url, $options);
    }

    public function delete($url, array $options = array())
    {
        return $this->doRequest('DELETE', $url, $options);
    }

    public function put($url, array $options = array())
    {
        return $this->doRequest('PUT', $url, $options);
    }

    public function patch($url, array $options = array())
    {
        return $this->doRequest('PATCH', $url, $options);
    }

    public function post($url, array $options = array())
    {
        return $this->doRequest('POST', $url, $options);
    }

    public function options($url, array $options = array())
    {
        return $this->doRequest('OPTIONS', $url, $options);
    }

    private function doRequest($method, $url, $options)
    {
        $this->logger->info(sprintf('%s "%s"', $method, $url));
        $this->logger->debug(sprintf('Requesting %s %s options={options}', $method, $url), array('{options}' => $options));
        $options = array_merge($options, ['method' => $method, 'uri' => $url]);

        try {
            $response = $this->client->request($options);
        } catch (TransferException $e) {
            $message = sprintf('Something went wrong when calling consul (%s).', $e->getMessage());

            $this->logger->error($message);

            throw new ServerException($message);
        }

        $this->logger->debug(sprintf("Response:\n%s", $this->formatResponse($response)));

        if (400 <= $response->getStatusCode()) {
            $message = sprintf('Something went wrong when calling consul (%s - %s).', $response->getStatusCode(), $response->getReasonPhrase());

            $this->logger->error($message);

            $message .= "\n" . (string)$response->getBody();
            if (500 <= $response->getStatusCode()) {
                throw new ServerException($message, $response->getStatusCode());
            }

            throw new ClientException($message, $response->getStatusCode());
        }

        return new ConsulResponse($response->getHeaders(), (string)$response->getBody(), $response->getStatusCode());
    }

    private function formatResponse(Response $response)
    {
        $headers = array();

        foreach ($response->getHeaders() as $key => $values) {
            foreach ($values as $value) {
                $headers[] = sprintf('%s: %s', $key, $value);
            }
        }

        return sprintf("%s\n\n%s", implode("\n", $headers), $response->getBody());
    }
}

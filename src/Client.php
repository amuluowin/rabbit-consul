<?php

namespace rabbit\consul;

use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use rabbit\consul\Exception\ClientException;
use rabbit\consul\Exception\ServerException;
use rabbit\helper\JsonHelper;
use rabbit\httpclient\ClientInterface;
use Swlib\Saber;

final class Client implements ClientInterface
{
    /** @var Saber */
    private $client;
    /** @var LoggerInterface */
    private $logger;

    /**
     * Client constructor.
     * @param array $options
     * @param LoggerInterface|null $logger
     * @param Saber|null $client
     */
    public function __construct(array $options = array(), LoggerInterface $logger = null, Saber $client = null)
    {
        $this->client = $client ?: Saber::create($options);
        $this->logger = $logger ?: new NullLogger();
    }

    /**
     * @param null $url
     * @param array $options
     * @return ConsulResponse
     */
    public function get($url = null, array $options = array())
    {
        return $this->doRequest('GET', $url, $options);
    }

    /**
     * @param $url
     * @param array $options
     * @return ConsulResponse
     */
    public function head($url, array $options = array())
    {
        return $this->doRequest('HEAD', $url, $options);
    }

    /**
     * @param $url
     * @param array $options
     * @return ConsulResponse
     */
    public function delete($url, array $options = array())
    {
        return $this->doRequest('DELETE', $url, $options);
    }

    /**
     * @param $url
     * @param array $options
     * @return mixed|ConsulResponse
     */
    public function put($url, array $options = array())
    {
        return $this->doRequest('PUT', $url, $options);
    }

    /**
     * @param $url
     * @param array $options
     * @return mixed|ConsulResponse
     */
    public function patch($url, array $options = array())
    {
        return $this->doRequest('PATCH', $url, $options);
    }

    /**
     * @param $url
     * @param array $options
     * @return mixed|ConsulResponse
     */
    public function post($url, array $options = array())
    {
        return $this->doRequest('POST', $url, $options);
    }

    /**
     * @param $url
     * @param array $options
     * @return mixed|ConsulResponse
     */
    public function options($url, array $options = array())
    {
        return $this->doRequest('OPTIONS', $url, $options);
    }

    /**
     * @param $method
     * @param $url
     * @param $options
     * @return ConsulResponse
     */
    private function doRequest($method, $url, $options)
    {
        $this->logger->info(sprintf('%s "%s"', $method, $url));
        $this->logger->debug(sprintf('Requesting %s %s options={options}', $method, $url), array('{options}' => JsonHelper::encode($options)));
        $options = array_merge($options, ['method' => $method, 'uri' => $url]);

        try {
            $response = $this->client->request($options);
        } catch (\Exception $e) {
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

    /**
     * @param ResponseInterface $response
     * @return string
     */
    private function formatResponse(ResponseInterface $response): string
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

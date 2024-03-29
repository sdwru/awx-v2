<?php

namespace AwxV2\Adapter;

use AwxV2\Exception\HttpException;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Response;

/**
 * @author Marcos Sigueros <alrik11es@gmail.com>
 * @author Chris Fidao <fideloper@gmail.com>
 * @author Graham Campbell <graham@alt-three.com>
 */
class GuzzleHttpAdapter implements AdapterInterface
{
    /**
     * @var ClientInterface
     */
    protected $client;

    /**
     * @var Response
     */
    protected $response;

    /**
     * @param string               $token
     * @param ClientInterface|null $client
     */
    public function __construct($token, $verify = true, ClientInterface $client = null)
    {
        $version;
        if (defined('ClientInterface::VERSION')){
            $version = ClientInterface::VERSION;
        } else {
            // Guzzle 7 ClientInterface does not have a VERSION CONSTANT!!
            $version = ClientInterface::MAJOR_VERSION;
        }
        
        if (version_compare($version, '6', '>=')) {
            $this->client = $client ?: new Client([
                'headers' => ['Authorization' => sprintf('Bearer %s', $token)],
                'verify' => $verify
            ]);
        } else {
            $this->client = $client ?: new Client();

            $this->client->setDefaultOption('headers/Authorization', sprintf('Bearer %s', $token));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function get($url)
    {
        try {
            $this->response = $this->client->get($url);
        } catch (RequestException $e) {
            $this->response = $e->getResponse();
            $this->handleError();
        }

        return (string)$this->response->getBody();
    }

    /**
     * {@inheritdoc}
     */
    public function delete($url)
    {
        try {
            $this->response = $this->client->delete($url);
        } catch (RequestException $e) {
            $this->response = $e->getResponse();
            $this->handleError();
        }

        return (string)$this->response->getBody();
    }

    /**
     * {@inheritdoc}
     */
    public function put($url, $content = '')
    {
        $options = [];

        $options[is_array($content) ? 'json' : 'body'] = $content;

        try {
            $this->response = $this->client->put($url, $options);
        } catch (RequestException $e) {
            $this->response = $e->getResponse();
            $this->handleError();
        }

        return (string)$this->response->getBody();
    }

    /**
     * {@inheritdoc}
     */
    public function post($url, $content = '')
    {
        $options = [];

        $options[is_array($content) ? 'json' : 'body'] = $content;

        try {
            $this->response = $this->client->post($url, $options);
        } catch (RequestException $e) {
            $this->response = $e->getResponse();
            $this->handleError();
        }

        return (string)$this->response->getBody();
    }

    /**
     * {@inheritdoc}
     */
    public function getLatestResponseHeaders()
    {
        if (null === $this->response) {
            return null;
        }

        return [
            'reset' => (int) (string) $this->response->getHeader('RateLimit-Reset'),
            'remaining' => (int) (string) $this->response->getHeader('RateLimit-Remaining'),
            'limit' => (int) (string) $this->response->getHeader('RateLimit-Limit'),
        ];
    }

    /**
     * @throws HttpException
     */
    protected function handleError()
    {
        $body = (string) $this->response->getBody();
        $code = (int) $this->response->getStatusCode();

        $content = json_decode($body);

        throw new HttpException(isset($content->message) ? $content->message : 'Request not processed.', $code);
    }
}

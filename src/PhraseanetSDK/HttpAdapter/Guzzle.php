<?php

namespace PhraseanetSDK\HttpAdapter;

use Guzzle\Common\Event;
use Guzzle\Common\GuzzleException;
use Guzzle\Http\ClientInterface;
use Guzzle\Http\Exception\BadResponseException as GuzzleBadResponse;
use Guzzle\Http\Exception\CurlException;
use PhraseanetSDK\Exception\BadResponseException;
use PhraseanetSDK\Exception\RuntimeException;

class Guzzle implements HttpAdapterInterface
{
    private $client;
    private $token;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    public function getBaseUrl()
    {
        return $this->client->getBaseUrl();
    }

    public function setBaseUrl($url)
    {
        $this->client->setBaseUrl($url);

        return $this;
    }

    /**
     * GET request
     *
     * @param string $path The path to query
     * @param array $args An array of query parameters
     * @return string The response body
     * @throws BadResponseException
     * @throws RuntimeException
     */
    public function get($path, array $args = array())
    {
        $queryDatas = $this->formatQueryParameters($args);

        $path = sprintf('%s%s', ltrim($path, '/'), $this->getTemplate($queryDatas['data']));

        try {
            $request = $this->client->get(array($path, $queryDatas));
            $request->setHeader('Accept', 'application/json');
            $response = $request->send();
        } catch (CurlException $e) {
            throw new RuntimeException($e->getMessage(), $e->getErrorNo(), $e);
        } catch (GuzzleBadResponse $e) {
            $response = $e->getResponse();
            $ex = new BadResponseException($response->getReasonPhrase(), $e->getCode(), $e);
            $ex->setResponseBody($response->getBody())->setHttpStatusCode($response->getStatusCode());
            throw $ex;
        } catch (GuzzleException $e) {
            throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
        }

        return $response->getBody();
    }

    /**
     * Post request
     *
     * @param string $path The path to query
     * @param array $args An array of query parameters
     * @return string The response body
     * @throws BadResponseException
     * @throws RuntimeException
     */
    public function post($path, array $args = array())
    {
        $queryDatas = $this->formatQueryParameters($args);

        $path = sprintf('%s%s', ltrim($path, '/'), $this->getTemplate($queryDatas['data']));

        try {
            $request = $this->client->post(array($path, $queryDatas));
            $request->setHeader('Accept', 'application/json');
            $response = $request->send();
        } catch (CurlException $e) {
            throw new RuntimeException($e->getMessage(), $e->getErrorNo(), $e);
        } catch (GuzzleBadResponse $e) {
            $response = $e->getResponse();
            $ex = new BadResponseException($response->getReasonPhrase(), $e->getCode(), $e);
            $ex->setResponseBody($response->getBody())->setHttpStatusCode($response->getStatusCode());

            throw $ex;
        } catch (GuzzleException $e) {
            throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
        }

        return $response->getBody();
    }

    public function disabledException()
    {
        $this->client->getEventDispatcher()->addListener('request.error', function(Event $event) {
                $event->stopPropagation();
            }, -254
        );
    }

    private function getTemplate(array $args)
    {
        return '{?' . (null !== $this->token ? 'oauth_token,' : '') . ( ! empty($args) ? 'data*' : '' ) . '}';
    }

    private function formatQueryParameters($args)
    {
        $queryDatas = array('data' => $args);

        if (isset($args['oauth_token'])) {
            $this->token = $args['oauth_token'];
            unset($args['oauth_token']);
        }

        if ($this->token) {
            $queryDatas['oauth_token'] = $this->token;
        }

        return $queryDatas;
    }
}

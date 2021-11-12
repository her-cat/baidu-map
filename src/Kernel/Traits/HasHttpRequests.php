<?php

/*
 * This file is part of the her-cat/baidu-map.
 *
 * (c) her-cat <i@her-cat.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace HerCat\BaiduMap\Kernel\Traits;

use function GuzzleHttp\choose_handler;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\HandlerStack;
use Psr\Http\Message\ResponseInterface;

/**
 * Trait HasHttpRequests.
 *
 * @author overtrue <i@overtrue.me>
 */
trait HasHttpRequests
{
    use ResponseCastable;

    /**
     * @var ClientInterface
     */
    protected $httpClient;

    /**
     * @var array
     */
    protected $middlewares = [];

    /**
     * @var HandlerStack
     */
    protected $handlerStack;

    /**
     * @var array
     */
    protected static $defaults = [];

    /**
     * @param array $defaults
     */
    public static function setDefaultOptions($defaults = [])
    {
        self::$defaults = $defaults;
    }

    /**
     * @return array
     */
    public static function getDefaultOptions()
    {
        return self::$defaults;
    }

    public function setHttpClient(ClientInterface $client)
    {
        $this->httpClient = $client;
    }

    /**
     * @return Client|ClientInterface
     */
    public function getHttpClient()
    {
        if (!($this->httpClient instanceof ClientInterface)) {
            if (property_exists($this, 'app') && isset($this->app['http_client'])) {
                $this->httpClient = $this->app['http_client'];
            } else {
                $this->httpClient = new Client(['handler' => choose_handler()]);
            }
        }

        return $this->httpClient;
    }

    /**
     * Add a middleware.
     *
     * @param string|null $name
     *
     * @return $this
     */
    public function pushMiddleware(callable $middleware, $name = null)
    {
        if (is_null($name)) {
            array_push($this->middlewares, $middleware);
        } else {
            $this->middlewares[$name] = $middleware;
        }

        return $this;
    }

    /**
     * Get all middlewares.
     *
     * @return array
     */
    public function getMiddlewares()
    {
        return $this->middlewares;
    }

    /**
     * Make a request.
     *
     * @param string $url
     * @param string $method
     *
     * @return mixed|ResponseInterface
     *
     * @throws GuzzleException
     */
    public function request($url, $method = 'GET', array $options = [])
    {
        $method = strtoupper($method);

        $options = array_merge(self::$defaults, $options, ['handler' => $this->getHandlerStack()]);

        $options = $this->fixJsonIssue($options);

        if (property_exists($this, 'baseUri') && !is_null($this->baseUri)) {
            $options['base_uri'] = $this->baseUri;
        }

        $response = $this->getHttpClient()->request($method, $url, $options);
        $response->getBody()->rewind();

        return $response;
    }

    public function setHandlerStack(HandlerStack $handlerStack)
    {
        $this->handlerStack = $handlerStack;
    }

    /**
     * Build a handler stack.
     *
     * @return HandlerStack
     */
    public function getHandlerStack()
    {
        if (!is_null($this->handlerStack)) {
            return $this->handlerStack;
        }

        $this->handlerStack = HandlerStack::create(choose_handler());

        foreach ($this->getMiddlewares() as $name => $middleware) {
            $this->handlerStack->push($middleware, $name);
        }

        return $this->handlerStack;
    }

    /**
     * @return array
     */
    public function fixJsonIssue(array $options)
    {
        if (isset($options['json']) && is_array($options['json'])) {
            $options['headers'] = array_merge(
                isset($options['headers']) ? $options['headers'] : [],
                ['Content-Type' => 'application/json']
            );

            if (empty($options['json'])) {
                $options['body'] = \Guzzlehttp\json_encode($options['json'], JSON_FORCE_OBJECT);
            } else {
                $options['body'] = \GuzzleHttp\json_encode($options['json'], JSON_UNESCAPED_UNICODE);
            }

            unset($options['json']);
        }

        return $options;
    }
}

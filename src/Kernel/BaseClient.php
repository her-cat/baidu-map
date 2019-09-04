<?php

/*
 * This file is part of the her-cat/baidu-map.
 *
 * (c) her-cat <i@her-cat.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace HerCat\BaiduMap\Kernel;

use Closure;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\MessageFormatter;
use GuzzleHttp\Middleware;
use HerCat\BaiduMap\Kernel\Http\Response;
use HerCat\BaiduMap\Kernel\Http\StreamResponse;
use HerCat\BaiduMap\Kernel\Traits\HasHttpRequests;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LogLevel;

/**
 * Class BaseClient.
 *
 * @author her-cat <i@her-cat.com>
 */
class BaseClient
{
    use HasHttpRequests {request as performRequest; }

    /**
     * @var ServiceContainer
     */
    protected $app;

    /**
     * @var string
     */
    protected $baseUri;

    /**
     * @var bool
     */
    protected $needSignature = true;

    /**
     * BaseClient constructor.
     *
     * @param ServiceContainer $app
     */
    public function __construct(ServiceContainer $app)
    {
        $this->app = $app;
    }

    /**
     * @param string $url
     * @param array  $query
     *
     * @return array|Http\Response|Support\Collection|mixed|object|ResponseInterface
     *
     * @throws Exceptions\InvalidConfigException
     * @throws GuzzleException
     */
    public function httpGet($url, array $query = [])
    {
        return $this->request($url, 'GET', ['query' => $query]);
    }

    /**
     * @param string $url
     * @param array  $params
     *
     * @return array|Http\Response|Support\Collection|mixed|object|ResponseInterface
     *
     * @throws Exceptions\InvalidConfigException
     * @throws GuzzleException
     */
    public function httpPost($url, array $params = [])
    {
        return $this->request($url, 'POST', ['form_params' => $params]);
    }

    /**
     * @param string $url
     * @param array $params
     *
     * @param array $query
     * @return array|Http\Response|Support\Collection|mixed|object|ResponseInterface
     *
     * @throws Exceptions\InvalidConfigException
     * @throws GuzzleException
     */
    public function httpPostJson($url, array $params = [], array $query = [])
    {
        return $this->request($url, 'POST', ['json' => $params, 'query' => $query]);
    }

    /**
     * @param string $url
     * @param string $method
     * @param array  $params
     *
     * @return array|Response|StreamResponse|Support\Collection|object|ResponseInterface
     *
     * @throws Exceptions\InvalidConfigException
     * @throws GuzzleException
     */
    public function httpGetStream($url, $method = 'GET', array $params = [])
    {
        $options = ('GET' == strtoupper($method))
            ? ['query' => $params]
            : ['form_params' => $params];

        $response = $this->request($url, $method, $options, true);

        if (false !== stripos($response->getHeaderLine('Content-Type'), 'image')) {
            return StreamResponse::buildFromPsrResponse($response);
        }

        return $this->castResponseToType($response, $this->app->config->get('response_type'));
    }

    /**
     * @param string $url
     * @param string $method
     * @param array  $options
     * @param bool   $returnRaw
     *
     * @return array|Http\Response|Support\Collection|mixed|object|ResponseInterface
     *
     * @throws Exceptions\InvalidConfigException
     * @throws GuzzleException
     */
    public function request($url, $method = 'GET', array $options = [], $returnRaw = false)
    {
        if (empty($this->middlewares)) {
            $this->registerHttpMiddlewares();
        }

        $response = $this->performRequest($url, $method, $options);

        return $returnRaw ? $response : $this->castResponseToType($response, $this->app->config->get('response_type'));
    }

    /**
     * @param string $url
     * @param string $method
     * @param array  $options
     *
     * @return Response
     *
     * @throws Exceptions\InvalidConfigException
     * @throws GuzzleException
     */
    public function requestRaw($url, $method = 'GET', array $options = [])
    {
        return Response::buildFromPsrResponse($this->request($url, $method, $options, true));
    }

    /**
     * Register Guzzle middleware.
     */
    protected function registerHttpMiddlewares()
    {
        if ($this->needSignature) {
            $this->pushMiddleware($this->akMiddleware(), 'ak');

            if ($this->app->config->has('sk')) {
                $this->pushMiddleware($this->signatureMiddleware(), 'signature');
            }
        }

        $this->pushMiddleware($this->logMiddleware(), 'log');
    }

    /**
     * Attache ak to request.
     *
     * @return Closure
     */
    protected function akMiddleware()
    {
        return function (callable $handler) {
            return function (RequestInterface $request, array $options) use ($handler) {
                $request = $this->app->signature->applyAkToRequest($request);

                return $handler($request, $options);
            };
        };
    }

    /**
     * Attache signature to request.
     *
     * @return Closure
     */
    protected function signatureMiddleware()
    {
        return function (callable $handler) {
            return function (RequestInterface $request, array $options) use ($handler) {
                $request = $this->app->signature->applySignatureToRequest($request);

                return $handler($request, $options);
            };
        };
    }

    /**
     * Log the request.
     *
     * @return callable
     */
    protected function logMiddleware()
    {
        $template = $this->app->config->get('http.log_template', MessageFormatter::DEBUG);

        $formatter = new MessageFormatter($template);

        return Middleware::log($this->app->logger, $formatter, LogLevel::DEBUG);
    }
}

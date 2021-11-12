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

use GuzzleHttp\Psr7\Utils;
use HerCat\BaiduMap\Kernel\Exceptions\InvalidArgumentException;
use Psr\Http\Message\RequestInterface;

/**
 * Class Signature.
 *
 * @author her-cat <i@her-cat.com>
 */
class Signature
{
    /**
     * @var string
     */
    protected $ak;

    /**
     * @var string
     */
    protected $sk;

    /**
     * Signature constructor.
     *
     * @throws InvalidArgumentException
     */
    public function __construct(ServiceContainer $app)
    {
        $this->ak = $app->config->get('ak');

        if (is_null($this->ak)) {
            throw new InvalidArgumentException('The \'ak\' not configured.');
        }

        $this->sk = $app->config->get('sk');
    }

    /**
     * @return string
     */
    public function getAk()
    {
        return $this->ak;
    }

    /**
     * @param string $ak
     */
    public function setAk($ak)
    {
        $this->ak = $ak;
    }

    /**
     * @return string
     */
    public function getSk()
    {
        return $this->sk;
    }

    /**
     * @param string $sk
     */
    public function setSk($sk)
    {
        $this->sk = $sk;
    }

    /**
     * Make signature.
     *
     * @param string $uri
     * @param string $method
     *
     * @return string
     */
    public function make($uri, $method, array $params)
    {
        'POST' === strtoupper($method) && ksort($params);

        $querystring = http_build_query($params);

        return md5(urlencode("{$uri}?{$querystring}{$this->getSk()}"));
    }

    /**
     * Applying app ak to requests.
     *
     * @return RequestInterface
     */
    public function applyAkToRequest(RequestInterface $request)
    {
        return $this->applyParamsToRequest($request, $this->getParams($request), ['ak' => $this->getAk()]);
    }

    /**
     * Applying signature to requests.
     *
     * @return RequestInterface
     */
    public function applySignatureToRequest(RequestInterface $request)
    {
        $params = $this->getParams($request);

        $signature = $this->make(
            $request->getUri()->getPath(),
            $request->getMethod(),
            $params
        );

        return $this->applyParamsToRequest($request, $params, ['sn' => $signature]);
    }

    /**
     * Applying params to requests.
     *
     * @return RequestInterface
     */
    public function applyParamsToRequest(RequestInterface $request, array $params = [], array $appends = [])
    {
        $querystring = http_build_query(array_merge($params, $appends));

        return ('GET' == $request->getMethod())
            ? $request->withUri($request->getUri()->withQuery($querystring))
            : $request->withBody(Utils::streamFor($querystring));
    }

    /**
     * Get the requests params.
     *
     * @return array
     */
    public function getParams(RequestInterface $request)
    {
        $querystring = ('GET' === $request->getMethod())
            ? $request->getUri()->getQuery()
            : $request->getBody()->getContents();

        parse_str($querystring, $params);

        return $params;
    }
}

<?php

namespace HerCat\BaiduMap\Kernel;

use function GuzzleHttp\Psr7\stream_for;
use HerCat\BaiduMap\Kernel\Exceptions\InvalidArgumentException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;

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
     * @param ServiceContainer $app
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
     * @param array  $params
     *
     * @return string
     */
    public function make($uri, $method, array $params)
    {
        strtoupper($method) === 'POST' && ksort($params);

        $querystring = http_build_query($params);

        return md5(urlencode("{$uri}?{$querystring}{$this->getSk()}"));
    }

    /**
     * Applying app ak to requests.
     *
     * @param RequestInterface $request
     *
     * @return RequestInterface|UriInterface
     */
    public function applyAkToRequest(RequestInterface $request)
    {
        return $this->applyParamsToRequest($request, $this->getParams($request), ['ak' => $this->getAk()]);
    }

    /**
     * Applying signature to requests.
     *
     * @param RequestInterface $request
     *
     * @return RequestInterface|UriInterface
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
     * @param RequestInterface $request
     * @param array $params
     * @param array $appends
     *
     * @return RequestInterface|UriInterface
     */
    public function applyParamsToRequest(RequestInterface $request, array $params = [], array $appends = [])
    {
        $querystring = http_build_query(array_merge($params, $appends));

        return ('GET' == $request->getMethod())
            ? $request->getUri()->withQuery($querystring)
            : $request->withBody(stream_for($querystring));
    }

    /**
     * Get the requests params.
     *
     * @param RequestInterface $request
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

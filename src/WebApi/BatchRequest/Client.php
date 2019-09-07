<?php

namespace HerCat\BaiduMap\WebApi\BatchRequest;

use GuzzleHttp\Exception\GuzzleException;
use function GuzzleHttp\Psr7\parse_query;
use HerCat\BaiduMap\Kernel\BaseClient;
use HerCat\BaiduMap\Kernel\Exceptions\InvalidArgumentException;
use HerCat\BaiduMap\Kernel\Exceptions\InvalidConfigException;
use HerCat\BaiduMap\Kernel\Http\Response;
use HerCat\BaiduMap\Kernel\Support\Collection;
use Psr\Http\Message\ResponseInterface;

/**
 * Class Client.
 *
 * @author her-cat <i@her-cat.com>
 */
class Client extends BaseClient
{
    protected $needSignature = false;

    /**
     * @param $params
     *
     * @return array|Response|Collection|mixed|object|ResponseInterface
     *
     * @throws GuzzleException
     * @throws InvalidConfigException
     */
    public function get(array $params)
    {
        $ak = $this->app->config->get('ak');

        $params = array_map(function ($value) use ($ak) {
            if (!isset($value['url'])) {
                throw new InvalidArgumentException('The url cannot be empty.');
            }

            $url = parse_url($value['url']);

            if (empty($url['query']) || stripos($url['query'], 'ak=') === false) {
                $value['url'] .= sprintf('%sak=%s', empty($url['query']) ? '?' : '&', $ak);
            }

            return $value;
        }, (array) $params);

        return $this->httpPostJson('batch', ['reqs' => $params]);
    }
}

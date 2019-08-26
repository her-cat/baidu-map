<?php

namespace HerCat\BaiduMap\WebApi\PlaceSearch;

/**
 * Interface PlaceSearchInterface.
 *
 * @author her-cat <i@her-cat.com>
 */
interface PlaceSearchInterface
{
    /**
     * @param string $keyword
     * @param string $region
     * @param array $options
     *
     * @return mixed
     */
    public function region($keyword, $region, $options = []);

    /**
     * @param string $keyword
     * @param string|float $longitude
     * @param string|float $latitude
     * @param array $options
     *
     * @return mixed
     */
    public function circle($keyword, $longitude, $latitude, $options = []);

    /**
     * @param string $keyword
     * @param string|array $bounds
     * @param array $options
     *
     * @return mixed
     */
    public function rectangle($keyword, $bounds, $options = []);

    /**
     * @param string|array $uid
     * @param int $scope
     * @param string $output
     *
     * @return mixed
     */
    public function get($uid, $scope = 1, $output = 'json');
}

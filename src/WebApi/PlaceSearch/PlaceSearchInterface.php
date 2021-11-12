<?php

/*
 * This file is part of the her-cat/baidu-map.
 *
 * (c) her-cat <i@her-cat.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

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
     *
     * @return mixed
     */
    public function region($keyword, $region, array $options = []);

    /**
     * @param string       $keyword
     * @param string|float $longitude
     * @param string|float $latitude
     *
     * @return mixed
     */
    public function circle($keyword, $longitude, $latitude, array $options = []);

    /**
     * @param string       $keyword
     * @param string|array $bounds
     *
     * @return mixed
     */
    public function rectangle($keyword, $bounds, array $options = []);

    /**
     * @param string|array $uid
     * @param int          $scope
     * @param string       $output
     *
     * @return mixed
     */
    public function get($uid, $scope = 1, $output = 'json');
}

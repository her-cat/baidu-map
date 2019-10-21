<h1 align="center"> 🗺️ baidu-map </h1>

<p align="center">可能是我用过的最好用的百度地图 SDK 了</p>

[![Build Status](https://travis-ci.org/her-cat/baidu-map.svg?branch=master)](https://travis-ci.org/her-cat/baidu-map) 
[![StyleCI build status](https://github.styleci.io/repos/200389077/shield)](https://github.styleci.io/repos/200389077)

## 环境要求

- PHP >= 5.6
- [Composer](https://getcomposer.org/)
- fileinfo 拓展（获取静态图需要用到）

## 安装

```shell
$ composer require "her-cat/baidu-map" -vvv
```

## 单元测试

```shell
$ composer test
```

## 使用

```php
<?php

use HerCat\BaiduMap\Factory;

$config = [
    'ak' => 'your ak',
//    'sk' => 'your sk',
    'log' => [
        'file' => './baidu-map.log'
    ],
    'response_type' => 'array',
];

$webApi = Factory::webApi($config);

$result = $webApi->timezone->get('116.30815', '40.056878');

//    Array
//    (
//        [status] => 0
//        [timezone_id] => Asia/Shanghai
//        [dst_offset] => 0
//        [raw_offset] => 28800
//    )
```

## 文档

[详细文档](http://doc.hxhsoft.cn/docs)

## Features

- [ ] [LBS云](http://lbsyun.baidu.com/index.php?title=lbscloud)
- [x] [Web服务API](http://lbsyun.baidu.com/index.php?title=webapi)
- [ ] [鹰眼轨迹服务](http://lbsyun.baidu.com/index.php?title=yingyan)

## 参考

- [overtrue/wechat](https://github.com/overtrue/wechat)
- [PHP 扩展包实战教程 - 从入门到发布](https://learnku.com/courses/creating-package)

## License

MIT

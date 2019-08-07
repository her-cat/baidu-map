<?php

namespace HerCat\BaiduMap\Kernel\Providers;

use HerCat\BaiduMap\Kernel\Exceptions\Exception;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class LogServiceProvider implements ServiceProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function register(Container $app)
    {
        $app['logger'] = function ($app) {
            $logger = new Logger($app->config->get('log.name'));

            $logger->pushHandler($this->getDefaultHandler($app));

            return $logger;
        };
    }

    /**
     * @param $app
     *
     * @return RotatingFileHandler
     *
     * @throws Exception
     */
    public function getDefaultHandler($app)
    {
        $handler = new RotatingFileHandler(
            $app->config->get('log.file', sprintf('%s/logs/baidu-map.log', \sys_get_temp_dir())),
            $app->config->get('log.days', 7),
            $this->level($app->config->get('log.level', 'DEBUG'))
        );

        $handler->setFormatter($this->getLineFormatter());

        return $handler;
    }

    /**
     * @param string $level
     *
     * @return int
     *
     * @throws Exception
     */
    public function level($level)
    {
        $level = Logger::toMonologLevel(strval($level));

        if (is_integer($level)) {
            return $level;
        }

        throw new Exception('Invalid log level.');
    }

    /**
     * @return LineFormatter
     */
    public function getLineFormatter()
    {
        return new LineFormatter(null, null, true, true);
    }
}

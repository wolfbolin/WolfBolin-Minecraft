<?php
/**
 * Created by PhpStorm.
 * User: wolfbolin
 * Date: 2019/3/28
 * Time: 23:11
 */

// PHP启动设置
date_default_timezone_set('Asia/Shanghai');

// 处理全局异常数据
error_reporting(E_ALL);
set_error_handler(function ($severity, $message, $file, $line) {
    if (error_reporting() & $severity) {
        throw new \ErrorException($message, 0, $severity, $file, $line);
    }
});


// 引入Composer组建
require __DIR__ . '/../vendor/autoload.php';

// 注册私有工具
require __DIR__ . '/../src/util/http_response.php';
require __DIR__ . '/../src/util/zip.php';

// 注册配置数据
$config = require __DIR__ . '/../src/config.php';
$static = require __DIR__ . '/../src/static.php';
$parameter = array_merge($config, $static);

// 注册应用程序
$app = new Slim\App($parameter);

// 注册容器环境
require __DIR__ . '/../src/util/container.php';

// 注册网络中间件
require __DIR__ . '/../src/util/middleware.php';

// 注册网络路由
require __DIR__ . '/../src/route/info.php';
require __DIR__ . '/../src/route/backup.php';

// 运行应用程序
try {
    $app->run();
} catch (Exception $e) {
    $container = $app->getContainer();
    $sentry = $container->get('sentry_client');
    $sentry->captureException($e);
}

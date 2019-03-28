<?php
/**
 * Created by PhpStorm.
 * User: wolfbolin
 * Date: 2019/3/28
 * Time: 23:12
 */

use \Slim\Container;
use \Slim\Http\Request;
use \Slim\Http\Response;

$container = $app->getContainer();

// Sentry组建初始化
$container['sentry_client'] = function (Container $a) {
    $sentry_dsn = $a->get('Sentry_DSN');
    $sentry = new Raven_Client($sentry_dsn,
        [
            'version' => $a->get('Version'),
            'php_version' => phpversion()
        ]
    );
    return $sentry;
};

// COS组建初始化
$container['cos_client'] = function (Container $a) {
    $secret_id = $a->get('COS')['Secret_Id'];
    $secret_key = $a->get('COS')['Secret_Key'];
    $region = $a->get('COS')['Region'];

    $client = new Qcloud\Cos\Client([
        'region' => $region,
        'schema' => 'https',
        'credentials' => [
            'secretId' => $secret_id,
            'secretKey' => $secret_key
        ]
    ]);

    try{
        $client->headBucket(['Bucket'=>$a->get('COS')['Bucket']])->toArray();
    }catch (Qcloud\Cos\Exception\ServiceResponseException $e){
        $sentry = new Raven_Client($a['Sentry_DSN']);
        $sentry->captureException($e, array(
            'tags' => array(
                'version' => $a['Version']
            )
        ));
    }

    return $client;
};


// 异常访问处理
$container['notFoundHandler'] = function ($a) {
    return function ($request, $response) use ($a) {
        return WolfBolin\Slim\HTTP\Not_found($response);
    };
};
$container['notAllowedHandler'] = function ($a) {
    return function ($request, $response) use ($a) {
        return WolfBolin\Slim\HTTP\Not_allowed($response);
    };
};
$container['errorHandler'] = function ($a) {
    return function (Request $request, Response $response, $exception) use ($a) {
        $sentry = new Raven_Client($a['Sentry_DSN']);
        $sentry->captureException($exception, array(
            'extra' => array(
                'URL' => $request->getUri(),
                'Method' => $request->getMethod(),
                'Body' => $request->getBody()
            ),
            'tags' => array(
                'version' => $a['Version']
            )
        ));
        return WolfBolin\Slim\HTTP\Server_error($response);
    };
};
$container['phpErrorHandler'] = function ($a) {
    return function (Request $request, Response $response, $exception) use ($a) {
        $sentry = new Raven_Client($a['Sentry_DSN']);
        $sentry->captureException($exception, array(
            'extra' => array(
                'URL' => $request->getUri(),
                'Method' => $request->getMethod(),
                'Body' => $request->getBody()
            ),
            'tags' => array(
                'version' => $a['Version']
            )
        ));
        return WolfBolin\Slim\HTTP\Server_error($response);
    };
};


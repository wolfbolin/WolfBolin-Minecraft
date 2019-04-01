<?php
/**
 * Created by PhpStorm.
 * User: wolfbolin
 * Date: 2019/3/31
 * Time: 21:09
 */

use \Slim\App;
use \Slim\Http\Request;
use \Slim\Http\Response;

$app->redirect('/', '/hello-mc', 301);

$app->get('/hello', function (Request $request, Response $response) {
    $result = ['status' => 'success', 'info' => 'Hello, Minecraft!'];
    return $response->withJson($result);
});

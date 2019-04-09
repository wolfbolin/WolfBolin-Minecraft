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

$app->redirect('/', '/hello-mc', 301)->add(\WolfBolin\Slim\Middleware\http_cors());

$app->get('/hello-mc', function (Request $request, Response $response) {
    $result = ['status' => 'success', 'info' => 'Hello, Minecraft!'];
    return $response->withJson($result);
})->add(\WolfBolin\Slim\Middleware\http_cors());

$app->group('/info', function (App $app) {
    $app->get('/time', function (Request $request, Response $response) {
        // 获取运行时间
        $db = new MongoDB\Database($this->get('mongodb_client'), $this->get('MongoDB')['db']);
        $collection = $db->selectCollection('info');
        $result = $collection->findOne([
            'key' => 'start_time'
        ]);

        // 组织响应数据
        $result = [
            'status' => 'success',
            'start_time' => $result['value']
        ];
        return $response->withJson($result);
    });

    $app->get('/overview', function (Request $request, Response $response, $args) {
        // 获取宏观数据
        $db = new MongoDB\Database($this->get('mongodb_client'), $this->get('MongoDB')['db']);
        $collection = $db->selectCollection('log');
        $select_result = $collection->findOne(
            [],
            [
                'projection' => [
                    '_id' => 0,
                    'time' => 0,
                    'timestamp' => 0
                ],
                'sort' => ['timestamp' => -1]
            ]
        );
        if ($select_result) {
            $result = (array)$select_result->getArrayCopy();
        } else {
            goto Bad_request;
        }

        $collection = $db->selectCollection('info');
        $Info_key = $this->get('Info_key');
        foreach ($Info_key as $item) {
            $select_result = $collection->findOne(
                ['key' => $item]
            );
            $result[$item] = $select_result['value'];
        }

        // 将字典数据写入请求响应
        $result = [
            'status' => 'success',
            'data' => $result
        ];
        return $response->withJson($result);
        // 异常访问出口
        Bad_request:
        return \WolfBolin\Slim\HTTP\Bad_request($response);
    });

    $app->get('/memory', function (Request $request, Response $response, $args) {
        # 计算访问参数
        $timestamp = time();
        $time_slot = $request->getQueryParam('time', '');
        if ($time_slot === '7days') {
            $begin_time = $timestamp - 86400 * 7;
        } else if ($time_slot === '30days') {
            $begin_time = $timestamp - 86400 * 7;
        } else {
            goto Bad_request;
        }

        // 获取运行时间
        $db = new MongoDB\Database($this->get('mongodb_client'), $this->get('MongoDB')['db']);
        $collection = $db->selectCollection('log');
        $memory_result = $collection->find(
            [
                'timestamp' => ['$gt' => $begin_time],
            ],
            [
                'projection' => [
                    '_id' => 0,
                    'mem' => 1,
                    'time' => 1,
                    'timestamp' => 1
                ]
            ]
        );
        $memory_result = (array)$memory_result->toArray();
        $result = [];
        foreach ($memory_result as $item) {
            $result [] = [
                'num' => $item['mem']['num'],
                'per' => $item['mem']['per'],
                'time' => $item['time'],
                'timestamp' => $item['timestamp']
            ];
        }

        // 将字典数据写入请求响应
        $result = [
            'status' => 'success',
            'data' => $result
        ];
        return $response->withJson($result);
        // 异常访问出口
        Bad_request:
        return \WolfBolin\Slim\HTTP\Bad_request($response);
    });

    $app->get('/cpu', function (Request $request, Response $response, $args) {
        # 计算访问参数
        $timestamp = time();
        $time_slot = $request->getQueryParam('time', '');
        if ($time_slot === '7days') {
            $begin_time = $timestamp - 86400 * 7;
        } else if ($time_slot === '30days') {
            $begin_time = $timestamp - 86400 * 7;
        } else {
            goto Bad_request;
        }

        // 获取运行时间
        $db = new MongoDB\Database($this->get('mongodb_client'), $this->get('MongoDB')['db']);
        $collection = $db->selectCollection('log');
        $memory_result = $collection->find(
            [
                'timestamp' => ['$gt' => $begin_time],
            ],
            [
                'projection' => [
                    '_id' => 0,
                    'cpu' => 1,
                    'time' => 1,
                    'timestamp' => 1
                ]
            ]
        );
        $memory_result = (array)$memory_result->toArray();
        $result = [];
        foreach ($memory_result as $item) {
            $result [] = [
                'cpu' => $item['cpu'],
                'time' => $item['time'],
                'timestamp' => $item['timestamp']
            ];
        }

        // 将字典数据写入请求响应
        $result = [
            'status' => 'success',
            'data' => $result
        ];
        return $response->withJson($result);
        // 异常访问出口
        Bad_request:
        return \WolfBolin\Slim\HTTP\Bad_request($response);
    });
})->add(\WolfBolin\Slim\Middleware\http_cors());
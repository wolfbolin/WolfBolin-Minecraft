<?php
/**
 * Created by PhpStorm.
 * User: wolfbolin
 * Date: 2019/3/28
 * Time: 23:13
 */

use \Slim\App;
use \Slim\Http\Request;
use \Slim\Http\Response;

$app->redirect('/', '/hello-mc', 301);

$app->get('/hello-mc', function (Request $request, Response $response) {
    $result = ['status' => 'success', 'info' => 'Hello, Minecraft!'];
    return $response->withJson($result);
});

$app->group('/backup', function (App $app) {
    $app->get('/list', function (Request $request, Response $response) {
        // 获取对象储存信息
        $client = $this->get('cos_client');
        try {
            $file_list = $client->listObjects([
                'Bucket' => $this->get('COS')['Bucket'],
                'Prefix' => 'Minecraft/world/world'
            ])->toArray();
        } catch (\Exception $e) {
            $sentry = $this->get('sentry_client');
            $sentry->captureException($e, array(
                'extra' => array(
                    'URL' => $request->getUri(),
                    'Method' => $request->getMethod(),
                    'Body' => $request->getBody()
                ),
                'tags' => array(
                    'version' => $this['Version']
                )
            ));
            goto Bad_request;
        }

        // 数据获取成功响应请求
        $result = [
            'status' => 'success',
            "info" => [
                'msg' => "数据获取成功",
                'count' => 0
            ],
            'data' => []
        ];
        if (isset($file_list['Contents'])) {
            foreach ($file_list['Contents'] as $item) {
                $file_info = $this->get('File_info');
                $file_info['name'] = substr($item['Key'], 16);
                $file_info['time'] = substr(str_replace('T', ' ', $item['LastModified']), 0, -5);
                $file_info['size'] = round(intval($item['Size']) / 1000000, 2);
                $file_info['class'] = strtolower($item['StorageClass']);
                $result['data'] [] = $file_info;
                $result['info']['count']++;
            }
        }

        // 将字典数据写入请求响应
        return $response->withJson($result);
        // 异常访问出口
        Bad_request:
        return \WolfBolin\Slim\HTTP\Bad_request($response);
    });

    $app->get('/add', function (Request $request, Response $response) {
        // 生成压缩包
        $zip_name = 'world-' . time() . '.zip';
        $zip_path = __DIR__ . '/../temp/';
        $data_path = __DIR__ . '/../data/';
        $world_path = $data_path . 'world/';
        // 递归添加文件
        $zip = new ZipArchive();
        $result = $zip->open($zip_path . $zip_name, ZipArchive::CREATE);
        if ($result === TRUE) {
            \WolfBolin\Util\Zip\addFileToZip($zip, $world_path, strlen($data_path));
            $zip->close(); //关闭处理的zip文件
        } else {
            $sentry = $this->get('sentry_client');
            $sentry->captureMessage("数据压缩过程出现异常", array(
                'extra' => array(
                    'URL' => $request->getUri(),
                    'Method' => $request->getMethod(),
                    'Body' => $request->getBody()
                ),
                'tags' => array(
                    'version' => $this['Version']
                )
            ));
            goto Bad_request;
        }

        // 上传至COS
        try {
            $cos_client = $this->get('cos_client');
            $result = $cos_client->putObject([
                'Bucket' => $this->get('COS')['Bucket'],
                'Key' => 'Minecraft/world/' . $zip_name,
                'Body' => fopen($zip_path . $zip_name, 'rb')
            ]);

        } catch (\Exception $e) {
            $sentry = $this->get('sentry_client');
            $sentry->captureException($e, array(
                'extra' => array(
                    'URL' => $request->getUri(),
                    'Method' => $request->getMethod(),
                    'Body' => $request->getBody()
                ),
                'tags' => array(
                    'version' => $this['Version']
                )
            ));
            goto Bad_request;
        }

        // 文件上传成功，删除文件缓存
        unlink($zip_path . $zip_name);

        // 将字典数据写入请求响应
        $result = [
            'status' => 'success',
            'ETag' => substr($result['ETag'], 1, -1),
            'URL' => $result['ObjectURL'],
            'file_name' => $zip_name
        ];
        return $response->withJson($result);
        // 异常访问出口
        Bad_request:
        return \WolfBolin\Slim\HTTP\Bad_request($response);

    })->add(\WolfBolin\Slim\Middleware\x_auth_token());
});





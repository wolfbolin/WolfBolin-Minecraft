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
            $sentry->captureException($e);
            goto Server_error;
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
                $file_info['index'] = substr($item['Key'], 16, -4);
                $file_info['name'] = substr($item['Key'], 16);
                $file_info['time'] = substr(str_replace('T', ' ', $item['LastModified']), 0, -5);
                $file_info['size'] = round(intval($item['Size']) / 1000000, 2) . 'MB';
                $file_info['class'] = strtolower($item['StorageClass']) == 'standard' ? '标准储存' : '低频储存';
                $result['data'] [] = $file_info;
                $result['info']['count']++;
            }
        }

        // 将字典数据写入请求响应
        return $response->withJson($result);
        // 异常访问出口
        Server_error:
        return \WolfBolin\Slim\HTTP\Server_error($response);
    });

    $app->get('/{name:world-[0-9]{10}}', function (Request $request, Response $response, $args) {
        // 获取访问参数
        $file_name = $args['name'];

        try {
            $cos_client = $this->get('cos_client');
            $command = $cos_client->getCommand('getObject', array(
                'Bucket' => $this->get('COS')['Bucket'],
                'Key' => 'Minecraft/world/' . $file_name . '.zip',
                'Body' => ''
            ));
            $signedUrl = $command->createPresignedUrl($this->get('COS')['Signature-TTL']);
        } catch (\Exception $e) {
            $sentry = $this->get('sentry_client');
            $sentry->captureException($e);
            goto Server_error;
        }

        // 将字典数据写入请求响应
        $result = [
            'status' => 'success',
            'ttl' => substr($this->get('COS')['Signature-TTL'], 1),
            'url' => $signedUrl,
        ];
        return $response->withJson($result);
        // 异常访问出口
        Server_error:
        return \WolfBolin\Slim\HTTP\Server_error($response);
    })->add(\WolfBolin\Slim\Middleware\x_auth_token());

    $app->post('/world', function (Request $request, Response $response) {
        // 生成压缩包
        $zip_path = $this->get('Temp_file_path');
        $zip_name = 'world-' . time() . '.zip';
        $data_path = $this->get('Minecraft_path');
        $world_path = $data_path . 'world/';

        // 递归添加文件
        $zip = new ZipArchive();
        $result = $zip->open($zip_path . $zip_name, ZipArchive::CREATE);
        if ($result === TRUE && file_exists($zip_path)) {
            // 验证数据文件路径
            if (file_exists($world_path)) {
                // 添加World路径
                \WolfBolin\Util\Zip\addFileToZip($zip, $world_path, strlen($data_path));
                // 添加properties文件
                $zip->addFile($data_path . 'server.properties', 'server.properties');
                $zip->close();
            } else {
                $sentry = $this->get('sentry_client');
                $sentry->captureMessage("数据文件不存在");
                goto Server_error;
            }
        } else {
            $sentry = $this->get('sentry_client');
            $sentry->captureMessage("数据压缩过程出现异常");
            goto Server_error;
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
            $sentry->captureException($e);
            goto Server_error;
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
        Server_error:
        return \WolfBolin\Slim\HTTP\Server_error($response);

    })->add(\WolfBolin\Slim\Middleware\x_auth_token());

    $app->delete('/{name:world-[0-9]{10}}', function (Request $request, Response $response, $args) {
        // 获取访问参数
        $file_name = $args['name'];

        // 从COS中删除
        try {
            $cos_client = $this->get('cos_client');
            $result = $cos_client->deleteObject([
                'Bucket' => $this->get('COS')['Bucket'],
                'Key' => 'Minecraft/world/' . $file_name . '.zip',
            ])->toArray();
        } catch (\Exception $e) {
            $sentry = $this->get('sentry_client');
            $sentry->captureException($e);
            goto Server_error;
        }

        // 将字典数据写入请求响应
        $result = [
            'status' => 'success',
            'operate' => $result['RequestId']
        ];
        return $response->withJson($result);
        // 异常访问出口
        Server_error:
        return \WolfBolin\Slim\HTTP\Server_error($response);
    })->add(\WolfBolin\Slim\Middleware\x_auth_token());


})->add(\WolfBolin\Slim\Middleware\http_cors());





<?php
/**
 * Created by PhpStorm.
 * User: wolfbolin
 * Date: 2019/3/28
 * Time: 23:14
 */

namespace WolfBolin\Slim\Middleware;

use \Slim\Http\Request;
use \Slim\Http\Response;

function x_auth_token() {
    $result = function (Request $request, Response $response, $next) {
        $auth_token = $request->getHeader('X-Auth-Token');

        if (empty($auth_token) || count($auth_token) > 1 || !is_string($auth_token[0])) {
            return \WolfBolin\Slim\HTTP\Unauthorized($response);
        }

        // 处理Token信息
        if ($auth_token[0]==$this->get('Access_token')) {
            return $next($request, $response);
        } else {
            return \WolfBolin\Slim\HTTP\Unauthorized3($response);
        }
    };
    return $result;
}

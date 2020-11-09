<?php

use Mix\Http\Message\Response;
use App\Common\Helpers\ResponseHelper;

/**
 * 用户助手函数
 */

/**
 * 获取全局配置对象
 * @return \Noodlehaus\Config
 */
function config()
{
    return $GLOBALS['config'];
}

/**
 * json 输出格式化函数
 *
 * @param \Mix\Http\Message\Response $response
 * @param string $code
 * @param string $msg
 * @param array $data
 * @return Response
 */
function json_response(Response $response, $data = [], string $msg = 'OK', string $code = 0)
{
    $content = ['status' => $code, 'msg' => $msg, 'data' => $data];
    return ResponseHelper::json($response, $content);      
}
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
 * @param array $data
 * @param string $code
 * @param int $msg
 * @return Response
 */
function json_response(Response $response, $data = null, string $msg = 'OK', int $code = 0)
{
    $content = ['status' => $code, 'msg' => $msg, 'data' => $data];
    return ResponseHelper::json($response, $content);      
}
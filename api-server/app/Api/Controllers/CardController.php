<?php

namespace App\Api\Controllers;

use App\Api\Models\ProfileModel;
use Mix\Http\Message\Response;
use Mix\Http\Message\ServerRequest;

/**
 * Class UserController
 * @package App\Api\Controllers
 * @author liu,jian <coder.keda@gmail.com>
 */
class CardController
{

    /**
     * 名片详情
     * @param ServerRequest $request
     * @param Response $response
     * @return Response
     */
    public function details(ServerRequest $request, Response $response)
    {
        try {
            $payload = $request->getContext()->value('payload');
            $uid = $payload["uid"];
        } catch (\InvalidArgumentException $th) {
            $uid = 1;
        }

        $list = (new ProfileModel)->whereGet([['user_id', '=', $uid]]);

        $desc = $request->getAttribute('desc', 0); // 是否要对描述按换行符分割
        $app_url = getenv('APP_URL');

        // 处理信息中特别的记录 
        $list = array_map(function ($item) use ($desc, $app_url) {
            if ($desc && $item["key"] == "description") {
                $value = explode("\n", $item["value"]);
                $item["value"] = array_filter($value);
            }
            if ($desc && $item["key"] == "avatar") {
                $item["value"] =  $app_url.$item["value"];
            }
            if ($item["key"] == "tags") {
                $item["value"] = explode(",", $item["value"]);
            }
            return $item;
        }, $list);
        
        $data = array_column($list, 'value', 'key');

        return json_response($response, $data, 'OK');
    }
    

    /**
     * 更新名片
     * @param ServerRequest $request
     * @param Response $response
     * @return Response
     */
    public function update(ServerRequest $request, Response $response)
    {

        try {
            $params = $request->getAttributes();

            $payload = $request->getContext()->value('payload');
            $uid = $payload["uid"];
            $profileModel = new ProfileModel();

            foreach ($params as $key => $value) {
                if ($key == "tags" && $value) {
                    $value = implode(",", $value); 
                } 
                $profileModel->update([['user_id', '=', $uid], ['`key`', '=', $key]], ['value' => $value]);
            }

            return json_response($response, null, 'OK');
        } catch (\Throwable $th) {
            return json_response($response, null, $th->getMessage(), -1);
        }
    }
}

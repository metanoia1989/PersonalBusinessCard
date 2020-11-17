<?php

namespace App\Api\Controllers;

use App\Common\Helpers\ResponseHelper;
use App\Api\Forms\UserForm;
use App\Api\Models\UserModel;
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
        return json_response($response, null, 'OK');
    }
    

    /**
     * 更新名片
     * @param ServerRequest $request
     * @param Response $response
     * @return Response
     */
    public function update(ServerRequest $request, Response $response)
    {
        return json_response($response, null, 'OK');
    }
}

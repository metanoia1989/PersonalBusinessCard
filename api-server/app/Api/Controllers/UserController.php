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
class UserController
{

    /**
     * Create
     * @param ServerRequest $request
     * @param Response $response
     * @return Response
     */
    public function create(ServerRequest $request, Response $response)
    {
        // 使用表单验证器
        $form = new UserForm($request->getAttributes());
        $form->setScenario('create');
        if (!$form->validate()) {
            return json_response($response, null, $form->getErrors(), 1);
        }

        // 执行保存数据库
        (new UserModel())->add($request->getAttributes());

        // 响应
        return json_response($response, null, 'OK');
    }

    /**
     * 更新用户信息
     *
     * @param ServerRequest $request
     * @param Response $response
     * @return Response
     */
    public function update(ServerRequest $request, Response $response)
    {
        // 使用表单验证器
        $form = new UserForm($request->getAttributes());
        $form->setScenario('update');
        if (!$form->validate()) {
            return json_response($response, null, $form->getErrors(), 1);
        }

        // 执行保存数据库
        (new UserModel())->update(['id', '=', ''], $request->getAttributes());

        // 响应
        return json_response($response, null, 'OK');
    }

    /**
     * 用户登录
     *
     * @param ServerRequest $request
     * @param Response $response
     * @return Response
     */
    public function login(ServerRequest $request, Response $response)
    {
        // 使用表单验证器
        $form = new UserForm($request->getAttributes());
        $form->setScenario('login');
        if (!$form->validate()) {
            return json_response($response, null, $form->getErrors(), 1);
        }

        // 执行保存数据库
        (new UserModel())->whereFirst([
            ['username', '=', $form->username], 
            ['p']
        ]);

        // 响应
        return json_response($response, null, 'OK');
    }

    

}

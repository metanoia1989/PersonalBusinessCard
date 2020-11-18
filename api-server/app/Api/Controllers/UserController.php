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
            return json_response($response, $form->getErrors(), "参数验证失败", 1);
        }

        $userModel = new UserModel();
         
        $user = $userModel->whereFirst([['username', '=', $form->username]]);
        if ($user) {
            return json_response($response, null, '创建失败：已存在此用户', -1);
        }

        // 执行保存数据库
        $data = $request->getAttributes();
        $data["password"] = md5($data["password"]);
        if ($userModel->add($data)) {
            return json_response($response, null, 'OK');
        } else {
            return json_response($response, null, '创建失败，未知', -1);
        }
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
            return json_response($response, $form->getErrors(), "参数验证失败", 1);
        }

        // 执行保存数据库
        (new UserModel())->update(['id', '=', ''], $request->getAttributes());

        // 响应
        return json_response($response, null, 'OK');
    }

}

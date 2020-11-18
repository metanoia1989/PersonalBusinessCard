<?php

namespace App\Api\Controllers;

use App\Api\Forms\UserForm;
use App\Api\Models\UserModel;
use Mix\Http\Message\Response;
use Mix\Http\Message\ServerRequest;

/**
 * Class UserController
 * @package App\Api\Controllers
 * @author liu,jian <coder.keda@gmail.com>
 */
class AuthController
{
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
            return json_response($response, $form->getErrors(), "参数验证失败", 1);
        }

        $user = (new UserModel())->whereFirst([
            ['username', '=', $form->username], 
            ['password', '=', md5($form->password)]
        ]);
        if (empty($user)) {
            return json_response($response, null, '用户不存在', -1);
        }

        // 生成token
        $time = time();
        $auth = context()->get('auth');
        $payload = [
            "iss" => 'http://card.anzhuoxfpx.com', // 签发人
            "iat" => $time, // 签发时间
            "exp" => $time + 3600 * 24, // 过期时间
            "uid" => $user["id"]
        ];
        $accessToken = $auth->createToken($payload);

        $data = [ "token" => $accessToken ];
        return json_response($response, $data, 'OK');
    }
    
    /**
     * 获取用户个人信息
     *
     * @param ServerRequest $request
     * @param Response $response
     * @return Response
     */
    public function me(ServerRequest $request, Response $response)
    {
        $payload = $request->getContext()->value('payload');
        $uid = $payload["uid"];
        $user = (new UserModel())->get($uid);
        if (isset($user['avatar']) && !empty($user['avatar'])) {
            $user['avatar'] = getenv("APP_URL").$user['avatar'];        
        }
        return json_response($response, $user, 'OK');
    }

    /**
     * 注销登录
     *
     * @param ServerRequest $request
     * @param Response $response
     * @return Response
     */
    public function logout(ServerRequest $request, Response $response)
    {
        return json_response($response, null, 'OK');
    }

}

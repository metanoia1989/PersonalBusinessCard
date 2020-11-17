<?php

namespace App\Api\Forms;

use Mix\Validate\Validator;

/**
 * Class UserForm
 * @package App\Api\Forms
 * @author liu,jian <coder.keda@gmail.com>
 */
class UserForm extends Validator
{

    /**
     * 名称
     * @var string
     */
    public $name;

    /**
     * 年龄
     * @var int
     */
    public $age;

    /**
     * 邮箱
     * @var string
     */
    public $email;

    /**
     * 规则
     * @return array
     */
    public function rules()
    {
        return [
            'username'  => [ 'string', 'maxLength' => 25, 'filter' => ['trim'] ],
            'mobile'   => [ 'integer', 'unsigned' => true, 'length' => 11, 'filter' => ['trim'] ],
            'password' => [ 'string', 'minLength' => 6, 'maxLength' => 30 ],  
        ];
    }

    /**
     * 场景
     * @return array
     */
    public function scenarios()
    {
        return [
            'create' => ['required' => ['username', 'password'], 'optional' => ['mobile']],
            'update' => ['required' => ['username'], 'optional' => ['mobile', 'password']],
            'login' => ['required' => ['username', 'password']],
        ];
    }

    /**
     * 消息
     * @return array
     */
    public function messages()
    {
        return [
            'username.required'  => '用户名不能为空.',
            'username.maxLength' => '用户名最多不能超过25个字符.',
            'password.minLength'    => '密码不能小于6个字符.',
            'password.maxLength'    => '密码不能大于30个字符.',
            'password.required'  => '密码不能为空.',
            'mobile.integer'    => '手机号码必须为数字.',
            'mobile.length'    => '手机号码必须为11位.',
        ];
    }

}

<?php

namespace App\Api\Models;

/**
 * Class UserModel
 * @package App\Api\Models
 */
class UserModel extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'yang_user';

    /**
     * 获取指定ID的数据，联表查询头像
     *
     * @param integer $id
     * @param string $select
     * @return array
     */
    public function get(int $id, string $select = '*')
    {
        return $this->db
            ->table($this->table)
            ->leftJoin('yang_profile', [['yang_user.id', '=', 'yang_profile.user_id'], ['yang_profile.key', '=', '"avatar"']])
            ->where(['yang_user.id', '=', $id])
            ->select('yang_user.id,yang_user.username,yang_user.mobile,yang_profile.value as avatar')
            ->first();  
    }
}

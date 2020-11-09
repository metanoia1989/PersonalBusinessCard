<?php

namespace App\Api\Models;

use Mix\Database\Database;

/**
 * Class BaseModel
 *
 * @package App\Api\Models
 * @author liu,jian <coder.keda@gmail.com>
 */
class BaseModel
{
    /**
     * @var Database
     */
    public $db;

    /**
     * @var string
     */
    protected $table;
    

    /**
     * UserModel constructor.
     */
    public function __construct()
    {
        $this->db = context()->get('database');
    }

    /**
     * 设置模型表名
     *
     * @param string $table
     * @return void
     */
    protected function setTableName(string $table)
    {
        $this->table = $table;     
    }
    
    /**
     * 获取指定ID的数据
     *
     * @param integer $id
     * @return array
     */
    public function get(int $id)
    {
        return $this->db->table($this->table)->where(['id', '=', $id])->first();
    }

    public function whereFirst(array $where, $orderBy = null, $select = '*')
    {
        $query =  $this->db->table($this->table);
        $query->where($where);
        $query->select($select);
        if (!empty($orderBy)) {
            // 是否有多个orderBy条件
            if (is_array(current($orderBy))) {
                foreach ($orderBy as $item) {
                    $query->orderBy($item[0], $item[1]);
                }
            } else {
                $query->orderBy($orderBy[0], $orderBy[1]);
            }
        }
        $row = $query->first();
        return $row ? $row : [];
    }

    public function whereGet(array $where, $orderBy = null, $select = '*')
    {
        $query =  $this->db->table($this->table);
        $query->where($where);
        $query->select($select);
        if (!empty($orderBy)) {
            // 是否有多个orderBy条件
            if (is_array(current($orderBy))) {
                foreach ($orderBy as $item) {
                    $query->orderBy($item[0], $item[1]);
                }
            } else {
                $query->orderBy($orderBy[0], $orderBy[1]);
            }
        }
        $row = $query->get();
        return $row ? $row : [];
    }

    public function whereValue(array $where, string $name)
    {
        return $this->db->table($this->table)->where($where)->value($name);
    }

    public function whereColumn(array $where, string $name)
    {
        $data = $this->db->table($this->table)->where($where)->get();
        if ($data) {
            return array_column($data, $name);
        }
        return $data;
    }

    /**
     * 新增一条记录
     * @param array $item
     * @return bool|string
     */
    public function add($item)
    {
        $conn     = $this->db->insert($this->table, $item);
        $status   = $conn->execute();
        $insertId = $status ? $conn->getLastInsertId() : false;
        return $insertId;
    }
    
    /**
     * 更新数据
     *
     * @param array $where
     * @param array $set
     * @return boolean
     */
    public function update(array $where, array $set)
    {
        $conn = $this->db->update($this->table, $set, $where);
        $success = $conn->execute();
        return $success;
    }
}
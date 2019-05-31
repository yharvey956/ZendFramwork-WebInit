<?php
//fileDesc:OA班管理的数据模型
//author:zc
namespace Model\Admin;

use Zend\Db\Adapter\Adapter;
use Model\Base\BaseTableModel;

class Base extends BaseTableModel
{

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }

    public function testData()
    {
        $sql = "select * from user_info where user_id = :id";
        return $this->sqlexec($sql, ["id" => 2]);
    }

}

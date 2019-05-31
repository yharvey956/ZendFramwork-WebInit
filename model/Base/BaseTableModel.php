<?php

namespace Model\Base;

use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Sql;

class BaseTableModel
{
    protected $adapter = null;
    protected $ServiceLocator = null;
    protected $log = null;

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * @param    string    $sql
     * @param    $sqlArg    sql里面对应的参数
     * @return    如果是SELECT会返回数组，如果INSERT会返回插入完的生成的主键，update/delete返回TRUE/FALSE和MSG
     */
    public function sqlexec($sql, $sqlArg = array(),$time=null)
    {

        try {
            /* 数组与字符串转换 ,由于ZF使用了PDO，但PDO对IN的参数绑定不太友善，如果想用ZF的预处理与变量绑定，需要进行转换*/
            $newArg = array();
            $tmpWhereStr = null;
            foreach ($sqlArg as $argKey => $argVal) {
                if (is_array($argVal)) {
                    foreach ($argVal as $tkey => $tval){
                        $newArg[$argKey.$tkey] = $tval;
                        $tmpWhereStr = $tmpWhereStr." :".$argKey.$tkey.",";
                    }
                    unset($newArg[$argKey]);
                    $sql = str_replace(":$argKey", rtrim($tmpWhereStr, ","), $sql);
                } else {
                    $newArg[$argKey] = $argVal;
                }
            }

            // 替换/R/T/N
            $sql = str_replace(array(
                "\r\n",
                "\r",
                "\n",
                "\t"
            ), " ", $sql);

            // 检查冒号与数组数量的对应关系


            if (strstr($sql, 'select') || strstr($sql, 'SELECT')) {
                $outSql  = preg_replace('# #','',$sql);
                if (substr_count($outSql, '=:') > count($newArg)) {
                    return array("result" => false, "msg" => "SQLEXEC:执行查询操作时，参数sqlArg数组内参数要多于SQL语句参数" . $sql); // 传入的sqlArg数组的参数数量应该多于SQL语句参数
                }
            } else {
                if (substr_count($sql, ':') != count($newArg)) {
                    return array("result" => false, "msg" => "SQLEXEC:执行插入、删除、更新操作时，参数sqlArg数组与SQL语句参数要一致，不能多也不能少" . $sql);
                }
            }

            $statement = $this->adapter->createStatement($sql);
            $result = $statement->execute($newArg);

            preg_match_all('/([a-zA-Z]+)/', $sql, $match);
            $sql_str_6 = strtolower($match[0][0]);
            $arrinfo = [];
            switch ($sql_str_6)
            {
                case "insert":
                    $arrinfo =  array('result' => true, 'msg' => $result->getGeneratedValue());break;
                case "delete":
                case "update":
                    $arrinfo = array('result' => true, 'msg' => $result->getAffectedRows());break;
                case "select":
                    $resultSet = new ResultSet();
                    $resultSet->initialize($result);
                    $arrinfo = array('result' => true, 'msg' => $resultSet->toArray());break;
                default:
                    if (strstr($sql, 'foreign_key_checks') || strstr($sql, 'FOREIGN_KEY_CHECKS')) {
                        $arrinfo = array('result' => true, 'msg' => '');
                    }else{
                        $arrinfo = array('result' => false, 'msg' => "sqlexec 执行出错，相应的SQL：" . $statement->getSql());
                    }
            }
            return $arrinfo;
        } catch (\Exception $e) {
            return array("result" => false, "msg" => "SQLEXEC:异常" . $e->getMessage() . $sql);
        }
    }

    /**
     * 生成sql的部分语句
     * 主要用于判断传入参数是否为空，然后生成对应的语句,减少重复语句
     */
    public function generateSql($arrValue,$sqlkey,$key,$tableName)
    {
        $sql = "";
        if($arrValue=="%%")
        {
            $sql = "($tableName.$sqlkey is null or $tableName.$sqlkey like :$key) ";
        }
        else if($arrValue != null)
        {
            $sql = "($tableName.$sqlkey like :$key) ";
        }
        else
        {
            $sql = "($tableName.$sqlkey is null) ";
        }
        return $sql;
    }
}



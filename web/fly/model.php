<?php
/**
 * X Frame 数据库操作类
 */

namespace fly;

abstract class Model {

    public static $pdo;

    public function __construct() {
        self::initPDO();
    }

    /**
     * 初始化PDO
     */
    private static function initPDO() {
        if (empty(self::$pdo)) {
            
            self::$pdo = new \PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME
                    , DB_USER
                    , DB_PASS
                    , array(\PDO::ATTR_PERSISTENT => false));

//            self::$pdo->exec('SET NAMES UTF8;');
        }
    }
    
    private function getTableName() {
//        $table = get_called_class();
//        $slashPos = strstr($table, "\\");
//        if($slashPos !== FALSE) {
//            $table = substr($table, $slashPos + 1);
//        }
        
        $name = explode("\\", get_called_class());
        return end($name);
        //return (new \ReflectionClass($this))->getShortName();
    }

    /**
     * 插入数据
     * @param type $data
     * @return type
     */
    protected function _insert($data = null) {
        unset($this->error);
        $table = $this->getTableName();
        
        $rows = 1;
        // 如果传入的是对象, 获取对象所有设值的属性
        if ($data === null) {
            $data = $this;
            $keys = array();
            $vals = array();
            $vars = get_object_vars($data);
            foreach ($vars as $key => $value) {
                if (isset($value)) {
                    $keys[] = $key;
                    $vals[] = $value;
                }
            }
        } elseif (is_array($data)) {
            // 如果是数组, 判断是一维数组还是二维数组, 二维数组可以插入多条
            if (is_array($data[0])) {
                $keys = array_keys($data[0]);
                $vals = self::flattern($data);
                $rows = count($data);
            } else {    // 一维数组
                $keys = array_keys($data);
                $vals = array_values($data);
            }

        } else {
            return 0;
        }

        $q = array_fill(0, count($keys), '?');
        $values = '(' . join(',', $q) . ')';
        // 多行处理
        if ($rows > 1) {
            $q = array_fill(0, $rows, $values);
            $values = join(',', $q);
        }

        $fields = join(',', $keys);
        $sql = "INSERT INTO $table ($fields) VALUES $values";
        $stmt = self::$pdo->prepare($sql);

        $stmt->execute($vals);
        
        if($stmt->errorCode() !== \PDO::ERR_NONE) {
            $this->error = $stmt->errorInfo();
        }
        
        return $stmt->rowCount();
    }

    /**
     * 通用更新方法
     * @param type $obj
     * @param array $primary_key
     * @return boolean
     */
    protected function _update(array $primary_key) {
        $table = $this->getTableName();

        unset($this->error);
        
        $keys = array();
        $params = array();
        $vars = get_object_vars($this);
        foreach ($vars as $key => $value) {
            if (isset($value)) {
                $keys[] = $key . '=?';
                $params[] = $value;
            }
        }
        
        if(empty($keys)) {
            return false;
        }
        
        $fields = join(',', $keys);
        $sql = "UPDATE $table SET $fields";
        if(count($primary_key) > 0) {
            $wheres = array();
            foreach ($primary_key as $key => $value) {
                $wheres[] = $key . '=?';
                $params[] = $value;
            }
            
            $sql .= ' WHERE ' . join(' AND ', $wheres);
        }
        
        $stmt = self::$pdo->prepare($sql);
        
        $stmt->execute($params);
        
        if($stmt->errorCode() !== \PDO::ERR_NONE) {
            $this->error = $stmt->errorInfo();
        }
        
        return $stmt->rowCount();
    }
    
    protected function _delete(array $primary_key) {
        $table = $this->getTableName();
        $params = array();
        $sql = "DELETE FROM $table";
        if(count($primary_key) > 0) {
            $wheres = array();
            foreach ($primary_key as $key => $value) {
                $wheres[] = $key . '=?';
                $params[] = $value;
            }
            
            $sql .= ' WHERE ' . join(' AND ', $wheres);
        }
        
        $stmt = self::$pdo->prepare($sql);
        
        $stmt->execute($params);
        return $stmt->rowCount();
    }

    /**
     * 将一个二维数组变成一位数组
     * @param array $array
     * @return type
     */
    private static function flattern(array $array) {
        $flattern = array();
        foreach ($array as $arr) {
            $flattern = array_merge($flattern, array_values($arr));
        }
        return $flattern;
    }

    /**
     * 将参数的数组映射到当前对象中, 如果当前对象不存在对应的属性, 则忽略
     * 此方法适用于将大量数据的 $_POST 参数赋值到当前对象中, 以进行插入
     * @param array $inputs 
     */
    public function map(array $inputs) {
        $properties = array_keys(get_object_vars($this));
        foreach ($properties as $key) {
            if (isset($inputs[$key])) {
                $this->$key = $inputs[$key];
            }
        }
    }

}

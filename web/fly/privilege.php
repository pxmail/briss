<?php

namespace fly;

class Privilege {

    /**
     * 默认权限, 一些不需要特别声明(公开)的功能, 使用此权限值
     * 这个值并不包括在权限数组中, 所有用户成功登录后将默认拥有此权限
     */
    const DEFAULT_PRIVILEGE = 0;

    private $privileges = [];

    function __construct() {

        $mathes = NULL;
        $pattern = '/^(\d+)[\t\s]+(.+)/';

        $file = fopen(FILE_PRIVILEGES, 'r');
        if (empty($file)) {
            return;
        }

        while (!feof($file)) {
            $line = fgets($file);
            if (preg_match($pattern, $line, $mathes) && count($mathes) === 3) {

                $this->privileges[$mathes[1]] = preg_replace( "/\r|\n/", "", $mathes[2] );
            }
        }
        fclose($file);
    }

    /**
     * 检查权限是否是预定义
     * 
     * @param $privilege 权限id        	
     * @return true / false
     */
    function isValid($privilege) {
        return array_key_exists($privilege, $this->privileges);
    }

    /**
     * 获取所有权限,返回到一个数组中
     * 
     * @return array 权限数组[['id'=>角色ID, 'name'=>角色名]]
     */
    function getAll() {
        return $this->privileges;
    }

    /**
     * 根据ID获取角色名称
     * 
     * @param int $id
     *        	角色ID
     * @return string 角色名称
     */
    function getName($id) {
        if (array_key_exists($id, $this->privileges)) {
            return $this->privileges[$id];
        }
    }

}

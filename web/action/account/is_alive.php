<?php
require_once 'fly/privilege.php';

use fly\Action;
use fly\Privilege;

class is_alive extends Action {

    public function getFilter() {
        
    }

    /**
     * 检查当前用户是否是活动状态(access token是否可用), 并返回access token 实效的时间(秒)
     *
     * @return 数组对象
     */
    public function exec(array $params = null) {
        $expire_in = intval($this->user->exp) - time();
        return [
            'expire_in' => $expire_in
        ];
    }

    public function getPrivilege() {
        return NULL;
    }

}
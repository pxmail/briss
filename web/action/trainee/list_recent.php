<?php

/*
 * (C) 2016 EZ Sport Ltd.
 */

/**
 * list_recent 列出近期活跃的用户，当天已经入场（包括训练完成）的运动员不在内
 *
 * @author xieyiming
 */
class list_recent extends fly\Action {
    public function exec(array $params = null) {
    	$trainee = new trainee();
    	if (empty($params['list_mine'])) {
    		$trainees = $trainee->listRecent();
    	} else {
    		$trainees = $trainee->listMyRecent($this->user->uid);
    	}
        
        return ['trainees' => $trainees];
    }

    public function getFilter() {
        $optional = array(
        	'list_mine' => FILTER_VALIDATE_BOOLEAN
        );
        
        return [INPUT_GET, null, $optional];
    }

    public function getPrivilege() {
        return 0;   // 默认权限，登录后访问
    }

}

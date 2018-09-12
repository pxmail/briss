<?php

require_once 'fly/jwt.php';
require_once 'fly/privilege.php';

use fly\Action;
use fly\JWT;
use fly\Privilege;

/**
 * 运动员登录
 * @author yangz
 *
 */
class login extends Action {

    public function getFilter() {
        $required = [
            'user' => FILTER_SANITIZE_STRING,
            'password' => FILTER_SANITIZE_STRING
        ];

        return [INPUT_POST, $required, null];
    }

    public function exec(array $params = null) {
        $trainee = new trainee();
        
        if (is_numeric(trim($params['user']))) {
        	$trainee->getByMobile($params['user']);
        } else {
        	$trainees = $trainee->getByName($params['user']);
        	$count = count($trainees);
        	
        	if ($count === 0) {
        		return 2080;
        	} else {
        		if ($count > 1) {
        			return 3030;
        		} else {
        			$trainee = (object)$trainees[0];
        		}
        	}
        }

        if (empty($trainee->id)) {
            return 2080;
        }

        $verify = password_verify($params['password'], trim($trainee->password));
        if ($verify === false) {
            return 2002;
        }

        if ($trainee->status === trainee::STATUS_NOT_REVIEWED) {
            return 2081;
        }

        $role = new role();
        $role->get(role::SPORT_CLIENT);

        $user = new stdClass();
        $user->uid = $trainee->id;
        $user->name = $trainee->name;
        $user->role_id = $trainee->role_id;
        $user->privileges = explode(',', $role->privilege);

        $access_token = JWT::encode($user);
        $expire_in = JWT::EXPIRE;

        return ['access_token' => $access_token, 'expire_in' => $expire_in];
    }

    public function getPrivilege() {
        return 30;
    }
    
}

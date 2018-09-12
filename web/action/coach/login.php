<?php
require_once 'fly/jwt.php';
require_once 'fly/privilege.php';

use fly\Action;
use fly\Privilege;
use fly\JWT;
use fly\Log;
/**
 * 教练员登录
 * @author yangz
 *
 */
class login extends Action {
	public function getFilter() {
		$required = [
			'name' => FILTER_SANITIZE_STRING,
			'password' => FILTER_SANITIZE_STRING
		];
		
		return [INPUT_POST, $required, null];
	}

	public function exec(array $params = null) {
		 $coach = new coach();
		 $result = $coach->getByName($params['name'], coach::REMOVED);
		 if (empty($result)) {
		 	return 2000;
		 }
		 if ($coach->status === coach::BLOCKED) {
		 	return 2001;
		 }
		 $verify = password_verify($params['password'], trim($coach->password));
		 if( $verify === false) {
		 	return 2002;
		 }
		 // 更新coach表登录时间
		 $coach->login_time = date('Y-m-d H:i:s');
		 $coach->update($coach->id);
		 
		 $role = new role();
		 $role->get($coach->role_id);

		 $obj = new stdClass();
		 $obj->uid = $coach->id;
		 $obj->name = $coach->name;
		 $obj->role_id = $coach->role_id;
		 $obj->privileges = explode(',', $role->privilege);
		 
		 $access_token = JWT::encode($obj);
         debug($access_token);
		 $expire_in = JWT::EXPIRE;
		 return ['access_token' => $access_token, 'expire_in' => $expire_in];
	}

	public function getPrivilege() {

	}
}

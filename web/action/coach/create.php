<?php
use fly\Action;
/**
 * 注册教练账户（只能超级教练员来操作）
 * @author yangz
 *
 */
class create extends Action {
	public function getFilter() {
		$required = array(
			'name' => FILTER_SANITIZE_STRING,
			'role_id' => FILTER_SANITIZE_NUMBER_INT,
			'password' => FILTER_SANITIZE_STRING
		);
		
		return [INPUT_POST, $required, null];
	}
	
	public function exec(array $params = null) {
		$coach = new coach();
		$coach->getByName($params['name'], coach::REMOVED);
		
		// 判断同名教练是存在
		if ($coach->id) {
			return 2050;
		}
		
		$coach->name = $params['name'];
		$coach->create_time = date('Y-m-d H:i:s');
		$coach->role_id = $params['role_id'];
		$coach->password = password_hash($params['password'], PASSWORD_DEFAULT);
		$id = $coach->create();
		
		return ['id' => $id];
	}
	
	public function getPrivilege() {
		return 10;
	}
}
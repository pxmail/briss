<?php
use fly\Action;
use fly\Privilege;

/**
 * 注册新教练
 * @author yangz
 *
 */
class register extends Action {
	public function getFilter() {
		$required = [
			'name' => FILTER_SANITIZE_STRING,
			'password' => FILTER_SANITIZE_STRING,
			'role_id' => FILTER_SANITIZE_NUMBER_INT
		];
		
		return [INPUT_POST, $required, null];
	}

	public function exec(array $params = null) {
		$coach = new coach();
		$result = $coach->getByName($params['name'], coach::REMOVED);

		if ($coach->name === $params['name']) {
			return 2050;
		}
		
		// 检查role_id是否是系统预设值
		$role_id = $params['role_id'];
		if ($role_id != role::COMMON_COACH && $role_id != role::SUPER_COACH) {
			return 2051;
		}
		
		$coach->name = $params['name'];
		$coach->password = password_hash($params['password'], PASSWORD_DEFAULT);
		$coach->status = coach::NORMAL;
		$coach->create_time = date('Y-m-d H:i:s');
		$coach->login_time = "0000-00-00 00:00:00";
		$coach->role_id = $params['role_id'];
		$coach->create();
		
		return ['success' => true];
	}

	public function getPrivilege() {

	}
}
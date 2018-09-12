<?php
use fly\Action;
/**
 * 更新教练个人信息
 * @author yangz
 *
 */
class update extends Action {
	public function getFilter() {
		$required = array(
			'coach_id' => FILTER_SANITIZE_NUMBER_INT,
			'role_id' => FILTER_SANITIZE_NUMBER_INT
		);
		
		return [INPUT_POST, $required, null];
	}
	
	public function exec(array $params = null) {
		$coach = new coach();
		// 检查role_id是否是系统预设值
		$role_id = $params['role_id'];
		if ($role_id != role::COMMON_COACH && $role_id != role::SUPER_COACH) {
			return 2051;
		}
		$coach->role_id = $role_id;
		$coach->update($params['coach_id']);
		
		return ['coach_id' => $params['coach_id']];
	}
	
	public function getPrivilege() {
		return 10;
	}
}
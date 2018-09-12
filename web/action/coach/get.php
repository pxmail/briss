<?php
use fly\Action;
/**
 * 获取教练详细信息
 * @author yangz
 *
 */
class get extends Action {
	public function getFilter() {
		$required = array(
			'coach_id' => FILTER_SANITIZE_NUMBER_INT		
		);
		
		return [INPUT_GET, $required, null];
	}
	
	public function exec(array $params = null) {
		$coach = new coach();
		$coach->get($params['coach_id']);
		
		unset($coach->password);
		
		return ['coach' => $coach];
	}
	
	public function getPrivilege() {
		return 20;
	}
}
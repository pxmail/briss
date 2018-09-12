<?php
use fly\Action;
/**
 * 删除运动项目
 * @author yangz
 *
 */
class delete extends Action {
	public function getFilter() {
		$required = array(
			'sport_id' => FILTER_SANITIZE_NUMBER_INT
		);
		
		return [INPUT_POST, $required, null];
	}
	
	public function exec(array $params = null) {
		$sport = new sport();
		$sport->get($params['sport_id']);
		
		if ($sport->trainee_num) {
			return 3041;		
		}
		$result = $sport->delete($params['sport_id']);
		$result = empty($result) ? false : true;
		
		return ['success' => $result];
	}
	
	public function getPrivilege() {
		return 10;
	}
}
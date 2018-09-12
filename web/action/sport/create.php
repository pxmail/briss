<?php
use fly\Action;
/**
 * 创建运动项目
 * @author yangz
 *
 */
class create extends Action {
	public function getFilter() {
		$required = array(
			'sport' => FILTER_SANITIZE_STRING
		);
		
		return [INPUT_POST, $required, null];
	}
	
	public function exec(array $params = null) {
		$sport = new sport();
		$obj = $sport->getByName($params['sport']);
		
		if ($obj) {
			return 3040;
		}
		$sport->name = $params['sport'];
		$sport->trainee_num = 0;
		$sport_id = $sport->create();
		
		return ['sport_id' => $sport_id];
	}
	
	public function getPrivilege() {
		return 10;
	}
}
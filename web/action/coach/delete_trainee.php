<?php
use fly\Action;
/**
 * 教练删除运动员
 * @author yangz
 *
 */
class delete_trainee extends Action {
	public function getFilter() {
		$required = array(
			'trainee_id' => FILTER_SANITIZE_NUMBER_INT	
		);
		
		return [INPUT_POST, $required, null];
	}
	
	public function exec(array $params = null) {
		$trainee = new trainee();
		$trainee->get($params['trainee_id']);
		
		if (empty($trainee->id)) {
			return 2080;
		}
		
		if ($trainee->day) {
			$result = false;
		} else {
			$trainee->delete($params['trainee_id']);
			$result = true;
			//修改基地运动员数目信息
			$base = new base();
			$base->get($trainee->base_id);
			$base->trainee_num = $base->trainee_num - 1;
		}
		
		return ['success' => $result];
	}
	
	public function getPrivilege() {
		return 20;
	}
}
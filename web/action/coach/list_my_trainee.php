<?php
use fly\Action;
/**
 * 获取属于当前登录教练员的所有运动员
 * @author yangz
 *
 */
class list_my_trainee extends Action {
	public function getFilter() {
		$optional = array(
			'simple' => FILTER_SANITIZE_STRING
		);
		
		return [INPUT_GET, null, $optional];
	}
	   
	public function exec(array $params = null) {
		if (empty($params['simple'])) {
			$trainee = new trainee();
			$trainees = $trainee->listMyTrainee($this->user->uid);
				
			return ['trainees' => $trainees];
		} else {
			$coach_trainee = new coach_trainee();
			$trainee_ids = $coach_trainee->getTrainees($this->user->uid);
				
			return ['trainee_ids' => $trainee_ids];
		}	
	}
	
	public function getPrivilege() {
		return NULL;
	}
}
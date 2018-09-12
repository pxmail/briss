<?php
use fly\Action;
/**
 * 教练移除自己的运动员
 * @author yangz
 *
 */
class unlink_trainee extends Action {
	public function getFilter() {
		$optional = array(
			'sport_id' => FILTER_SANITIZE_NUMBER_INT,
			'trainee_id' => FILTER_SANITIZE_NUMBER_INT
		);
	
		return [INPUT_POST, null, $optional];
	}
	
	public function exec(array $params = null) {
		$coach_sport = new coach_sport();
		$coach_trainee = new coach_trainee();
		$trainee = new trainee();
		
		if (!empty($params['sport_id'])) {
			//删除教练与该运动项目之间的关系
			$coach_sport->delete($params['sport_id'], $this->user->uid);
			//删除教练与运动员的关系
			$coach_trainee->deleteBatch($this->user->uid, $params['sport_id']);
		}
		
		if (!empty($params['trainee_id'])) {
			//删除教练与该运动员的关系
			$coach_trainee->delete($this->user->uid, $params['trainee_id']);
			$trainee->get($params['trainee_id']);
			//删除教练与该运动员所属运动项目的关系
			$coach_sport->delete($trainee->sport_id, $this->user->uid);
		}
		
		return ['success' => true];
	}
	
	public function getPrivilege() {
		return 20;
	}
}
<?php
use fly\Action;

/**
 * 更新教练员状态
 * @author yangz
 *
 */
class update_status extends Action {
	public function getFilter() {
		$required = [
			'coach_id' => FILTER_SANITIZE_NUMBER_INT,
			'status' => FILTER_SANITIZE_STRING
		];
		
		return [INPUT_POST, $required, null];
	}

	public function exec(array $params = null) {
		if ($params['coach_id'] === $this->user->uid) {
			return 2070;
		}
		$coach = new coach();
		$coach->get($params['coach_id']);
		
		// 如果是正常状态（coach.status == 0），只能更新为 1
		if ($coach->status === coach::NORMAL && $params['status'] !== coach::BLOCKED) {
			return 2071;
		}
		// 如果是锁定状态（coach.status == 1），可以更新为 0（正常）或 2（删除）
		if ($coach->status === coach::BLOCKED && $params['status'] !== coach::NORMAL && $params['status'] !== coach::REMOVED) {
			return 2071;
		}
		// 如果是删除状态（coach.status == 2），不可以更新
		if ($coach->status === coach::REMOVED) {
			return 2071;
		}
		
		$coach->status = $params['status'];
		$coach->update($params['coach_id']);
		
		return ['success' => true];
	}

	public function getPrivilege() {
		return 10;
	}
}
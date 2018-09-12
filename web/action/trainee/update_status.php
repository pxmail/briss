<?php
/**
 * 修改运动员状态
 */
use fly\Action;
class update_status extends Action {
	public function getFilter() {
		$required = array(
			'trainee_id' => FILTER_SANITIZE_NUMBER_INT,
			'status' => FILTER_SANITIZE_NUMBER_INT
		);
		
		return [INPUT_POST, $required, null];
	}
	
	public function exec(array $params = null) {
		$trainee = new trainee();
		$trainee->get($params['trainee_id']);
		
		// 如果是审核状态（trainee.status = 0），只能更新为 1（正常）或4（拒绝）
		if ($trainee->status === trainee::STATUS_NOT_REVIEWED && $params['status'] !== trainee::STATUS_NORMAL && $params['status'] !== trainee::STATUS_REJECTED) {
			return 2071;
		}
		// 如果是锁定状态（trainee.status = 2），可以更新为 0（正常）或 2（删除）
		if ($trainee->status === trainee::STATUS_LOCKED && $params['status'] !== trainee::STATUS_NORMAL && $params['status'] !== trainee::STATUS_DELETED) {
			return 2071;
		}
		// 如果是删除状态（trainee.status = 3），不可以更新
		if ($trainee->status === trainee::STATUS_DELETED) {
			return 2071;
		}
		
		$base = new base();
		$base->get($trainee->base_id);
		if ($params['status'] === trainee::STATUS_REJECTED) {
			$trainee->delete($params['trainee_id']);
		} else {
			$trainee->status = $params['status'];
			$trainee->update($params['trainee_id']);
			
			//教练审核通过，基地运动员数目、该运动员所属运动项目人数加1
			$base->trainee_num = $base->trainee_num + 1;
			$base->update($base->id);
			
			$sport = new sport();
			$sport->get($trainee->sport_id);
			$sport->trainee_num = $sport->trainee_num + 1;
			$sport->update($trainee->sport_id);
		}

		return ['success' => true];
	}
	
	public function getPrivilege() {
		return 20;
	}
}
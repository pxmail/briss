<?php
use fly\Action;
/**
 * 查询待审核的所有运动员
 * @author yangz
 *
 */
class list_pending extends Action {
	public function getFilter() {
		
	}
	
	public function exec(array $params = null) {
		$trainee = new trainee();
		$trainees = $trainee->listPending();
		
		return ['trainees' => $trainees];
	}
	
	public function getPrivilege() {
		return 20;
	}
}
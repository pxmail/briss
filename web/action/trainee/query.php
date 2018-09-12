<?php
require_once 'fly/privilege.php';

use fly\Action;
use fly\Privilege;

/**
 * 根据传入的关键字查询运动员数据
 * @author yangz
 *
 */
class query extends Action {
	public function getFilter() {
		$required = array(
			'keyword' => FILTER_SANITIZE_STRING
		);
		
		$optional = array(
			'list_mine' => FILTER_VALIDATE_BOOLEAN
		);
		
		return [INPUT_GET, $required, $optional];
	}

	public function exec(array $params = null) {
		$trainee = new trainee();
		if (is_numeric($params['keyword']) && strlen($params['keyword']) > 3) {
			if (empty($params['list_mine'])) {
				$trainees = $trainee->listByMobile($params['keyword']);
			} else {
				$trainees = $trainee->listMineByMobile($params['keyword'], $this->user->uid);
			}
		} else {
			if (empty($params['list_mine'])) {
				$trainees = $trainee->lists($params['keyword']);
			} else {
				$trainees = $trainee->listMine($params['keyword'], $this->user->uid);
			}
		}
		
		return ['trainees' => $trainees];
	}

	public function getPrivilege() {
		return 0;
	}
}
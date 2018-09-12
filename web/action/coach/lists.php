<?php
/**
 * 获取所有教练信息
 */
use fly\Action;
class lists extends Action {
	public function getFilter() {
	}
	
	public function exec(array $params = null) {
		$coach = new coach();
		$coaches = $coach->lists();

		return ['coaches' => $coaches];
	}
	
	public function getPrivilege() {
		return 10;
	}
}
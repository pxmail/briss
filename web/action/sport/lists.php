<?php
use fly\Action;
/**
 * 获取所有的运动项目列表
 * @author yangz
 *
 */
class lists extends Action {
	public function getFilter() {
		
	}
	
	public function exec(array $params = null) {
		$sport = new sport();
		$sports = $sport->lists();
		
		return ['sports' => $sports];
	}
	
	public function getPrivilege() {

	}
}
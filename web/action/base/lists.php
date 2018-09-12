<?php
/**
 * 查询所有的基地列表
 */
use fly\Action;
class lists extends Action {
	public function getFilter() {
		
	}
	
	public function exec(array $params = null) {
		$base = new base();
		$bases = $base->lists();
		
		return ['bases' => $bases];
	}
	
	public function getPrivilege() {
		return NULL;
	}
}
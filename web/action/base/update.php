<?php
/**
 * 修改基地信息
 */
use fly\Action;
class update extends Action {
	public function getFilter() {
		$required = array(
			'base_id' => FILTER_SANITIZE_NUMBER_INT,
			'base_name' => FILTER_SANITIZE_STRING
		);
		
		return [INPUT_POST, $required, null];
	}
	
	public function exec(array $params = null) {
		$base = new base();
		$base->get($params['base_id']);
		
		// 检查修改的新基地名称是否与 数据库中的其它数据重复
		if ($params['base_name'] !== $base->name) {
			$obj = new base();
			$obj->getByName($params['base_name']);
			if ($obj->id) {
				return 3010;
			}
		}
		
		$base->name = $params['base_name'];
		$base->update($params['base_id']);
		
		return ['base_id' => $params['base_id']];
	}
	
	public function getPrivilege() {
		return 10;
	}
}
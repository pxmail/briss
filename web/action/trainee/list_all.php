<?php
use fly\Action;
/**
 * 查询所有的运动员列表
 * @author yangz
 *
 */
class list_all extends Action {
	public function getFilter() {
//		$optional = array(
//			'page' => FILTER_SANITIZE_NUMBER_INT
//		);
//		return [INPUT_GET, null, $optional];
	}
	
	public function exec(array $params = null) {
		//默认一页显示10条数据
//		$pagesize = 10;
//		//如果不传页码，默认页码为 page = 1
//		if (empty($params['page']) || $params['page']<0){
//			$params['page'] = 1;
//		}
//		$start = ($params['page'] - 1) * $pagesize;
		$trainee = new trainee();
		$trainees = $trainee->listAll();
		
		// 判断有下一页
//		$has_next = count($trainees) === ($pagesize + 1);
//		if ($has_next) {
//			array_pop($trainees);
//		}
		
		return ['trainees' => $trainees];
	}
	
	public function getPrivilege() {
		return 20;
	}
}
<?php
require_once 'fly/privilege.php';

use fly\Action;
use fly\Privilege;

/**
 * 获取训练动作列表
 * @author yangz
 *
 */
class get_movements extends Action {
	public function getFilter() {
		
	}

	public function exec(array $params = null) {
		$groups = array("攀爬机训练", "Pully训练", "力量耐力训练", "动作速度训练");
		
		$items[0][] = array("id" => 101, "name" => "5*30秒高强度训练");
		$items[0][] = array("id" => 102, "name" => "Tabata高强度间歇性训练");
		$items[0][] = array("id" => 103, "name" => "10s冲刺训练");
		$items[0][] = array("id" => 104, "name" =>"耐力训练--自己记录阻力（无，中等，高）、时间和距离");
		
		$items[1][] = array("id" => 201, "name" => "转体提拉");
		
		$items[2][] = array("id" => 301, "name" => "3分钟俯卧撑");
		$items[2][] = array("id" => 302, "name" => "3分钟引体向上");
		$items[2][] = array("id" => 303, "name" =>"俯卧撑最大次数");
		$items[2][] = array("id" => 304, "name" =>"引体向上最大次数");
		
		$items[3][] = array("id" => 401, "name" => "六边形跳");
		$items[3][] = array("id" => 402, "name" =>"20次双摇");
		$items[3][] = array("id" => 403, "name" =>"10s快速脚步");

		$count = count($groups);
		$movements = array();
		for ($i = 0; $i < $count; $i++) {
			$movements[$i] = array(
				"group" => &$groups[$i],
				"items" => &$items[$i]
			);
		}
		
		return ['movements' => $movements];
	}

	public function getPrivilege() {
		return 0;
	}
}
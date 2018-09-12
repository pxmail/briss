<?php
use fly\Model;

/**
 * 教练员信息表
 * @author yangz
 *
 */
class coach extends Model {
	var $id, $name, $password, $status, $create_time, $login_time, $role_id;
	//教练状态
	const NORMAL 	= '0';	// 正常
	const BLOCKED 	= '1';	// 锁定
	const REMOVED	= '2';	// 删除
	
	//默认密码
	const DEFAULT_PASSWORD = 'ezsport';
	
	public function create() {
		$this->_insert();
		return self::$pdo->lastInsertId();
	}
	
	public function get($id) {
		$stmt = self::$pdo->prepare('SELECT * FROM coach WHERE id = ?');
		$stmt->bindValue(1, $id);
		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_INTO, $this);
		return $stmt->fetch();
	}
	
	public function getByName($name, $status) {
		$stmt = self::$pdo->prepare('SELECT * FROM coach WHERE name = ? AND status != ?');
		$stmt->bindValue(1, $name);
		$stmt->bindValue(2, $status);
		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_INTO, $this);
		return $stmt->fetch();
	}
	
	public function lists() {
		$stmt = self::$pdo->prepare('SELECT * FROM coach');
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	
	public function update($id) {
		return $this->_update(['id'=>$id]);
	}
}
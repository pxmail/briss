<?php
use fly\Model;
/**
 * 运动项目表
 * @author yangz
 *
 */
class sport extends Model {
	var $id, $name, $trainee_num;
	
	public function create() {
		$this->_insert();
		return self::$pdo->lastInsertId();
	}
	
	public function delete($id) {
		return $this->_delete(['id' => $id]);
	}
	
	public function get($id) {
		$stmt = self::$pdo->prepare('SELECT * FROM sport WHERE id = ?');
		$stmt->bindValue(1, $id);
		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_INTO, $this);
		
		return $stmt->fetch();
	}
	
	public function getByName($name) {
		$stmt = self::$pdo->prepare('SELECT * FROM sport WHERE name = ?');
		$stmt->bindValue(1, $name);
		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_INTO, $this);
		
		return $stmt->fetch();
	}
	
	public function lists() {
		$stmt = self::$pdo->prepare('SELECT * FROM sport');
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);		
	}
	
	public function update($id) {
		return $this->_update(['id' => $id]);
	}
}
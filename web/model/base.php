<?php
use fly\Model;

/**
 * 基地表
 * @author yangz
 *
 */
class base extends Model {
	var $id, $name, $trainee_num;
	
	public function create() {
		$this->_insert();
		return self::$pdo->lastInsertId();
	}
	
	public function get($id) {
		$stmt = self::$pdo->prepare('SELECT * FROM base WHERE id = ?');
		$stmt->bindValue(1, $id);
		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_INTO, $this);
		return $stmt->fetch();
	}
	
	public function getByName($name) {
		$stmt = self::$pdo->prepare('SELECT * FROM base WHERE name = ?');
		$stmt->bindValue(1, $name);
		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_INTO, $this);
		return $stmt->fetch();
	}
	
	public function lists() {
		$stmt = self::$pdo->prepare('SELECT * FROM base');
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	
	public function update($id) {
		return $this->_update(['id'=>$id]);
	}
	
	public function delete($id) {
		return $this->_delete( ['id' => $id] );
	}
}
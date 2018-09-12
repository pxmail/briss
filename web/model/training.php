<?php

use fly\Model;

/**
 * 记录运动员具体的运动项目运动时间
 * @author yangz
 *
 */
class training extends Model {

    var $id, $trainee_id, $training_date, $training_time, $movement_id,
            $number_1, $number_2, $number_3, $number_4, $number_5, $number_6, $number_7,
            $number_8, $string_1, $string_2, $group_id;

    public function create() {
        $this->_insert();
        return self::$pdo->lastInsertId();
    }

    public function get($id) {
        $stmt = self::$pdo->prepare('SELECT * FROM training WHERE id = ?');
        $stmt->bindValue(1, $id);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_INTO, $this);
        return $stmt->fetch();
    }

    public function lists($trainee_id, $movement_id, $start_date, $end_date) {
        $stmt = self::$pdo->prepare('SELECT * FROM training WHERE trainee_id = ? AND movement_id = ? AND training_date BETWEEN ? AND ?');
        $stmt->bindValue(1, $trainee_id);
        $stmt->bindValue(2, $movement_id);
        $stmt->bindValue(3, $start_date);
        $stmt->bindValue(4, $end_date);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listByTrainee($trainee_id, $training_date) {
        $stmt = self::$pdo->prepare('SELECT * FROM training WHERE trainee_id = ? AND training_date = ?');
        $stmt->bindValue(1, $trainee_id);
        $stmt->bindValue(2, $training_date);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update($id) {
        return $this->_update(['id' => $id]);
    }

    
    public function getPrevious($id, $trainee_id, $movement_id) {
        $stmt = self::$pdo->prepare('SELECT * FROM training WHERE trainee_id = :trainee_id AND movement_id = :movement_id AND id < :id ORDER BY id DESC LIMIT 1');
        $stmt->bindValue('trainee_id', $trainee_id);
        $stmt->bindValue('movement_id', $movement_id);
        $stmt->bindValue('id', $id);
        $stmt->execute();
        
        $stmt->setFetchMode(\PDO::FETCH_INTO, $this);
        
        return $stmt->fetch() !== FALSE;
    }
    
    public function getSubsequent($id, $trainee_id, $movement_id) {
        $stmt = self::$pdo->prepare('SELECT * FROM training WHERE trainee_id = :trainee_id AND movement_id = :movement_id AND id > :id ORDER BY id ASC LIMIT 1');
        $stmt->bindValue('trainee_id', $trainee_id);
        $stmt->bindValue('movement_id', $movement_id);
        $stmt->bindValue('id', $id);
        $stmt->execute();
        
        $stmt->setFetchMode(\PDO::FETCH_INTO, $this);
        
        return $stmt->fetch() !== FALSE;
    }
}

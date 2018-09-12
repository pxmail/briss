<?php

use fly\Action;

/**
 * 根据学员ID查询某学员的训练记录
 * @author yangz
 *
 */
class list_by_trainee extends Action {

    public function getFilter() {
        $required = array(
            'trainee_id' => FILTER_SANITIZE_NUMBER_INT
        );

        $optional = array(
            'training_date' => FILTER_SANITIZE_STRING
        );

        return [INPUT_GET, $required, $optional];
    }

    public function exec(array $params = null) {
        $training = new training();
        
        $ret = [];
        
        if (empty($params['training_date'])) {
            $params['training_date'] = date('Y-m-d');
            //查询运动员是否已经入场训练
            $trainee = new trainee();
            $trainee->get($params['trainee_id']);
            $ret['last_train_date'] = $trainee->last_train_date;
            $ret['last_status'] = $trainee->last_status;
        }
        
        $ret['trainings'] = $training->listByTrainee($params['trainee_id'], $params['training_date']);

        

        return $ret;
    }

    public function getPrivilege() {
        return NULL;
    }

}

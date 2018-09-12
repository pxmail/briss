<?php

/*
 * (C) 2016 EZ sport Ltd.
 */

/**
 * 获取指定动作的上一个相同动作数据
 *
 * @author xieyiming
 */
class get_previous extends fly\Action {
    
    public function exec(array $params = null) {
        $training = new training();
        $training->getPrevious($params['id'], $params['trainee_id'], $params['movement_id']);
        
        return ['training' => $training];
    }

    public function getFilter() {
        $required = array(
            'id' => FILTER_VALIDATE_INT,
            'trainee_id' => FILTER_VALIDATE_INT,
            'movement_id' => FILTER_VALIDATE_INT
        );
        
        return [INPUT_GET, $required, NULL];
    }

    public function getPrivilege() {
        return 0;
    }

}

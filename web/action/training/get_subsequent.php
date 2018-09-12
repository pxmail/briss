<?php

/*
 * (C) 2016 EZS Ltd.
 */

/**
 * Description of get_subsequent
 *
 * @author xieyiming
 */
class get_subsequent extends fly\Action {

    public function exec(array $params = null) {
        $training = new training();
        $training->getSubsequent($params['id'], $params['trainee_id'], $params['movement_id']);

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

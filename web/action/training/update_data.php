<?php
use fly\Action;
class update_data extends Action {
	public function getFilter() {
		$required = array(
			'id' => FILTER_SANITIZE_NUMBER_INT		
		);
		
		$optional = array(
			'number_1' => array(
					'filter' => FILTER_SANITIZE_NUMBER_FLOAT,
					'flags' => FILTER_FLAG_ALLOW_FRACTION
			),
			'number_2' => array(
					'filter' => FILTER_SANITIZE_NUMBER_FLOAT,
					'flags' => FILTER_FLAG_ALLOW_FRACTION
			),
			'number_3' => array(
					'filter' => FILTER_SANITIZE_NUMBER_FLOAT,
					'flags' => FILTER_FLAG_ALLOW_FRACTION
			),
			'number_4' => array(
					'filter' => FILTER_SANITIZE_NUMBER_FLOAT,
					'flags' => FILTER_FLAG_ALLOW_FRACTION
			),
			'number_5' => array(
					'filter' => FILTER_SANITIZE_NUMBER_FLOAT,
					'flags' => FILTER_FLAG_ALLOW_FRACTION
			),
			'number_6' => array(
					'filter' => FILTER_SANITIZE_NUMBER_FLOAT,
					'flags' => FILTER_FLAG_ALLOW_FRACTION
			),
			'number_7' => array(
					'filter' => FILTER_SANITIZE_NUMBER_FLOAT,
					'flags' => FILTER_FLAG_ALLOW_FRACTION
			),
			'number_8' => array(
					'filter' => FILTER_SANITIZE_NUMBER_FLOAT,
					'flags' => FILTER_FLAG_ALLOW_FRACTION
			),
			'string_1' => FILTER_SANITIZE_STRING,
			'string_2' => FILTER_SANITIZE_STRING
		);
		return [INPUT_POST, $required, $optional];
	}
	
	public function exec(array $params = null) {
		$training = new training();
		$training->map($params);
		$training->update($params['id']);
		
		return ['id' => $params['id']];
	}
	
	public function getPrivilege() {
		return 0;
	}
}

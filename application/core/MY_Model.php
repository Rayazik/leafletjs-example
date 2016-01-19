<?php

class MY_Model extends CI_Model {
	
	public function __construct() {
		parent::__construct();
	}
	
	public function __destruct() {
		// nothing
	}
	
	/** оставляем только нужные поля */
	protected function _filter_data($data, $fields = [], $filter_specialchars = true) {
	
		$result = [];
	
		foreach ($fields as $fieldname) {
			if (isset($data[$fieldname])) {
				if (is_string($data[$fieldname])) {
					$data[$fieldname] = trim($data[$fieldname]);
				}
				if ($filter_specialchars && is_string($data[$fieldname])) {
					$result[$fieldname] = htmlspecialchars($data[$fieldname]);
				} else {
					$result[$fieldname] = $data[$fieldname];
				}
			}
		}
	
		return $result;
	
	}
	
	/**
	 * проверка входящих данных
	 * @param $not_empty - флаг, разрешающий или запрещающий пустые значения в полях
	 * */
	protected function _check_data($data, $fields, $filter_specialchars = true, $not_empty = false) {
	
		$result = [
				'error'	=> false,
				'data'	=> [],
		];
			
		if (!empty($data) && !empty($fields)) {
	
			foreach ($fields as $field) {
				if (!isset($data[$field])) {
					$result['error'] = true;
					$result['messages'][] = "Field '$field' is required!";
				} else {
					// чтобы не было названий из пробела
					if (is_string($data[$field])) {
						$data[$field] = trim($data[$field]);
					}
					if ($filter_specialchars && is_string($data[$field])) {
						$result['data'][$field] = htmlspecialchars($data[$field]);
					} else {
						$result['data'][$field] = $data[$field];
					}
				}
			}
	
			return $result;
	
		}
	
		$result = array_merge($result, [
				'error'	=> true,
				'messages'	=> ['Data and (or) fields array is empty!'],
		]);
	
		return $result;
	
	}
	
	
}
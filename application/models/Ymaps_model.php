<?php
/**
 * Модель для работы с Яндекс-картами
 * @author Rayaz
 * @since 19/01/2016
 */
class Ymaps_model extends MY_Model {
	
	const API_LINK = 'https://geocode-maps.yandex.ru/1.x/';
	
	protected $_request_params = [
			'format'	=> 'json',
			'results'	=> 1,
	];
	
	public function __construct() {
		parent::__construct();
	}
	
	/**
	 * Задать параметры вызова по умолчанию
	 * @param array $params
	 */
	public function set_request_params($params = []) {
		if (!empty($params) && is_array($params)) {
			foreach ($params as $k => $v) {
				$this->_request_params[$k] = $v;
			}
		}
	}
	
	/**
	 * Вызов к апи
	 * @param string $path
	 * @param unknown $params
	 * @return mixed
	 */
	public function api_call($path = '', $params = []) {
	
		$req_url = self::API_LINK . '?' . http_build_query(array_merge($this->_request_params, $params));
	
		$ch = curl_init($req_url);
		
		curl_setopt_array($ch, [
			CURLOPT_RETURNTRANSFER	=> true,
			CURLOPT_HEADER			=> false,
			CURLOPT_NOBODY 			=> false,
		]);
		
		$res = curl_exec($ch);
		
		curl_close($ch);
		
		return json_decode($res, true);
	
	}
	
	
}
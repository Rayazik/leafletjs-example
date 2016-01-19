<?php

class Points_model extends MY_Model {
	
	const TABLE_NAME = 'points';
	
	/**
	 * Полная схема таблицы
	 * @var array
	 */
	protected $_table_schema = [
			'id',
			'lat',
			'lon',
			'description',
	];
	
	/**
	 * Обязательные поля при создании записи
	 * @var array
	 */
	protected $_table_schema_check = [
			'lat',
			'lon',
			'description',
	];
	
	/**
	 * Поля которые можно обновлять
	 * @var array
	 */
	protected $_table_schema_update = [
			'lat',
			'lon',
			'description',
	];

	/**
	 * Поля по которым можно делать выборку
	 * @var array
	 */
	protected $_table_schema_where = [
			'id',
			'lat',
			'lon'
	];
	
	/**
	 * Создание точки
	 * @param array $data
	 * @return integer - id созданной записи или 0
	 */
	public function create($data = []) {
		
		$check = $this->_check_data($data, $this->_table_schema_check);
		
		if (!$check['error']) {
			
			$insert_data = $this->_filter_data($data, $this->_table_schema_update);
			
			$res = $this->db->set($insert_data)->insert(self::TABLE_NAME);
			
			if ($res) {
				return $this->db->insert_id();
			}
			
		}
		
		return 0;
		
	}
	
	/**
	 * Выборка по точкам
	 * @param array $data
	 * @return array
	 */
	public function get($data = []) {
		
		$where = $this->_filter_data($data, $this->_table_schema_where);
		
		$res = $this->db->where($where)->get(self::TABLE_NAME)->result_array();
		
		return $res ? $res : [];
		
	}
	
	/**
	 * Обновление точки
	 * @param unknown $id
	 * @param unknown $data
	 * @return boolean
	 */
	public function update($id, $data = []) {
		
		$id = intval($id);
		
		if ($id) {
			
			// обновляем только разрешенные поля
			$update_data = $this->_filter_data($data, $this->_table_schema_update);
			
			// если есть что обнвлять
			if (!empty($update_data)) {
				
				$res = $this->db->where('id', $id)->set($update_data)->limit(1)->update(self::TABLE_NAME);
				
				return boolval($res);
				
			}
			
		}
		
		return false;
		
	}
	
	/**
	 * Удаление точки
	 * @param integer $id
	 * @return boolean
	 */
	public function delete($id) {
		
		$id = intval($id);
		
		if ($id) {
			
			$res = $this->db->where('id', $id)->limit(1)->delete(self::TABLE_NAME);
			
			return boolval($res);
			
		}
		
		return false;
		
	}
	
}
<?php

class Ajax extends CI_Controller {
	
	/**
	 * Инстанс ответа
	 * @var array
	 */
	protected $result = [
			'success'	=> false,
			'data'		=> [],
			'message'	=> 'No message',
	];
	
	/**
	 * Задать сообщение для пользователя
	 */
	protected function _message($msg = '') {
		$this->result['message'] = $msg;
		return $this;
	}
	
	/**
	 * Обертка для ответа о том, что все прошло успешно
	 * @param unknown $data
	 */
	protected function _success($data = []) {
		$this->result['data'] = $data;
		$this->result['success'] = true;
		return $this->_response($data);
	}
	
	/**
	 * Обертка для ответа о том, что что-то пошло не так
	 * @param unknown $data
	 * @return boolean
	 */
	protected function _failure($data = []) {
		$this->result['success'] = false;
		return $this->_response($data);
	}
	
	/**
	 * Универсальная обертка для возврата ответа
	 * @param array $data
	 * @return boolean
	 */
	protected function _response($data = []) {
		header('Content-type: application/json');
		$data = array_merge($this->result, $data);
		echo json_encode($data);
		return true;
	}
	
	public function get_points() {
		$this->load->model('points_model', 'point');
		return $this->_success([
				'points'	=> $this->point->get(),
		]);
	}
	
	/**
	 * Создание точки
	 */
	public function create_point() {
		
		$data = $this->input->post();
		
		if (!empty($data)) {
			
			$this->load->model('points_model', 'point');
			
			// при успешном создании к нам вернется id созданной точки
			$id = $this->point->create($data);
			
			if ($id) {
				return $this->_message('Точка успешно создана!')->_success([
						'id'	=> $id,
				]);
			} else {
				return $this->_message('Не удалось создать точку :(')->_failure([]);
			}
			
		}
		
		return $this->_message('Данные не передались! Обратитесь к администратору.')->_failure();
		
	}
	
	public function delete_point($id = 0) {
		
		$id = intval($id);
		
		if ($id) {
			
			$this->load->model('points_model', 'point');
			
			$res = $id = $this->point->delete($id);
			
			if ($res) {
				return $this->_message('Точка успешно удалена!')->_success([]);
			} else {
				return $this->_message('Не удалось удалить точку :(')->_failure([]);
			}
			
		}
		
		return $this->_message('Неправильный ID!')->_failure([]);
		
	}
	
	public function update_point() {
		
	}
	
}
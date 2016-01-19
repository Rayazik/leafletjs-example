<?php

class Index extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
	}
	
	/**
	 * Индексная страница
	 */
	public function index() {
		// на локалхосте не удалось использовать redirect() так как он считал доменом 127.0.0.1
		header('Location: /index.php/index/view', null, 301); 
		exit();
	}
	
	/**
	 * Просмотр всех точек и добавление точки
	 */
	public function view() {
		
		$this->load->model('points_model', 'point');
		
		$this->load->view('index/template', [
				'content'	=> $this->load->view('index/view', [
						
				], true),
		]);
		
	}
	
	/**
	 * Просмотр списка точек
	 */
	public function points() {
		
		$this->load->library('parser');
		
		$this->load->model('points_model', 'point');
		
		$act = $this->input->get('act');
		$res = $this->input->get('res') == 'success';
		
		$message = '';
		switch ($act) {
			case 'del':
				$message = $res ? 'Удаление прошло успешно!' : 'Не удалось удалить запись!';
			break;
		}
		
		$this->load->view('index/template', [
				'content'	=> $this->parser->parse('index/points', [
						'points'	=> $this->point->get([]),
						'message'	=> $message,
						'success'	=> $res,
				], true),
		]);
		
	}
	
	public function deletepoint($id = 0) {
		
		$success = false;
		if (is_numeric($id) && $id) {
			$this->load->model('points_model', 'point');
			$res = $this->point->delete($id);
			$success = boolval($res);
		}
		
		// на локалхосте не удалось использовать redirect() так как он считал доменом 127.0.0.1
		header('Location: /index.php/index/points?act=del&res=' . ($success ? 'success' : 'failure'), null, 307);
		exit();
		
	}
	
}
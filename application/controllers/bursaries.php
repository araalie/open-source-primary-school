<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Bursaries extends GeneralAuth_Controller {

	public function __construct() {
		
		parent::__construct();
		
		AccessManager::only('DIRECTOR');
		
	}
	
	public function index() {

		$opts = new Optionsmanager();
		$data['current_classes'] = $opts -> getCurrentClasses('Any Class');
		$data['search_classi_id'] = $this -> input -> post('search_classi_id');

		$data['page'] = 'student-management/bursary';

		$this -> load -> view('layout/default', $data);
	}

	public function post() {
		$this -> load -> library('Accountsservice');
		$accSvc = new Accountsservice();

		echo json_encode($accSvc -> postBursary($this -> input -> post()));
	}

	public function get($studentId) {
		$this -> load -> library('Accountsservice');
		$accSvc = new Accountsservice();

		echo json_encode($accSvc -> getStudentBursary($studentId));
	}

	public function delete() {
		$this -> load -> library('Accountsservice');
		$accSvc = new Accountsservice();

		echo json_encode($accSvc -> deleteBursary($this -> input -> post('bursary_id')));
	}
	

	public function term_awards() {
		$this -> load -> library('Accountsservice');
		$accSvc = new Accountsservice();
		$data['page'] = 'student-management/awards';
		$data['term_awards'] = $accSvc->getTermBursaries();
		
		$this -> load -> view('layout/default', $data);
	}	

}

/* End of file bursaries.php */
/* Location: ./application/controllers/bursaries.php */

<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Student_status extends GeneralAuth_Controller {

	public function __construct() {
		
		parent::__construct();
		
	}
	

	public function change() {
		$this -> load -> library('Studentservice');
		
		$stSvc = new Studentservice();

		echo json_encode($stSvc->changeStudentsStatus( $this -> input -> post()));
	}

}

/* End of file student_status.php */
/* Location: ./application/controllers/student_status.php */

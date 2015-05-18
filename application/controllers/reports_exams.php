<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Reports_exams extends GeneralAuth_Controller {


	public function __construct() {
		
		parent::__construct();
		
		AccessManager::only(array('DIRECTORY OF STUDIES','DIRECTOR'));
		
	}
		
	
	public function class_exam_results($class_instance, $exam)
	{
		$this->load->library('Examreportservice');
		$repSvc = new Examreportservice();
		
		$repSvc->getClassExamReport($class_instance, $exam);
	}
}

/* End of file Reports_classes.php */
/* Location: ./application/controllers/Reports_classes.php */

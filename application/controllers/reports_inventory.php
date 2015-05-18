<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Reports_inventory extends GeneralAuth_Controller {

	public function __construct() {
		
		parent::__construct();
		
		AccessManager::only(array('BURSAR','DIRECTOR'));
		
	}
	
	public function student($studentId, $termId=NULL)
	{
		$this->load->library('Reportservice');
		$repSvc = new Reportservice();
		
		$repSvc->getStudentInventory($studentId,$termId);
	}
}

/* End of file Reports_classes.php */
/* Location: ./application/controllers/Reports_classes.php */

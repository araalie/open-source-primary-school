<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Fees_reports extends GeneralAuth_Controller {

	public function __construct() {
		
		parent::__construct();
		
		AccessManager::only(array('BURSAR','DIRECTOR'));
		
	}
		
	public function index($studentId)
	{
		$this->load->library('Reportservice');
		$repSvc = new Reportservice();
		
		$repSvc->getFeesStatus($studentId);
	}


	public function term_bursary($termId=NULL)
	{
				if (is_null($termId)) {
			$current_term = Utilities::getCurrentTerm();
			$termId = $current_term -> getId();
		}
				
		$this->load->library('Reportservice');
		$repSvc = new Reportservice();
		
		$repSvc->getTermBursaries($termId);
	}	
}

/* End of file fees_reports.php */
/* Location: ./application/controllers/fees_reports.php */
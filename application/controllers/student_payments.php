<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Student_payments extends GeneralAuth_Controller {

	public function __construct() {
		
		parent::__construct();
		
		AccessManager::only('BURSAR');
		
	}	
	
	public function index()
	{
		
		$opts = new Optionsmanager();
		$data['current_classes'] = $opts->getCurrentClasses('Any Class');
		$data['search_classi_id']=$this->input->post('search_classi_id');
		
		$data['page']='student-management/student-payments';
		
		$this->load->view('layout/default', $data);
	}
	
	
	public function find()
	{
		$this->load->library('Studentservice');
		$studentSvc = new Studentservice();
		
		$criteria = array();
		
		$criteria['class_instance'] = $this->input->post('search_classi_id');
		$criteria['sno'] = $this->input->post('sno');
		$criteria['name'] = $this->input->post('name');
		$criteria['student_id'] = $this->input->post('student_id');
		
		$sts = $studentSvc->getStudentsAsArray($criteria);
		
		echo json_encode($sts);
	}
	
	public function get_term_fees($classId, $feesProfileId, $freqName){
		$this->load->library('Accountsservice');
		$acSvc = new Accountsservice();
		
		echo $acSvc->getTotalCompulsaryClassFees($classId, $feesProfileId, $freqName);
	}
	
	public function get_term_fees_structure($classId, $feesProfileId, $frequency){
		$this->load->library('Accountsservice');
		$acSvc = new Accountsservice();
		$results = array();
		
		foreach($acSvc->getClassFeesProfileStructure($classId, $feesProfileId, $frequency) as $r){
			$results[]= $r;
		}
		echo json_encode($results);
	}
	
	public function post_fees_terminal()
	{
		$this->load->library('Accountsservice');
		$acSvc = new Accountsservice();
		echo json_encode($acSvc->postFees($this->input->post()));
	}


	public function post_fees_annual()
	{
		$this->load->library('Accountsservice');
		$acSvc = new Accountsservice();
		
		$form = $this->input->post();
		$form['date-done']= $form['annual-date-done'];
		$form['active_student_id']= $form['annual_active_student_id'];
		$form['pay-slip']= $form['annual-pay-slip'];
		$form['narrative']= $form['annual-narrative'];
		
		echo json_encode($acSvc->postFees($form));
	}


	public function post_fees_one_time()
	{
		$this->load->library('Accountsservice');
		$acSvc = new Accountsservice();
		
		$form = $this->input->post();
		$form['date-done']= $form['once-date-done'];
		$form['active_student_id']= $form['once_active_student_id'];
		$form['pay-slip']= $form['once-pay-slip'];
		$form['narrative']= $form['once-narrative'];
		
		echo json_encode($acSvc->postFees($form));
	}



	public function post_fees_adhoc()
	{
		$this->load->library('Accountsservice');
		$acSvc = new Accountsservice();
		
		$form = $this->input->post();
		$form['date-done']= $form['adhoc-date-done'];
		$form['active_student_id']= $form['adhoc_active_student_id'];
		$form['pay-slip']= $form['adhoc-pay-slip'];
		$form['narrative']= $form['adhoc-narrative'];
		
		echo json_encode($acSvc->postFees($form));
	}
		

	public function post_fees_holiday()
	{
		$this->load->library('Accountsservice');
		$acSvc = new Accountsservice();
		
		$form = $this->input->post();
		$form['date-done']= $form['holiday-date-done'];
		$form['active_student_id']= $form['holiday_active_student_id'];
		$form['pay-slip']= $form['holiday-pay-slip'];
		$form['narrative']= $form['holiday-narrative'];
		
		echo json_encode($acSvc->postFees($form));
	}
				
	public function get_students_fees($studentId){
		$this->load->library('Accountsservice');
		$acSvc = new Accountsservice();
		
		$currentTerm = Utilities::getCurrentTerm();
				
		$r = $acSvc->getFeesPaidBreakDownForTerm($studentId, $currentTerm->getId());
		
		echo json_encode($r);
	}


	public function get_students_fees_breakdown_current_year($studentId){
		$this->load->library('Accountsservice');
		$acSvc = new Accountsservice();
		
		$currentTerm = Utilities::getCurrentTerm();
				
		$r = $acSvc->getFeesPaidBreakDownForYear($studentId, $currentTerm->getId());
		
		echo json_encode($r);
	}
	

	public function get_students_fees_breakdown_once($studentId){
		$this->load->library('Accountsservice');
		$acSvc = new Accountsservice();
		
		$r = $acSvc->getFeesPaidBreakDownForOnce($studentId);
		
		echo json_encode($r);
	}
	

	public function get_students_fees_breakdown_adhoc($studentId){
		$this->load->library('Accountsservice');
		$acSvc = new Accountsservice();
		
		$currentTerm = Utilities::getCurrentTerm();
				
		$r = $acSvc->getFeesPaidBreakDownForTerm($studentId, $currentTerm->getId(),'AD HOC');
		
		echo json_encode($r);
	}


	public function get_students_fees_breakdown_holiday($studentId){
		$this->load->library('Accountsservice');
		$acSvc = new Accountsservice();
		
		$currentTerm = Utilities::getCurrentTerm();
				
		$r = $acSvc->getFeesPaidBreakDownForTerm($studentId, $currentTerm->getId(),'HOLIDAY');
		
		echo json_encode($r);
	}
			
							
	public function get_student_bursary($studentId) {
		
		$this -> load -> library('Accountsservice');
		$accSvc = new Accountsservice();

		echo json_encode($accSvc -> getStudentBursary($studentId));
	}
		
	public function term_procurable_items($studentId, $termId=NULL){
		
		$this->load->library('Accountsservice');
		$acSvc = new Accountsservice();
		
		echo json_encode($acSvc->getStudentItemInventory($studentId, $termId))	;
	}
	

	public function delete_transaction()
	{
		$this->load->library('Accountsservice');
		$acSvc = new Accountsservice();
		echo json_encode($acSvc->deletePosting($this->input->post()));
	}


	public function update_inventory()
	{
		$this->load->library('Accountsservice');
		$acSvc = new Accountsservice();
		echo json_encode($acSvc->updateInventory($this->input->post()));
	}
		

	public function update_charge_inventory()
	{
		$this->load->library('Accountsservice');
		$acSvc = new Accountsservice();
		echo json_encode($acSvc->updateChargeInventory($this->input->post()));
	}
	
	
	public function delete_from_inventory()
	{
		$this->load->library('Accountsservice');
		$acSvc = new Accountsservice();
		echo json_encode($acSvc->deleteFromInventory($this->input->post()));
	}
	
	public function pay_for_inventory()
	{
		$this->load->library('Accountsservice');
		$acSvc = new Accountsservice();
		echo json_encode($acSvc->payForInventoryItems($this->input->post()));		
	}
}

/* End of file student_houses.php */
/* Location: ./application/controllers/student_Houses.php */
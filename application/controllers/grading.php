<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Grading extends GeneralAuth_Controller {

	public function __construct() {
		
		parent::__construct();
		
		AccessManager::only(array('DIRECTORY OF STUDIES','DIRECTOR'));
		
	}
		
	public function index()
	{
		$this->load->library('Gradingmanager');
		$gSvc = new Gradingmanager();		
		
		
		$data['page_title']='Grading Management';
		$data['page']='exams/grading';
		$data['remarks']='';
		$data['name']='';
		$data['gid']='';
		$data['feedback']='';
		$data['grade_elements']='';
		
		if($this->input->post('g_name')){
			
			$result = $gSvc->editGrading($this->input->post());
			
			}else{
			$data['grade_elements']= $gSvc->editGradesInterface();
		}
		
		//$data['feedback']='';
		$data['schemes'] =$gSvc->getAllGradingSchemes();
		$this->load->view('layout/default', $data);
	}
	
}

/* End of file grading.php */
/* Location: ./application/controllers/grading.php */
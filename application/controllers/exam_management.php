<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Exam_management extends GeneralAuth_Controller {
	
	public function index()
	{
		$this->load->library('Optionsmanager');
		$oSvc = new Optionsmanager();
		
		$this->load->library('Exammanager');
		$exMan = new Exammanager();
		
		$data['page_title']='Examinations Management';
		$data['page']='exams/exam-management';
		$data['exam_name']='';
		$data['exam_id']=$data['feedback'] ='';
		$data['classes'] = $oSvc->getCurrentClassInstances();
		$data['exams'] = $exMan->getCurrentTermExams();
		$this->load->view('layout/default', $data);
	}
	
	public function edit($id=NULL){
		
		$this->load->library('Optionsmanager');
		$oSvc = new Optionsmanager();
		
		$this->load->library('Exammanager');
		$exMan = new Exammanager();
		
		$data['page_title']='Examinations Management';
		$data['page']='exams/exam-management';
		$data['exam_name']='';
		$data['exam_id']=$data['feedback'] ='';
		$data['classes'] = $oSvc->getCurrentClassInstances();
		
		if($this->input->post('exam_name')){
			$r = $exMan->editExam($this->input->post());
			$data['feedback'] = $r['msg'];
			
			if($r['success']){
				$data['exam_id']=$r['id'];
			}
		}
		
		$data['exams'] = $exMan->getCurrentTermExams();
		$this->load->view('layout/default', $data);
		
	}
}

/* End of file exam_management.php */
/* Location: ./application/controllers/exam_management.php */
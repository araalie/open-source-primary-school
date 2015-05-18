<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Exam_grading extends GeneralAuth_Controller {

	public function __construct() {
		
		parent::__construct();
		
		AccessManager::only(array('DIRECTORY OF STUDIES','DIRECTOR'));
		
	}
		
	public function index()
	{
		$this->load->library('Optionsmanager');
		$oSvc = new Optionsmanager();
		
		$this->load->library('Gradingmanager');
		$gradingMan = new Gradingmanager();
		
		$data['page']='exams/grading-assignment';
		$data['page_title']='Exams : Grading Scheme Assignment';
		$data['selected_exam']='';
		$data['current_exams']=$oSvc->getCurrentExamsListing();
		$data['my_classes']=array();
		
		$data['my_subjects']=array();
		$data['gradings'] =$gradingMan->getAllGradingSchemes();
		$this->load->view('layout/default', $data);
	}
	
	public function load_exam_results(){
		
		$this->load->library('Exammanager');
		$this->load->library('Gradingmanager');
		$this->load->library('Statisticsservice');
		$this->load->library('Chartingservice');
		
		$eMan=new Exammanager();
		
		$statSvc = new Statisticsservice();
		$chartSvc = new Chartingservice();
		
		$students = $eMan->getMyStudentsMarks($this->input->post('subject'),$this->input->post('exam')
					,$this->input->post('grading'));
		
		$students['stats']=$statSvc->subjectDivisionSummary($students['marks']);
		
		$chartData=array();
		
		$chartData['values'] = array_values($students['stats']);
		$chartData['labels'] = array_keys($students['stats']);
		$students['chart'] = $chartSvc->generalPieChart('Ranges', $chartData);
		
		
		echo json_encode($students);
	}
	
	
	public function lo_30a1Qd_exam_re_su___d62tJMlts35E64tL0k4_0mN(){
		
		$this->load->library('Exammanager');
		$exMan = new Exammanager();
		
		$class = $this->input->post('class');
		$exam = $this->input->post('exam');
		echo json_encode($exMan->getClassResults($class,$exam));
	}
	
}

/* End of file Exam_grading.php */
/* Location: ./application/controllers/Exam_grading.php */
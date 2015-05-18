<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Marks_editing extends GeneralAuth_Controller {

	public function __construct() {
		
		parent::__construct();
		
		AccessManager::only('SECRETARY');
		
	}
		
	public function index()
	{
		$this->load->library('Optionsmanager');
		$oSvc = new Optionsmanager();
		
		$this->load->library('Gradingmanager');
		
		$data['page_title']='Marks Entry';
		$data['page']='exams/marks/processing';
		
		$data['feedback']='';
		$data['selected_exam']='';
		$data['current_exams']=$oSvc->getCurrentExamsListing();
		$data['my_classes']=array();
		$data['my_subjects']=array();
		
		$this->load->view('layout/default', $data);
	}
	
	public function get_my_classes($exaId)
	{
		$this->load->library('Optionsmanager');
		$oSvc = new Optionsmanager();
		
		echo json_encode($oSvc->getMyCurrentExamsClasses($exaId));
	}
	
	public function get_my_subjects($classId)
	{
		$this->load->library('Optionsmanager');
		$oSvc = new Optionsmanager();
		
		echo json_encode($oSvc->getMyCurrentExamsSubjects($classId));
		}	
	
	
	public function get_my_student_marks(){
		
		$this->load->library('Exammanager');
		$this->load->library('Statisticsservice');
		$this->load->library('Chartingservice');
		
		$eMan=new Exammanager();
		$statSvc = new Statisticsservice();
		$chartSvc = new Chartingservice();
		
		$students = $eMan->getMyStudentsMarks($this->input->post('subject'),$this->input->post('exam'));
		$students['stats']=$statSvc->subjectScoreSummary1($students['marks']);
		
		$chartData=array();
		
		$chartData['values'] = array_values($students['stats']);
		$chartData['labels'] = array_keys($students['stats']);
		$students['chart'] = $chartSvc->generalPieChart('Ranges', $chartData);
		
		echo json_encode($students);
	}
	
	
	public function post_marks(){
		//check is teacher!
		
		$this->load->library('Exammanager');
		$eMan=new Exammanager();
		
		if($this->input->post('subject') && $this->input->post('student')){
			echo json_encode($eMan->postMarks($this->input->post()));
			}else{
			echo json_encode( array('success'=>FALSE, 'msg'=>'Unknown operation'));
		}
	}
	
}

/* End of file Marks_editing.php */
/* Location: ./application/controllers/Marks_editing.php */
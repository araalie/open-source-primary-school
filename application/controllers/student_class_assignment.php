<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Student_class_assignment extends GeneralAuth_Controller {

	public function __construct() {
		parent::__construct();
		AccessManager::only(array('DIRECTOR', 'REGISTROR', 'HEAD TEACHER'));
	}

	
	public function index()
	{
		$data['class_name']='';
		$data['feedback']='';
		$data['page_title']='Class Assignment';
		$data['year_enrolled'] = '';
		$this->load->library('Studentservice');
		$studentSvc = new Studentservice();
		
		
		$opts = new Optionsmanager();
		
		$data['classi_id']='';
		$data['search_classi_id']=$this->input->post('search_classi_id');
		$data['search_year']=$this->input->post('search_year');;
		$data['years_enrollable'] = $opts->getFunctionalYearOptions('Any Year');
		$data['current_classes'] = $opts->getCurrentClasses('Any Class');
		
		$data['previous_classes'] = $opts->getPreviousTermClasses();
		
		$data['hsearch_classi_id']=$this->input->post('hsearch_classi_id');;
		$data['hsearch_year']=$this->input->post('hsearch_year');;
		
		if($this->input->post('find_students')){
			$data['hsearch_classi_id']=$this->input->post('search_classi_id');;
			$data['hsearch_year']=$this->input->post('search_year');;
		}
		
		
		if($this->input->post('assign_to_class')){
			
			$result = $studentSvc->assignStudents2Class($this->input->post('classi_id'),$this->input->post('student-choices'));
		}
		
		$critetia = array();
		
		$critetia['class_instance'] = $this->input->post('search_classi_id');
		$critetia['eyear'] = $this->input->post('year_enrolled');
		
		$data['students'] = $studentSvc->getStudents($critetia);
		
		//$data['class_name']=$data['current_classes'][$data['classi_id']];		
		$data['page']='student-management/student-class-assignment';
		
		$this->load->view('layout/default', $data);
	}
	
}

/* End of file student_class_assignment.php */
/* Location: ./application/controllers/student_class_assignment.php */
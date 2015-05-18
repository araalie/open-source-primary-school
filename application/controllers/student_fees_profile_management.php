<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Student_fees_profile_management extends GeneralAuth_Controller {
	
	public function index()
	{
		$data['class_name']='';
		$data['feedback']='';
		$data['page_title']='Fees Profile Assignment';
		$data['year_enrolled'] = '';
		$this->load->library('Studentservice');
		$studentSvc = new Studentservice();
		
		
		$opts = new Optionsmanager();
		
		$data['fees_profile_id']='';
		$data['search_classi_id']=$this->input->post('search_classi_id');
		$data['search_year']=$this->input->post('search_year');;
		$data['years_enrollable'] = $opts->getFunctionalYearOptions('Any Year');
		$data['current_classes'] = $opts->getCurrentClasses('Any Class');
		$data['fees_profiles'] = $opts->getFeesProfileOptions();
		
		$data['hsearch_classi_id']=$this->input->post('hsearch_classi_id');;
		$data['hsearch_year']=$this->input->post('hsearch_year');;
		
		if($this->input->post('find_students')){
			$data['hsearch_classi_id']=$this->input->post('search_classi_id');;
			$data['hsearch_year']=$this->input->post('search_year');;
		}
		
		
		if($this->input->post('assign_to_profile')){
			
			$result = $studentSvc->assignStudents2FeesProfile($this->input->post('fees_profile_id')
						,$this->input->post('student-choices'));
			$data['feedback']=$result['msg'];
		}
		
		$critetia = array();
		
		$critetia['class_instance'] = $this->input->post('search_classi_id');
		$critetia['eyear'] = $this->input->post('year_enrolled');
		
		$data['students'] = $studentSvc->getStudents($critetia);
		
		$data['page']='student-management/student-fees-profile';
		
		$this->load->view('layout/default', $data);
	}
	
}

/* End of file student_class_assignment.php */
/* Location: ./application/controllers/student_class_assignment.php */
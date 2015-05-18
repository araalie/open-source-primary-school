<?php if (!defined('BASEPATH'))   exit('No direct script access allowed');

class Enrollment extends GeneralAuth_Controller {
	
	public function index() {
		
		$this->load->library('Studentservice');
		$studentSvc = new Studentservice();
		
		$opts = new Optionsmanager();
		
		$data['page_title'] = 'Student Enrollment Information';
		
		$data['title_list'] = $opts->getTitlesOptions();
		$data['statuses'] = $opts->getStudentStatusOptions();
		$data['genders'] = $opts->getGenderOptions();
		$data['years_enrollable'] = $opts->getFunctionalYears(TRUE);
		$data['current_classes'] = $opts->getCurrentClasses('No Class');
		$data['fees_profiles'] = $opts->getFeesProfileOptions();
		
		$data['student_address'] = '';
		$data['classi_id'] = '';
		$data['student_remarks'] = '';
		$data['fees_profile_id'] = '';
		$data['student_id'] = '';
		$data['house_name'] = '';
		$data['year_enrolled'] = '';
		$data['year_left'] = '';
		$data['student_gender'] = '';
		$data['status_id'] = '';
		$data['first_name'] = '';
		$data['surname'] = '';
		$data['dob'] = '';
		$data['other_names'] = '';
		$data['tel'] = '';
		$data['email'] = '';
		
		$data['page'] = 'student-management/enrollment';
		$data['students'] = $studentSvc-> getStudentList();
		
		$this->load->view('layout/default', $data);
	}
	
	public function edit($id = NULL) {
		
		$opts = new Optionsmanager();
		
		$this->load->library('Studentservice');
		
		$studentSvc = new Studentservice();
		
		$data['page_title'] = 'Student Enrollment Information';
		$data['title_list'] = $opts->getTitlesOptions();
		$data['statuses'] = $opts->getStudentStatusOptions();
		$data['genders'] = $opts->getGenderOptions();
		$data['current_classes'] = $opts->getCurrentClasses('No Class');
		$data['years_enrollable'] = $opts->getFunctionalYears(TRUE);
		$data['fees_profiles'] = $opts->getFeesProfileOptions();
		
		$data['classi_id'] = '';
		$data['student_address'] = '';
		$data['student_remarks'] = '';
		$data['dob'] = '';
		$data['student_id'] = '';
		$data['fees_profile_id'] = '';
		$data['year_enrolled'] = '';
		$data['year_left'] = '';
		$data['student_gender'] = '';
		$data['status_id'] = '';
		$data['house_name'] = '';
		$data['first_name'] = '';
		$data['surname'] = '';
		$data['other_names'] = '';
		$data['tel'] = '';
		$data['email'] = '';
		
		if ($this->input->post('first_name') && $this->input->post('surname')) { //POST
			
			$student = $studentSvc->editStudent($this->input->post());
			
			if (!is_null($student)) {
				redirect('enrollment/edit/' . $student->getId());
			}
			} 
		else {
			$student = $studentSvc->getStudent($id);
			
			if (!is_null($student)) {
				$data['student_id'] = $student->getId();
				$data['first_name'] = $student->getFirstName();
				$data['surname'] = $student->getSurname();
				$data['other_names'] = $student->getOtherNames();
				$data['student_gender'] = $student->getGender();
				$data['dob'] = '';
				
				if ($student->getDateOfBirth() != NULL) {
					$data['dob'] = $student->getDateOfBirth()->format('d-M-Y');
				}
				
				if($student->getHouse()){
					$data['house_id'] = $student->getHouse()->getId();	
					}                
				
				if($student->getClassInstance()){
					$data['classi_id'] = $student->getClassInstance()->getId();	
					}   
					
				$data['tel'] = $student->getTelephone();
				$data['email'] = $student->getEmail();
				
				$data['student_address'] = $student->getAddress();
				
				$data['year_start'] = $student->getYearEnrolled();
				$data['year_left'] = $student->getYearCompleted();
				
				if($student->getStudentStatus()){
					$data['status_id'] = $student->getStudentStatus()->getId();
				}
				if($student->getHouse()){
					$data['house_name'] = $student->getHouse()->getName();
				}
				
				if($student->getFeesProfile()){
					$data['fees_profile_id'] = $student->getFeesProfile()->getId();
				}
				
				$data['year_enrolled'] = $student->getYearEnrolled();
				$data['year_left'] = $student->getYearCompleted();
				
			}
		}
		
		$data['students'] = $studentSvc->getStudentList();
		
		$data['page'] = 'student-management/enrollment';
		
		$this->load->view('layout/default', $data);
	}
	
}

/* End of file enrollment.php */
/* Location: ./application/controllers/enrollment.php */
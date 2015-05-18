<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Staff_management extends GeneralAuth_Controller {
	
	public function __construct() {
		parent::__construct();
		AccessManager::only('DIRECTOR');
	}
		
	public function index()
	{
		
		$this->load->library('Staffservice');
		$staffSvc = new Staffservice();
		
		$opts = new Optionsmanager();
		
		$data['title_list']=$opts->getTitlesOptions();
		$data['job_title_list']=$opts->getJobTitlesOptions();
		$data['genders']=$opts->getGenderOptions();
		$data['years']=$opts->getFunctionalYears();
		
		$data['staff_address']='';
		$data['staff_remarks']='';
		$data['staff_id']='';
		$data['year_start']='';
		$data['year_end']='';
		$data['staff_gender']='';
		$data['staff_title_id']='';
		$data['job_title_id']='';
		$data['first_name']='';
		$data['surname']='';
		$data['dob']='';
		$data['other_names']='';
		$data['tel1']='';
		$data['tel2']='';
		$data['email']='';
		
		$data['page']='staff-management/main-panel';
		$data['staffs']= $staffSvc->getStaffList(); 
		
		$this->load->view('layout/default', $data);
	}
	
	
	public function edit($id = NULL){
		
		$opts = new Optionsmanager();
		
		$this->load->library('Staffservice');
		
		$staffSvc = new Staffservice();
		
		$data['title_list']=$opts->getTitlesOptions();
		$data['job_title_list']=$opts->getJobTitlesOptions();
		$data['genders']=$opts->getGenderOptions();
		$data['years']=$opts->getFunctionalYears();
		
		$data['staff_address']='';
		$data['staff_remarks']='';
		$data['dob']='';
		$data['staff_id']='';
		$data['year_start']='';
		$data['year_end']='';
		$data['staff_gender']='';
		$data['staff_title_id']='';
		$data['job_title_id']='';
		$data['first_name']='';
		$data['surname']='';
		$data['other_names']='';
		$data['tel1']='';
		$data['tel2']='';
		$data['email']='';
		
		if($this->input->post('first_name') && $this->input->post('surname') ){		
			
			$stf = $staffSvc->editStaff($this->input->post());
			
			if(!is_null($stf)){
				redirect('staff_management/edit/'.$stf->getId());
				
			}
			
			}else{
			$stf = $staffSvc->getStaff($id);
			
			if(!is_null($stf)){
				$data['staff_id']=$stf->getId();
				$data['first_name']=$stf->getFirstName();
				$data['surname']=$stf->getSurname();
				$data['other_names']=$stf->getOtherNames();
				$data['staff_gender']=$stf->getGender();
				$data['dob']='';
				if($stf->getDateOfBirth()!=NULL){
					$data['dob']=$stf->getDateOfBirth()->format('d-M-Y');	
				}
				$data['staff_title_id']=$stf->getTitle()->getId();
				
				$data['tel1']=$stf->getTelephone1();
				$data['tel2']=$stf->getTelephone2();
				$data['email']=$stf->getEmail();
				
				$data['staff_address']=$stf->getAddress();
				$data['staff_remarks']=$stf->getRemarks();
				
				$data['year_start']=$stf->getYearJoined();
				$data['year_end']=$stf->getYearLeft();
				
				if(!is_null($stf->getJobTitle())){
					$data['job_title_id']=$stf->getJobTitle()->getId();
				}
				
				}		
		}
		
		$data['staffs']= $staffSvc->getStaffList(); 
		$data['page']='staff-management/main-panel';
		
		$this->load->view('layout/default', $data);
		}	
	
}

/* End of file Staff_management.php */
/* Location: ./application/controllers/Staff_management.php */
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Academic_years extends GeneralAuth_Controller {

	public function __construct() {
		
		parent::__construct();
		
		AccessManager::only(array('DIRECTORY OF STUDIES','DIRECTOR'));
		
	}
		
	public function index()
	{
		
		$this->load->library('Academicservice');
		
		$acSvc = new Academicservice();
		
		$opts = new Optionsmanager();
		
		
		$data['academic_years']= $opts->getValidAcedmicYears();
		$data['year_id']='';
		$data['year_name']='';
		$data['remarks']='';
		$data['date_start']='';
		$data['date_end']='';
		$data['feedback']='';
		$data['readonly']='';
		
		$data['page']='academic/years';
		$data['page_title']='Academic Year Panel';
		
		$data['year_list']= $acSvc->getAllYears();
		
		$this->load->view('layout/default', $data);
	}
	
	
	public function edit($id=NULL)
	{
		
		$this->load->library('Academicservice');
		
		$acSvc = new Academicservice();
		$opts = new Optionsmanager();
		
		$data['academic_years']= $opts->getValidAcedmicYears();
		$data['year_id']='';
		$data['year_name']='';
		$data['remarks']='';
		$data['date_start']='';
		$data['date_end']='';
		$data['feedback']='';
		$data['readonly']='';
		
		if ($this->input->post('year_name') && $this->input->post('date_start')) { //POST
			
			$result = $acSvc->editYear($this->input->post());
			
			
			if($result['success']){
				$year = $result['year'];
				redirect('academic_years/edit/' . $year->getId());		
				}else
			{
				$data['year_name']=$this->input->post('year_name');
				$data['remarks']=$this->input->post('remarks');
				$data['date_start']=$this->input->post('date_start');
				$data['date_end']=$this->input->post('date_end');
				$data['feedback']=$result['msg'];	
			}
			
			
			} 
		else { //GET
			
			$Y = $acSvc->getYear($id);
			
			if (!is_null($Y)) {
				$data['year_name']=$Y->getName();
				$data['remarks']=$Y->getRemarks();
				$data['date_start']=$Y->getDateBegan()->format('Y-M-d');
				$data['date_end']=$Y->getDateEnded()->format('Y-M-d');
				$data['feedback']=$Y->getRemarks();
				$data['readonly']='readonly="readonly"';
			}
		}
		
		$data['year_list']= $acSvc->getAllYears();
		$data['page']='academic/years';
		$data['page_title']='Academic Year Panel';
		
		$this->load->view('layout/default', $data);
	}
	
}

/* End of file Staff_management.php */
/* Location: ./application/controllers/Staff_management.php */
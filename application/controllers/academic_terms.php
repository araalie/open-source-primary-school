<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Academic_terms extends GeneralAuth_Controller {

	public function __construct() {
		
		parent::__construct();
		
		AccessManager::only(array('DIRECTORY OF STUDIES','DIRECTOR'));
		
	}
		
	public function index()
	{
		
		$this->load->library('Academicservice');
		
		$acSvc = new Academicservice();
		
		$opts = new Optionsmanager();
		
		
		$data['academic_years']= $opts->getCreatedAcademicYears();
		$data['term_types']=$opts->getTermTypeOptions();
		$data['term_type_id']='';
		$data['readonly_term']='';
		
		$data['year_id']='';
		$data['term_id']='';
		$data['remarks']='';
		$data['date_start']='';
		$data['date_end']='';
		$data['feedback']='';
		$data['readonly']='';
		$data['term_name']='N/A';
		
		$data['page']='academic/terms';
		$data['page_title']='Academic Term Panel';
		
		$data['term_list']= $acSvc->getTerms();
		
		$this->load->view('layout/default', $data);
	}
	
	
	public function edit($id=NULL)
	{
		
		$this->load->library('Academicservice');
		
		$acSvc = new Academicservice();
		$opts = new Optionsmanager();
		
		$data['academic_years']= $opts->getCreatedAcademicYears();
		$data['term_types']=$opts->getTermTypeOptions();
		$data['term_type_id']='';
		$data['readonly_term']='';
		$data['term_id']='';
		$data['year_id']='';
		$data['term_name']='N/A';
		$data['remarks']='';
		$data['date_start']='';
		$data['date_end']='';
		$data['feedback']='';
		$data['readonly']='';
		
		if ($this->input->post('year_id') && $this->input->post('term_type_id')) { //POST
			
			$termResult = $acSvc->editTerm($this->input->post());
			
			if($termResult['success']){
				redirect('academic_terms/edit/' . $termResult['term']->getId());		
				}else
			{
				$data['term_id']=$this->input->post('term_id');
				$data['term_type_id']=$this->input->post('term_type_id');
				$data['year_id']=$this->input->post('year_id');
				$data['remarks']=$this->input->post('remarks');
				$data['date_start']=$this->input->post('date_start');
				$data['date_end']=$this->input->post('date_end');
				$data['feedback']=$termResult['msg'];
			}
			
			
			} 
		else { //GET
			
			$T = $acSvc->getTerm($id);
			
			if (!is_null($T)) {
				$data['term_id']=$T->getId();
				$data['term_name']=$T->getName();
				$data['year_id']=$T->getAcademicYear()->getId();
				$data['term_type_id']=$T->getTermType()->getId();
				$data['remarks']=$T->getDescription();
				$data['date_start']=$T->getDateBegan()->format('Y-M-d');
				$data['date_end']=$T->getDateEnded()->format('Y-M-d');
				$data['feedback']='';
				$data['readonly']='readonly="readonly"';
				$data['readonly_term']='readonly="readonly"';
			}
		}
		
		$data['term_list']= $acSvc->getTerms();
		$data['page']='academic/terms';
		$data['page_title']='Academic Terms Panel';
		
		$this->load->view('layout/default', $data);
	}
	
	public function make_current($id=NULL){
		
		$this->load->library('Academicservice');
		
		$acSvc = new Academicservice();
		
		$result = $acSvc->createCurrentTerm($id);
		
		if($result['success']){
			redirect('account/logout');
		}
		
		$data['result'] = $result;
		
		$data['page']='academic/current-term';
		$data['page_title']='Current term';		
		
		$this->load->view('layout/default', $data);
	}
}

/* End of file academic_terms.php */
/* Location: ./application/controllers/academic_terms.php */
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Class_creation extends GeneralAuth_Controller {

	public function __construct() {
		
		parent::__construct();
		
		AccessManager::only(array('DIRECTORY OF STUDIES','DIRECTOR'));
		
	}
		
	public function index()
	{
		
		$this->load->library('Academicservice');
		
		$acSvc = new Academicservice();
		
		$opts = new Optionsmanager();
		
		
		$data['class_types']= $opts->getCreatedClassTypes();
		$data['term_id']='';
		$data['term_name']='N/A';
		$data['classi_id']='';
		$data['readonly_term']='';
		
		$data['class_type_id']='';
		$data['remarks']='';
		$data['feedback']='';
		$data['readonly']='';
		$data['class_name']='N/A';
		
		$data['page']='academic/class-instances';
		$data['page_title']='Class Management';
		
		$data['class_list']= $acSvc->getCurrentClassInstances();
		
		
		$term = Utilities::getCurrentTerm();
		
		if(!is_null($term)){
			$data['term_id']=$term->getId();
			$data['term_name']=$term->getName();
			}	
		
		$this->load->view('layout/default', $data);
	}
	
	
	public function edit($id=NULL)
	{
		
		$this->load->library('Academicservice');		
		$acSvc = new Academicservice();		
		$opts = new Optionsmanager();
		
		
		$data['class_types']= $opts->getCreatedClassTypes();
		$data['term_id']='';
		$data['term_name']='N/A';
		$data['classi_id']='';
		$data['readonly_term']='';
		
		$data['class_type_id']='';
		$data['remarks']='';
		$data['feedback']='';
		$data['readonly']='';
		$data['class_name']='N/A';
		
		$data['page']='academic/class-instances';
		$data['page_title']='Class Management';
		
		$data['class_list']= $acSvc->getCurrentClassInstances();
		
		
				$term = Utilities::getCurrentTerm();
		
		if(!is_null($term)){
			$data['term_id']=$term->getId();
			$data['term_name']=$term->getName();
			}
			
		if ($this->input->post('term_id') && $this->input->post('class_type_id')) { //isPOST
			
			$classiResult = $acSvc->editClassInstance($this->input->post());
			
			if($classiResult['success']){
				redirect('class_creation/edit/' . $classiResult['classi']->getId());		
				}else
			{
				$data['class_type_id']=$this->input->post('class_type_id');
				$data['remarks']=$this->input->post('remarks');
				$data['feedback']=$classiResult['msg'];
			}
			
			
			} 
		else { //GET
			
			$Ci = $acSvc->getClassInstance($id);
			
			if (!is_null($Ci)) {
				$data['class_name']=$Ci->getName();
				$data['class_type_id']=$Ci->getClassType()->getId();
				$data['remarks']=$Ci->getDescription();
				$data['readonly']='readonly="readonly"';
				$data['readonly_term']='readonly="readonly"';
			}
		}
		
		$data['term_list']= $acSvc->getTerms();
		$data['page']='academic/class-instances';
		$data['page_title']='Academic Terms Panel';
		
		$this->load->view('layout/default', $data);
	}
	
}

/* End of file Academic_terms.php */
/* Location: ./application/controllers/Academic_terms.php */
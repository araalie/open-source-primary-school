<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Fees_management extends GeneralAuth_Controller {

	public function __construct() {
		
		parent::__construct();
		
		AccessManager::only(array('BURSAR','DIRECTOR'));
		
	}
		
	public function index()
	{
	$data['class_name']='';
		$data['page_title']='School Fees Management';
		
		$this->load->library('Accountsservice');
		
		$acSvc = new Accountsservice();
		
		$opts = new Optionsmanager();
		
		$data['feedback']='';
		$data['classi_id']='';			
		$data['current_classes'] = $opts->getCurrentClasses();
		$data['fees'] = $acSvc->getClassFeesStructureDisplay(NULL);
		$data['page']='accounts/fees-setting-panel';
		
		$this->load->view('layout/default', $data);
	}
	
	public function edit($id=NULL){
		
		$data['class_name']='';
		$data['page_title']='School Fees Management';
		
		$this->load->library('Accountsservice');
		
		$acSvc = new Accountsservice();
		
		$opts = new Optionsmanager();
		
		$data['classi_id']='';
		$data['feedback']='';
		//POST
		if($this->input->post('classi_id') && intval($this->input->post('classi_id'))>0){

			if($this->uri->segment(3)==$this->input->post('classi_id')){
				
				$result = $acSvc->updateClassFeeStructure($this->input->post());
				$data['classi_id']=$this->input->post('classi_id');		
				$data['feedback']=$result['msg'];
				$data['fees'] = $acSvc->getClassFeesStructureDisplay($this->input->post('classi_id'));
				
				}else{
					redirect('fees_management/edit/'.$this->input->post('classi_id'));
			}
		}
		//end POST
		else{//GET
			
			if(is_null($id)){		
				redirect('fees_management/');	
			}
			$data['fees'] = $acSvc->getClassFeesStructureDisplay($id);
			$data['classi_id']=$id;			
		}
		
		$data['current_classes'] = $opts->getCurrentClasses();
		
		$data['class_name']=$data['current_classes'][$data['classi_id']];
		
		$data['page']='accounts/fees-setting-panel';
		
		$this->load->view('layout/default', $data);
	}
	
}

/* End of file fees_management.php */
/* Location: ./application/controllers/fees_management.php */
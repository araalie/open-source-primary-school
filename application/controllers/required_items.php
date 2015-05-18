<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Required_items extends GeneralAuth_Controller {

	public function __construct() {
		
		parent::__construct();
		
		AccessManager::only(array('BURSAR', 'DIRECTOR'));
		
	}	
		
	public function index()
	{
		
		$this->load->library('Accountsservice');
		$accSvc = new Accountsservice();
		
		$opts = new Optionsmanager();
		
		$data['item_list']= $accSvc->getAllRequiredItems();
		
		$data['fees_profile_id']='';	
		$data['fees_profiles'] = $opts->getCostingGroupOptions();		
		$data['item_id']='';
		$data['item_name']='';
		$data['description']='';
		$data['page']='meta/required-items';
		
		$this->load->view('layout/default', $data);
	}
	
	
	public function edit($id=NULL)
	{
		
		$this->load->library('Accountsservice');
		$accSvc = new Accountsservice();
		$opts = new Optionsmanager();
		
		$data['item_list']= $accSvc->getAllRequiredItems();
		
		$data['fees_profile_id']='';	
		$data['fees_profiles'] = $opts->getCostingGroupOptions();		
		$data['item_id']='';
		$data['item_name']='';
		$data['description']='';
		
		
		if($this->input->post('item_name')){		
			$Item = $accSvc->editRequiredItem($this->input->post());
			
			if(!is_null($Item)){
				$data['item_id']=$Item->getId();
				$data['item_name']=$Item->getName();
				$data['description']=$Item->getDescription();	
				if($Item->getFeesProfile()){
					$data['fees_profile_id']=$Item->getFeesProfile()->getId();
					}		
				}		
			}else{
			$Item = $accSvc->getRequiredItem($id);
			if(!is_null($Item)){
				$data['item_id']=$Item->getId();
				$data['item_name']=$Item->getName();
				$data['description']=$Item->getDescription();			
				if($Item->getFeesProfile()){
					$data['fees_profile_id']=$Item->getFeesProfile()->getId();
					}	
				}		
		}
		
		$data['item_list']= $accSvc->getAllRequiredItems();
		
		$data['page']='meta/required-items';
		
		$this->load->view('layout/default', $data);
	}
}

/* End of file required_items.php */
/* Location: ./application/controllers/required_items.php */
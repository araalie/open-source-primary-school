<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Student_requirements_management extends GeneralAuth_Controller {
	
	public function index()
	{
		$this->load->library('Accountsservice');		
		$acSvc = new Accountsservice();
		
		$data['feedback']='';
		
		if ($this->input->post('save_prices')) {
			
			$this->load->library('Accountsservice');		
			$acSvc = new Accountsservice();
			
			$acSvc->updateRequirementsCosts($this->input->post());
		}
		
		
		$data['page']='accounts/item-costs';
		$data['page_title']='Item Prices for this Term';
		
		$data['prices'] = $acSvc->getItemsCostStructure();
		
		$this->load->view('layout/default', $data);
	}
	
}

/* End of file student_requirements_management.php */
/* Location: ./application/controllers/student_requirements_management.php */
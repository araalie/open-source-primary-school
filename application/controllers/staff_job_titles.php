<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Staff_job_titles extends GeneralAuth_Controller {
	
	public function index()
	{
		
		$this->load->library('JobTitlemanager');
		
		
		$titleSvc = new JobTitlemanager();
		
		$data['title_list']= $titleSvc->getAllJobTitles();
		
		$data['title_id']='';
		$data['title_name']='';
		$data['title_description']='';
		$data['page']='meta/job-titles';
		
		$this->load->view('layout/default', $data);
	}
	
	
	public function edit($id=NULL)
	{
		$this->load->library('JobTitlemanager');
				
		$data['title_id']='';
		$data['title_name']='';
		$data['title_description']='';
		
		$titleSvc = new JobTitlemanager();
		
		if($this->input->post('title_name')){		
			$title = $titleSvc->editJobTitle($this->input->post('title_name')
			,$this->input->post('title_description'), $this->input->post('title_id'));
			
			if(!is_null($title)){
				$data['title_id']=$title->getId();
				$data['title_name']=$title->getName();
				$data['title_description']=$title->getDescription();			
				}		
		}else{
			$title = $titleSvc->getJobTitle($id);
			if(!is_null($title)){
				$data['title_id']=$title->getId();
				$data['title_name']=$title->getTitle();
				$data['title_description']=$title->getDescription();			
				}		
		}
		
		$data['title_list']= $titleSvc->getAllJobTitles();
		
		$data['page']='meta/job-titles';
		
		$this->load->view('layout/default', $data);
	}
}

/* End of file titles.php */
/* Location: ./application/controllers/titles.php */
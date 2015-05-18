<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class People_titles extends GeneralAuth_Controller {

	public function __construct() {
		
		parent::__construct();
		
		AccessManager::only(array('DIRECTOR','SYSTEM ADMINISTRATOR'));
		
	}
		
	public function index()
	{
		
		$this->load->library('Titlemanager');

		$titleSvc = new Titlemanager();
		
		$data['title_list']= $titleSvc->getAllTitles();
		
		$data['title_id']='';
		$data['title_name']='';
		$data['title_description']='';
		$data['page']='meta/people-titles';
		
		$this->load->view('layout/default', $data);
	}
	
	
	public function edit($id=NULL)
	{
		$this->load->library('Titlemanager');
		
		$data['title_id']='';
		$data['title_name']='';
		$data['title_description']='';
		
		$titleSvc = new Titlemanager();
		
		if($this->input->post('title_name')){		
			$title = $titleSvc->editTitle($this->input->post('title_name')
			,$this->input->post('title_description'), $this->input->post('title_id'));
			
			if(!is_null($title)){
				$data['title_id']=$title->getId();
				$data['title_name']=$title->getName();
				$data['title_description']=$title->getDescription();			
				}		
		}else{
			$title = $titleSvc->getTitle($id);
			if(!is_null($title)){
				$data['title_id']=$title->getId();
				$data['title_name']=$title->getName();
				$data['title_description']=$title->getDescription();			
				}		
		}
		
		$data['title_list']= $titleSvc->getAlltitles();
		
		$data['page']='meta/people-titles';
		
		$this->load->view('layout/default', $data);
	}
}

/* End of file people_titles.php */
/* Location: ./application/controllers/people_titles.php */
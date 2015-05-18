<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Search_students extends GeneralAuth_Controller {

	public function __construct() {
		
		parent::__construct();
		
		AccessManager::only(array('SECRETARY', 'BURSAR', 'DIRECTOR', 'HEAD TEACHER', 'REGISTRAR'));
		
	}
	
		
	public function index()
	{
		$this->load->library('Studentservice');
		
		$stSvc = new Studentservice();
		
		$data = $this->get_defaults();
		
		$data['page_title']='Find students';
		$data['page']='student-management/search';
		$data['found']='No search yet';
		$data['hits']=NULL;

		if($this->input->post()){
			
			$results = $stSvc->findStudents($this->input->post());
			
			$data['results'] = $results;
			
			if($results['total']>0){
				$data['found']=$results['total'].' Student(s) Found.';
				$data['hits']=$results['data'];
			}else{
				$data['found']='No Students Matched your search criteria.';
			}
			
			
		$data['search_name'] = $this->input->post('search_name');
		$data['sstd_num'] = $this->input->post('sstd_num');
		$data['sstudent_status'] = $this->input->post('sstudent_status');
		
			/*echo $results['total'];
			foreach($results['data'] as $d){
				echo '<br/>'.$d->getSurname().' - '.$d->getFirstName();
			}*/
		}
		
		$this->load->view('layout/default', $data);
	}
	
	
	private function get_defaults(){
		$data1 = array();
		
		$data1['search_name'] = NULL;
		$data1['sstd_num'] = NULL;
		$data1['sstudent_status'] = NULL;
		
		$this->load->library('optionsmanager');
		
		$optMan = new Optionsmanager();
		
		$data1['states'] = $optMan->getStudentStatusOptions('Any');
		
		return $data1;
	}
	
}

/* End of file grading.php */
/* Location: ./application/controllers/grading.php */
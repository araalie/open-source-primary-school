<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Reports_classes extends GeneralAuth_Controller {

	public function index() {

		$this -> load -> library('Optionsmanager');

		$opSvc = new Optionsmanager();

		$data['classis'] = $opSvc -> getCurrentClassInstances(NULL, 'Select a class');

		$data['page'] = 'reports/general/classes';

		$this -> load -> view('layout/default', $data);
	}

	public function get_students() {

		$classi_id = intval($this -> input -> post('class_instance_id'));

		if ($classi_id > 0) {
			$this -> load -> library('Studentservice');
			$stSvc = new Studentservice();

			$enrolled = $stSvc -> getStudentStatusByName('CURRENTLY_ENROLLED');

			$hits = $stSvc -> getStudentsAsArray(array('class_instance' => $classi_id, 'status' => $enrolled -> getId()));

			$stats = array('Gender' => GroupByKeySummary($hits, 'gender'));
			$stats['Houses'] = GroupByKeySummary($hits, 'house');
			$stats['Fees Profiles'] = GroupByKeySummary($hits, 'fees_profile');


			$this -> load -> library('Academicservice');
			$acSvc = new Academicservice();
			
			$cl = $acSvc->getClassInstance($classi_id);
			
			$clname = '';
			
			if(!is_null($cl)){
				$clname = $cl->getName();
			}

			WriteLog( LogAction::Reports, 'report:class', $classi_id, 'Accessing class report - '.$clname );
			
			echo json_encode(array('success' => TRUE, 'hits' => $hits, 'stats' => $stats));

		} else {
			echo json_encode(array('success' => FALSE, 'msg' => 'No Students'));
		}

	}


	public function class_list($classiId)
	{
		$this->load->library('Reportservice');
		$repSvc = new Reportservice();
		
		$repSvc->getClassList($classiId);
	}
}

/* End of file Reports_classes.php */
/* Location: ./application/controllers/Reports_classes.php */

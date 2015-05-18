<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Classes_subject_assignment extends GeneralAuth_Controller {

	public function __construct() {

		parent::__construct();

		AccessManager::only(array('DIRECTORY OF STUDIES', 'DIRECTOR'));

	}

	public function index() {
		$this -> load -> library('Studysubjectservice');
		$sSvc = new Studysubjectservice();
		$opts = new Optionsmanager();

		$data['classi_id'] = '';
		$data['classies'] = $opts -> getCurrentClassInstances();
		$data['feedback'] = '';

		if ($this -> input -> post('save_subjects')) {//isPOST

			$data['classi_id'] = $this -> input -> post('classi_id');

			$sSvc -> manageClassSubjects($this -> input -> post());

		} else {
			$data['classies'] = array('' => 'No Subject Selected') + $opts -> getCurrentClassInstances();
		}

		$duals = $opts -> getSubjectsforDualist($this -> input -> post('classi_id'));
		$data['classi_id'] = $this -> input -> post('classi_id');

		$data['possible_subjects'] = $duals['new'];
		$data['attached_subjects'] = $duals['old'];

		$data['page'] = 'study-subjects/subject-assignment';

		$this -> load -> view('layout/default', $data);
	}

}

/* End of file classes_subject_assignment.php */
/* Location: ./application/controllers/classes_subject_assignment.php */

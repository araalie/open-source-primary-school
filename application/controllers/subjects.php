<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Subjects extends GeneralAuth_Controller {

	public function __construct() {

		parent::__construct();

		AccessManager::only(array('DIRECTORY OF STUDIES', 'DIRECTOR'));

	}

	public function index() {
		$this -> load -> library('Studysubjectservice');

		$subSvc = new Studysubjectservice();

		if ($this -> input -> post('subject_name')) {
			$subSvc -> editSubject($this -> input -> post('subject_name'), $this -> input -> post('subject_description'), $this -> input -> post('subject_id'));
		}

		$data['subject_list'] = $subSvc -> getAllSubjects();

		$data['subject_id'] = '';
		$data['subject_name'] = '';
		$data['subject_description'] = '';
		$data['page'] = 'study-subjects/main';

		$this -> load -> view('layout/default', $data);
	}

	public function edit($id = NULL) {
		$this -> load -> library('Studysubjectservice');

		$data['subject_id'] = '';
		$data['subject_name'] = '';
		$data['subject_description'] = '';

		$subSvc = new Studysubjectservice();

		if ($this -> input -> post('subject_name')) {
			$sub = $subSvc -> editSubject($this -> input -> post('subject_name'), $this -> input -> post('subject_description'), $this -> input -> post('subject_id'));

			if (!is_null($sub)) {
				$data['subject_id'] = $sub -> getId();
				$data['subject_name'] = $sub -> getName();
				$data['subject_description'] = $sub -> getDescription();
			}
		} else {
			$sub = $subSvc -> getSubject($id);
			if (!is_null($sub)) {
				$data['subject_id'] = $sub -> getId();
				$data['subject_name'] = $sub -> getName();
				$data['subject_description'] = $sub -> getDescription();
			}
		}

		$data['subject_list'] = $subSvc -> getAllSubjects();

		$data['page'] = 'study-subjects/main';

		$this -> load -> view('layout/default', $data);
	}

}

/* End of file subjects.php */
/* Location: ./application/controllers/subjects.php */

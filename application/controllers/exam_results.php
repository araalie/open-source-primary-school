<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Exam_results extends GeneralAuth_Controller {

	public function __construct() {

		parent::__construct();

		AccessManager::only(array('DIRECTORY OF STUDIES', 'DIRECTOR'));

	}

	public function index() {
		$this -> load -> library('Optionsmanager');
		$oSvc = new Optionsmanager();

		$this -> load -> library('Gradingmanager');
		$gradingMan = new Gradingmanager();

		$data['page_title'] = 'Class Exam Results Processing';
		$data['page'] = 'exams/marks/results';

		$data['feedback'] = '';
		$data['selected_exam'] = '';
		$data['current_exams'] = $oSvc -> getCurrentExamsListing();
		$data['my_classes'] = array();
		$data['my_subjects'] = array();
		$data['schemes'] = $gradingMan -> getAllGradingSchemes();

		$this -> load -> view('layout/default', $data);
	}

	public function get_class_marks() {

		$this -> load -> library('Exammanager');
		$exMan = new Exammanager();

		$class = $this -> input -> post('class');
		$exam = $this -> input -> post('exam');
		echo json_encode($exMan -> getClassResults($class, $exam));
	}

	public function apply_grading() {

		$this -> load -> library('Exammanager');
		$exMan = new Exammanager();

		$class = $this -> input -> post('class');
		$exam = $this -> input -> post('exam');
		$subject_gradings = $this -> input -> post('subject_gradings');

		echo json_encode($exMan -> applyGradingSchemes($class, $exam, $subject_gradings));
	}

	public function classes_in_exam($exaId) {
		$this -> load -> library('Optionsmanager');
		$oSvc = new Optionsmanager();

		echo json_encode($oSvc -> getMyCurrentExamsClasses($exaId));
	}

	public function seal_class_exam() {

		$this -> load -> library('Exammanager');
		$exMan = new Exammanager();

		$class = $this -> input -> post('class');
		$exam = $this -> input -> post('exam');

		echo json_encode($exMan -> closeClassExam($class, $exam));
	}

}

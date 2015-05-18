<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Reports_class_level_fees extends GeneralAuth_Controller {

	public function __construct() {
		parent::__construct();

	}


	public function index($id=-1, $freq_name='TERMINAL') {

		$this -> load -> library('Accountsservice');		
		$data['actSvc'] = new Accountsservice();
		
		$this -> load -> library('Academicservice');		
		$acSvc = new Academicservice();

		$this -> load -> library('Feesreportservice');		
		$frSvc = new Feesreportservice();		
		
		
		$activeClasses = $acSvc->getPopulatedClasses();
		
		$data['active_students']=NULL;
		$data['active_klass']=NULL;
		$data['freq_name']=$freq_name;
		
		if(intval($id)>0){
			$data['active_students'] = $frSvc->getClassFeesProjection($id, $freq_name);
			$data['active_klass']=$id;
		}
		
		$data['active_classes']=$activeClasses;
		$data['page'] = 'reports/fees/class-projections';
		
		// REMOVE AT NEXT UPDATE!!!!!!!!!!!!
		$acSvc->closeClassInstances();
		
		$this -> load -> view('layout/default', $data);		
	}


	public function excel($id, $freq_name) {

		$this -> load -> library('Reportservice');		
		$rSvc = new Reportservice();
		
		if(intval($id)>0){
			$rSvc->getClassFeesStatus($id, $freq_name);			
		}
	
	}

}

/* End of file Reports_class_level_fees.php */
/* Location: ./application/controllers/Reports_class_level_fees.php */

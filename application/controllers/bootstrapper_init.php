<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bootstrapper_init  extends GeneralAuth_Controller {

	public function __construct() {
		
		parent::__construct();
		
		AccessManager::only('DIRECTOR');
		
	}	
	
	public function index()
	{
		
		$this->load->library('Bootstrapper');
		
		$bootSvc = new Bootstrapper();
		
		//$bootSvc->reInitHouseAssignments();
		//echo $bootSvc->createAcademicYearStatusDefaults();
		//echo $bootSvc->createTermStatusDefaults();
		//echo $bootSvc->createTermTypesDefaults();
		//echo $bootSvc->createSchoolDivisionDefaults();
		//echo $bootSvc->createClassTypeDefaults();
		//echo $bootSvc->createKinTypesDefaults();
		//echo $bootSvc->createClassInstanceStatusDefaults();
		//echo $bootSvc->createClassInstanceSubjectStatusDefaults();
		//echo $bootSvc->createAccountTypesDefaults();
		//echo $bootSvc->createUserGroupsDefaults();
		//echo $bootSvc->createFeeTypesDefaults();
		//echo $bootSvc->createSchoolAccounts();
		//echo $bootSvc->createTransactionTypesDefaults();
		//echo $bootSvc->createFeesProfileDefaults();
		
		//$this->load->library('Housemanager');
		
		//$hSvc = new Housemanager();
		
		//print_r($hSvc->getNextHouse());
		
		//echo $bootSvc->createGradingStatusDefaults();
		
		//echo $bootSvc->createStudentStatusDefaults();
		
		//echo $bootSvc->createExamStatusDefaults();
		
		//echo $bootSvc->createStaffStatusDefaults();
		
		//echo $bootSvc->createSystemAccountStatusDefaults();
		//echo $bootSvc->createFeesFrequencyDefaults();
		
		//print_r($bootSvc->testUser(19));
		
		//$bootSvc->initUsers();
		
		//echo $bootSvc->reInitStudentNumbers();
		
		# September 2012
		//echo $bootSvc->createExamMarksStatusDefaults();
		//echo $bootSvc->createExamResultsStatusDefaults();
		
		
		#september 29.
		/*
		 * set foreign_key_checks=0;
			TRUNCATE `exam_results_status`;
			set foreign_key_checks=1;*/
		//echo $bootSvc->createExamResultsStatusDefaults();
		
		//october 1
		
		//1.
		//$bootSvc->createGradingModeDefaults();
		
		//2.
		//$bootSvc->setDefaultModes();
		
		//3.
		//$bootSvc->createSubjectStatusDefaults();
		
		//october 13th
		//1. run u.bat
		
		//2. create new states
		//echo $bootSvc->createFeesSummaryStatusDefaults();
		
		//3. update states of summaries
		//$bootSvc->updateFeesSummaries();
		
		
		//october 17th
		//$bootSvc->createDebtsStatusDefaults();
		//$bootSvc->createDebtTypes();
		
		show_404('page');
		
	}
	
	public function import_8976534567ngf67yg_656bg3rrFG6yus()
	{
		
		$t = getdate();
		
		if($t[0]> 1339504760)
		{
			echo 'Nothing to do - it is '.$t[0];
			return;
		}
		
		$this->load->helper('file');
		$string = read_file('./tmp/init_4f475.csv');
		
		$recs = explode(chr(13),$string);
		shuffle($recs);
		foreach($recs as $r)
		{
			
			$fields = explode(',',$r);
			
			$form = array();
			
			$form['student_id']=$form['year_end']=$form['student_address']=$form['telephone']=$form['student_email']='';
			
			$form['student_gender'] = $fields[4];
			$form['classi_id']=  $fields[6];'';
			$form['first_name'] = $fields[1];
			$form['surname'] = $fields[0];
			$form['student_gender'] = $fields[4];
			$form['other_names'] =  $fields[2];
			
			$form['year_enrolled']  = $fields[3];
			$form['dob']=makeRandomDoB((2012 -$fields[7]).'-01-01');
			
			print_r($form);
			
			$this->load->library('Studentservice');	
			
			$ss = new Studentservice();
			
			$ss->editStudent($form);
		}
	}

	public function g0897b7h(){
		$this->load->library('Academicservice');
		
		$acSvc = new Academicservice();
		
		print_r($acSvc->getPopulatedClasses());
	}	
}

/* End of file bootstrapper_init.php */
/* Location: ./application/controllers/bootstrapper_init.php */
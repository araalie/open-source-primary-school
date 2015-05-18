<?php
class Bootstrapper {

	function __construct() {
		$this -> CI = &get_instance();
	}

	public function createFeesProfileDefaults() {
		$states = array('day scholar' => array('Day Scholars', 'NO'), 'boarding scholar' => array('Boarders', 'NO'), 'both day and boarding' => array('All students', 'YES'));

		$errs = '';
		foreach ($states as $key => $value) {

			$st = new Entities\FeesProfile;

			$st -> setName(strtoupper($key));
			$st -> setDescription($value[0]);
			$st -> setCostingOnly($value[1] == 'YES');
			$st -> setDateCreated(new DateTime());
			$st -> setDateLastModified(new DateTime());
			$st -> setIsValid(1);

			$this -> CI -> doctrine -> em -> persist($st);

			try {
				$this -> CI -> doctrine -> em -> flush();
			} catch( \PDOException $e ) {
				$errs .= $e -> getMessage() . '|<br/><br/>';
			}

			$errs .= 'SUCCESS : ' . $key . '|<br/><br/>';
		}

		return $errs;

	}

	public function createGradingMode000067txsfr4Defaults() {
		$states = array('BY TOTAL MARKS SCORED' => 'Grading by Total Marks out of 100% scored', 'BY DISTINCTION/CREDIT' => 'Grading by distinction or credit scored according to grading scheme');

		$errs = '';
		foreach ($states as $key => $value) {

			$st = new Entities\ExamStatus;

			$st -> setName($key);
			$st -> setDescription($value);
			$st -> setDateCreated(new DateTime());
			$st -> setDateLastModified(new DateTime());
			$st -> setIsValid(1);

			$this -> CI -> doctrine -> em -> persist($st);

			try {
				$this -> CI -> doctrine -> em -> flush();
			} catch( \PDOException $e ) {
				$errs .= $e -> getMessage() . '|<br/><br/>';
			}

			$errs .= 'SUCCESS : ' . $key . '|<br/><br/>';
		}

		return $errs;

	}

	public function createExamStatusDefaults() {
		$states = array('NEW' => 'New - can be edited', 'ARCHIVED' => 'Permanent - cannot be edited anymore');

		$errs = '';
		foreach ($states as $key => $value) {

			$st = new Entities\ExamStatus;

			$st -> setName($key);
			$st -> setDescription($value);
			$st -> setDateCreated(new DateTime());
			$st -> setDateLastModified(new DateTime());
			$st -> setIsValid(1);

			$this -> CI -> doctrine -> em -> persist($st);

			try {
				$this -> CI -> doctrine -> em -> flush();
			} catch( \PDOException $e ) {
				$errs .= $e -> getMessage() . '|<br/><br/>';
			}

			$errs .= 'SUCCESS : ' . $key . '|<br/><br/>';
		}

		return $errs;

	}

	public function createExamResultsStatusDefaults() {
		$states = array('NEW' => 'New - can be edited', 'INCOMPLETE_MARKS_ENTRY' => 'Not all results entered.', 'COMPLETED_MARKS_ENTRY' => 'All marks have been entered.', 'ARCHIVED' => 'Permanent - cannot be edited anymore');

		$errs = '';
		foreach ($states as $key => $value) {

			$st = new Entities\ExamResultsStatus;

			$st -> setName($key);
			$st -> setDescription($value);
			$st -> setDateCreated(new DateTime());
			$st -> setDateLastModified(new DateTime());
			$st -> setIsValid(1);

			$this -> CI -> doctrine -> em -> persist($st);

			try {
				$this -> CI -> doctrine -> em -> flush();
			} catch( \PDOException $e ) {
				$errs .= $e -> getMessage() . '|<br/><br/>';
			}

			$errs .= 'SUCCESS : ' . $key . '|<br/><br/>';
		}

		return $errs;

	}

	public function createExamMarksStatusDefaults() {
		$states = array('GRADED' => 'Assigned marks/grade', 'ARCHIVED' => 'Permanent - cannot be edited anymore');

		$errs = '';
		foreach ($states as $key => $value) {

			$st = new Entities\ExamDoneStatus;

			$st -> setName($key);
			$st -> setDescription($value);
			$st -> setDateCreated(new DateTime());
			$st -> setDateLastModified(new DateTime());
			$st -> setIsValid(1);

			$this -> CI -> doctrine -> em -> persist($st);

			try {
				$this -> CI -> doctrine -> em -> flush();
			} catch( \PDOException $e ) {
				$errs .= $e -> getMessage() . '|<br/><br/>';
			}

			$errs .= 'SUCCESS : ' . $key . '|<br/><br/>';
		}

		return $errs;

	}

	public function createSubjectStatusDefaults() {
		$this -> CI -> load -> library('Studysubjectservice');

		$ss = new Studysubjectservice();

		$ss -> createSubjectStatusDefaults();
	}

	public function createAcademicYearStatusDefaults() {
		$states = array('NEW' => 'A new Academic Year', 'IN_PROGRESS' => 'Academic year has started', 'CLOSED' => 'This year is now closed [Its End date has elapsed]');

		$errs = '';
		foreach ($states as $key => $value) {

			$YrState = new Entities\AcademicYearStatus;

			$YrState -> setName($key);
			$YrState -> setDescription($value);
			$YrState -> setDateCreated(new DateTime());
			$YrState -> setDateLastModified(new DateTime());
			$YrState -> setIsValid(1);

			$this -> CI -> doctrine -> em -> persist($YrState);

			try {
				$this -> CI -> doctrine -> em -> flush();
			} catch( \PDOException $e ) {
				$errs .= $e -> getMessage() . '|<br/><br/>';
			}

			$errs .= 'SUCCESS : ' . $key . '|<br/><br/>';
		}

		return $errs;

	}

	public function setDefaultModes() {

		$this -> CI -> load -> library('Gradingmanager');

		$gMan = new Gradingmanager();

		$gMan -> setDefaultGradingModes();

	}

	public function reInitHouseAssignments() {

		$this -> CI -> load -> library('Housemanager');

		$hSvc = new Housemanager();

		$status = $this -> CI -> doctrine -> em -> getRepository('Entities\StudentStatus') -> findOneBy(array('name' => 'CURRENTLY_ENROLLED'));

		$query = $this -> CI -> doctrine -> em -> createQuery('SELECT s FROM Entities\Student s LEFT JOIN s.house h' . ' LEFT JOIN s.student_status j WHERE s.student_status = ?1 AND s.house IS NULL');
		$query -> setParameter(1, $status);
		$students = $query -> getResult();

		foreach ($students as $k) {
			if (is_null($k -> getHouse())) {
				$k -> setHouse($this -> CI -> doctrine -> em -> getReference('Entities\House', $hSvc -> getNextHouse()));
				$this -> CI -> doctrine -> em -> persist($k);
				$this -> CI -> doctrine -> em -> flush();
			}
		}

	}

	public function reInitStudentNumbers() {

		$this -> CI -> load -> library('Studentservice');

		$Svc = new Studentservice();

		$query = $this -> CI -> doctrine -> em -> createQuery('SELECT s FROM Entities\Student s');

		$students = $query -> getResult();

		$i = 0;
		foreach ($s1tudents as $k) {
			//$k -> setStudentNumber($Svc -> createStudentNumber($k -> getYearEnrolled()));
			//$this -> CI -> doctrine -> em -> persist($k);
			//$this -> CI -> doctrine -> em -> flush();
			$i++;
		}

		echo $i;

	}

	public function createClassInstanceStatusDefaults() {
		$states = array('NEW' => 'Created for a given Term Type and Class', 'ACTIVE' => 'Class has students and subjects assigned to it', 'CLOSED' => 'Class is archived with end of term');

		$errs = '';
		foreach ($states as $key => $value) {

			$CiState = new Entities\ClassInstanceStatus;

			$CiState -> setName($key);
			$CiState -> setDescription($value);
			$CiState -> setDateCreated(new DateTime());
			$CiState -> setDateLastModified(new DateTime());
			$CiState -> setIsValid(1);

			$this -> CI -> doctrine -> em -> persist($CiState);

			try {
				$this -> CI -> doctrine -> em -> flush();
			} catch( \PDOException $e ) {
				$errs .= $e -> getMessage() . '|<br/><br/>';
			}

			$errs .= 'SUCCESS : ' . $key . '|<br/><br/>';
		}

		return $errs;

	}

	public function createFeesSummaryStatusDefaults() {
		$states = array('CURRENT' => 'Open for payments', 'OWING' => 'Uncleared compulsary fees', 'DEBTS CLEARED' => 'Previous debts have been cleared after the period', 'ALL PAID' => 'All compulsary fees were paid in the proper term/period');

		$errs = '';
		foreach ($states as $key => $value) {

			$CiState = new Entities\FeesSummaryStatus;

			$CiState -> setName($key);
			$CiState -> setDescription($value);
			$CiState -> setDateCreated(new DateTime());
			$CiState -> setDateLastModified(new DateTime());
			$CiState -> setIsValid(1);

			$this -> CI -> doctrine -> em -> persist($CiState);

			try {
				$this -> CI -> doctrine -> em -> flush();
			} catch( \PDOException $e ) {
				$errs .= $e -> getMessage() . '|<br/><br/>';
			}

			$errs .= 'SUCCESS : ' . $key . '|<br/><br/>';
		}

		return $errs;

	}

	public function createTermStatusDefaults() {
		$states = array('NEW' => 'A new Term [Must be attached to an Academic Year]', 'IN_PROGRESS' => 'Term in progress [Students/Classes attached to it, exams created]', 'CLOSED' => 'This term is now closed [Its End date has elapsed - No marks can be edited]');

		$errs = '';
		foreach ($states as $key => $value) {

			$tState = new Entities\TermStatus;

			$tState -> setName($key);
			$tState -> setDescription($value);
			$tState -> setDateCreated(new DateTime());
			$tState -> setDateLastModified(new DateTime());
			$tState -> setIsValid(1);

			$this -> CI -> doctrine -> em -> persist($tState);

			try {
				$this -> CI -> doctrine -> em -> flush();
			} catch( \PDOException $e ) {
				$errs .= $e -> getMessage() . '|<br/><br/>';
			}

			$errs .= 'SUCCESS : ' . $key . '|<br/><br/>';
		}

		return $errs;
	}

	public function createSchoolDivisionDefaults() {
		$terms = array('Nursery' => 'Nursery', 'Primary' => 'Primary Section', 'Secondary' => 'Secondary');

		$errs = '';
		foreach ($terms as $key => $value) {

			$div = new Entities\SchoolDivision;

			$div -> setName(strtoupper($key));
			$div -> setDescription($value);
			$div -> setDateCreated(new DateTime());
			$div -> setDateLastModified(new DateTime());
			$div -> setIsValid(1);

			$this -> CI -> doctrine -> em -> persist($div);

			try {
				$this -> CI -> doctrine -> em -> flush();
			} catch( \PDOException $e ) {
				$errs .= $e -> getMessage() . '|<br/><br/>';
			}

			$errs .= 'SUCCESS : ' . $key . '|<br/><br/>';
		}

		return $errs;

	}

	public function createTermTypesDefaults() {
		$terms = array('First Term' => '1st Term', 'Second Term' => '2nd Term', 'Third Term' => '3rd and Final Term');

		$errs = '';
		foreach ($terms as $key => $value) {

			$termT = new Entities\TermType;

			$termT -> setName($key);
			$termT -> setDescription($value);
			$termT -> setDateCreated(new DateTime());
			$termT -> setDateLastModified(new DateTime());
			$termT -> setIsValid(1);

			$this -> CI -> doctrine -> em -> persist($termT);

			try {
				$this -> CI -> doctrine -> em -> flush();
			} catch( \PDOException $e ) {
				$errs .= $e -> getMessage() . '|<br/><br/>';
			}

			$errs .= 'SUCCESS : ' . $key . '|<br/><br/>';
		}

		return $errs;

	}

	public function createClassTypeDefaults() {
		$nursery = $this -> CI -> doctrine -> em -> getRepository('Entities\SchoolDivision') -> findOneBy(array('name' => 'NURSERY'));
		$primary = $this -> CI -> doctrine -> em -> getRepository('Entities\SchoolDivision') -> findOneBy(array('name' => 'PRIMARY'));

		$nurseryClasses = array('BABY CLASS' => '0 to 3 Year Olds', 'MIDDLE CLASS' => ' 3 - 4 Year olds', 'TOP CLASS' => 'Older Toddlers');

		$errs = '';
		if ($nursery) {
			foreach ($nurseryClasses as $key => $value) {

				$ct = new Entities\ClassType;

				$ct -> setName(strtoupper($key));
				$ct -> setSchoolDivision($nursery);
				$ct -> setDescription($value);
				$ct -> setDateCreated(new DateTime());
				$ct -> setDateLastModified(new DateTime());
				$ct -> setIsValid(1);

				$this -> CI -> doctrine -> em -> persist($ct);

				try {
					$this -> CI -> doctrine -> em -> flush();
				} catch( \PDOException $e ) {
					$errs .= $e -> getMessage() . '|<br/><br/>';
				}

				$errs .= 'SUCCESS : ' . $key . '|<br/><br/>';
			}
		}

		$primaryClasses = array('Primary One' => 'P1', 'Primary Two' => 'P2', 'Primary Three' => 'P3', 'Primary Four' => 'P4', 'Primary Five' => 'P5', 'Primary Six' => 'P6', 'Primary Seven' => 'P7');

		if ($primary) {
			foreach ($primaryClasses as $key => $value) {

				$ct = new Entities\ClassType;

				$ct -> setName(strtoupper($key));
				$ct -> setSchoolDivision($primary);
				$ct -> setDescription($value);
				$ct -> setDateCreated(new DateTime());
				$ct -> setDateLastModified(new DateTime());
				$ct -> setIsValid(1);

				$this -> CI -> doctrine -> em -> persist($ct);

				try {
					$this -> CI -> doctrine -> em -> flush();
				} catch( \PDOException $e ) {
					$errs .= $e -> getMessage() . '|<br/><br/>';
				}

				$errs .= 'SUCCESS : ' . $key . '|<br/><br/>';
			}
		}

		return $errs;

	}

	//default class instance subject statuses
	public function createClassInstanceSubjectStatusDefaults() {
		$states = array('NEW' => 'Created for a given Term Type and Class', 'CLOSED' => 'Class is archived with end of term');

		$errs = '';
		foreach ($states as $key => $value) {

			$CiState = new Entities\ClassInstanceSubjectStatus;

			$CiState -> setName($key);
			$CiState -> setDescription($value);
			$CiState -> setDateCreated(new DateTime());
			$CiState -> setDateLastModified(new DateTime());
			$CiState -> setIsValid(1);

			$this -> CI -> doctrine -> em -> persist($CiState);

			try {
				$this -> CI -> doctrine -> em -> flush();
			} catch( \PDOException $e ) {
				$errs .= $e -> getMessage() . '|<br/><br/>';
			}

			$errs .= 'SUCCESS : ' . $key . '|<br/><br/>';
		}

		return $errs;

	}

	//end

	public function createKinTypesDefaults() {
		$kins = array('Father' => 'Biological father', 'Primary' => 'Biological mother', 'Brother' => 'Brother', 'Sister' => 'Sister', 'Uncle' => 'Uncle', 'Aunt' => 'Auntie', 'Grandfather' => 'Grandfather', 'Grandmother' => 'Grandmother', 'Guardian' => 'A caretaker', 'Other Relative');

		$errs = '';
		foreach ($kins as $key => $value) {

			$div = new Entities\KinType;

			$div -> setName(strtoupper($key));
			$div -> setDescription($value);
			$div -> setDateCreated(new DateTime());
			$div -> setDateLastModified(new DateTime());
			$div -> setIsValid(1);

			$this -> CI -> doctrine -> em -> persist($div);

			try {
				$this -> CI -> doctrine -> em -> flush();
			} catch( \PDOException $e ) {
				$errs .= $e -> getMessage() . '|<br/><br/>';
			}

			$errs .= 'SUCCESS : ' . $key . '|<br/><br/>';
		}

		return $errs;

	}

	public function createAccountTypesDefaults() {
		$acs = array('STUDENT' => 'Student Account', 'SCHOOL' => 'School Account', 'STAFF' => 'Staff Account');

		$errs = '';
		foreach ($acs as $key => $value) {

			$ac = new Entities\AccountType;

			$ac -> setName($key);
			$ac -> setDescription($value);
			$ac -> setDateCreated(new DateTime());
			$ac -> setDateLastModified(new DateTime());
			$ac -> setIsValid(1);

			$this -> CI -> doctrine -> em -> persist($ac);

			try {
				$this -> CI -> doctrine -> em -> flush();
			} catch( \PDOException $e ) {
				$errs .= $e -> getMessage() . '|<br/><br/>';
			}

			$errs .= 'SUCCESS : ' . $key . '|<br/><br/>';
		}

		return $errs;

	}

	public function createTransactionTypesDefaults() {
		$acs = array('FEES DEPOSIT' => 'Fees deposit by student.', 'WITHDRAWAL' => 'Withdrawal from school account.', 'TRANSFER' => 'Transfer from one account to another', 'ESCROW' => 'Pending obligations/reimbursements', 'BURSARY' => "Award to student by school");

		$acs = array('BURSARY' => "Award to student by school");

		$errs = '';
		foreach ($acs as $key => $value) {

			$ac = new Entities\TransactionType;

			$ac -> setName($key);
			$ac -> setDescription($value);
			$ac -> setDateCreated(new DateTime());
			$ac -> setDateLastModified(new DateTime());
			$ac -> setIsValid(1);

			$this -> CI -> doctrine -> em -> persist($ac);

			try {
				$this -> CI -> doctrine -> em -> flush();
			} catch( \PDOException $e ) {
				$errs .= $e -> getMessage() . '|<br/><br/>';
			}

			$errs .= 'SUCCESS : ' . $key . '|<br/><br/>';
		}

		return $errs;

	}

	public function createUserGroupsDefaults() {
		$groups = array('SYSTEM ADMINISTRATOR' => 'Can create user accounts, manage system, etc', 'DIRECTOR' => 'Can view finance, reports', 'TEACHER' => 'Can edit student marks for his/her subject', 'REGISTRAR' => 'Enrolls new students', 'HEAD TEACHER' => 'Super teacher', 'DIRECTOR OF STUDIES' => 'Manages subjects', 'ACCOUNT' => 'Manages finances', 'MATRON' => 'Cares for students in boarding', 'SECRETARY' => '', 'INVENTORY MANAGER' => 'Manages inventory');

		$errs = '';
		foreach ($groups as $key => $value) {

			$group = new Entities\UserGroup;

			$group -> setName($key);
			$group -> setDescription($value);
			$group -> setDateCreated(new DateTime());
			$group -> setDateLastModified(new DateTime());
			$group -> setIsValid(1);

			$this -> CI -> doctrine -> em -> persist($group);

			try {
				$this -> CI -> doctrine -> em -> flush();
			} catch( \PDOException $e ) {
				$errs .= $e -> getMessage() . '|<br/><br/>';
			}

			$errs .= 'SUCCESS : ' . $key . '|<br/><br/>';
		}

		return $errs;

	}

	public function createFeeTypesDefaults() {
		$acs = array('FEES' => 'Main fees', 'UNIFORM' => 'Uniform', 'SPORTS WEAR' => 'Sports Wear', 'FOOD' => 'Food', 'CASUAL WEAR' => '', 'BOARDING FEES' => 'Boarding fees', 'SWEATER' => 'School sweater', 'BUILDING FEE' => 'School construction');

		$errs = '';
		foreach ($acs as $key => $value) {

			$ac = new Entities\StudentFeeType;

			$ac -> setName($key);
			$ac -> setNarrative($value);
			$ac -> setDateCreated(new DateTime());
			$ac -> setDateLastModified(new DateTime());
			$ac -> setIsValid(1);

			$this -> CI -> doctrine -> em -> persist($ac);

			try {
				$this -> CI -> doctrine -> em -> flush();
			} catch( \PDOException $e ) {
				$errs .= $e -> getMessage() . '|<br/><br/>';
			}

			$errs .= 'SUCCESS : ' . $key . '|<br/><br/>';
		}

		return $errs;

	}

	public function createSchoolAccounts() {
		$this -> CI -> load -> library('Accountsservice');
		$acSvc = new Accountsservice();

		$acSvc -> createSchoolAccounts();
	}

	public function createGradingStatusDefaults() {
		$states = array('NEW' => 'New - can be edited', 'USED' => 'Permanent - cannot be edited');

		$errs = '';
		foreach ($states as $key => $value) {

			$st = new Entities\GradingStatus;

			$st -> setName($key);
			$st -> setDescription($value);
			$st -> setDateCreated(new DateTime());
			$st -> setDateLastModified(new DateTime());
			$st -> setIsValid(1);

			$this -> CI -> doctrine -> em -> persist($st);

			try {
				$this -> CI -> doctrine -> em -> flush();
			} catch( \PDOException $e ) {
				$errs .= $e -> getMessage() . '|<br/><br/>';
			}

			$errs .= 'SUCCESS : ' . $key . '|<br/><br/>';
		}

		return $errs;

	}

	public function createStudentStatusDefaults() {
		$states = array('CURRENTLY_ENROLLED' => 'Currently Enrolled', 'COMPLETED_PRIMARY_7' => 'Completed P7', 'DISCONTINUED' => 'Did not complete P7 at school', 'LEFT_AT_OWN_WILL' => 'Chose not to complete P7 at school', 'COMPLETED_NURSERY_SECTION' => 'Completed Nursery Section');

		$errs = '';
		foreach ($states as $key => $value) {

			$st = new Entities\StudentStatus;

			$st -> setName($key);
			$st -> setDescription($value);
			$st -> setDateCreated(new DateTime());
			$st -> setDateLastModified(new DateTime());
			$st -> setIsValid(1);

			$this -> CI -> doctrine -> em -> persist($st);

			try {
				$this -> CI -> doctrine -> em -> flush();
				$errs .= 'SUCCESS : ' . $key . '|<br/><br/>';
			} catch( \PDOException $e ) {
				$errs .= $e -> getMessage() . '|<br/><br/>';
			}
		}

		return $errs;
	}

	public function createStaffStatusDefaults() {
		$states = array('ACTIVE' => 'Currently Employed', 'RETIRED' => 'Retired', 'LEFT_AFTER_CONTRACT_EXPIRY' => 'Chose not to renew contract', 'RESIGNED' => 'Resigned', 'DISMISSED' => 'Dismissed');

		$errs = '';
		foreach ($states as $key => $value) {

			$st = new Entities\StaffStatus;

			$st -> setName($key);
			$st -> setDescription($value);
			$st -> setDateCreated(new DateTime());
			$st -> setDateLastModified(new DateTime());
			$st -> setIsValid(1);

			$this -> CI -> doctrine -> em -> persist($st);

			try {
				$this -> CI -> doctrine -> em -> flush();
				$errs .= 'SUCCESS : ' . $key . '| ';
			} catch( \PDOException $e ) {
				$errs .= $e -> getMessage() . '<br/><br/>';
			}
		}

		return $errs;
	}

	public function createFeesFrequencyDefaults() {

		$ff = array('ANNUAL' => 'Paid Once every year', 'TERMINAL' => 'Per term/terminal payments', 'ONCE' => 'One time', 'AD HOC' => 'Any time payments', 'HOLIDAY' => 'Holiday, extra-terminal');

		$errs = '';
		foreach ($ff as $key => $value) {

			$st = new Entities\FeeFrequencyType;

			$st -> setName($key);
			$st -> setDescription($value);
			$st -> setDateCreated(new DateTime());
			$st -> setDateLastModified(new DateTime());
			$st -> setIsValid(1);

			$this -> CI -> doctrine -> em -> persist($st);

			try {
				$this -> CI -> doctrine -> em -> flush();
				$errs .= 'SUCCESS : ' . $key . '| ';
			} catch( \PDOException $e ) {
				$errs .= $e -> getMessage() . '<br/><br/>';
			}
		}

		return $errs;
	}

	public function createSystemAccountStatusDefaults() {
		$states = array('UNUSED' => 'New Account', 'ACTIVE' => 'Active with at least 1 login', 'SUSPENDED' => 'Suspended system account', 'STAFF_NO_LONGER_EMPLOYEE' => 'Staff left');

		$errs = '';
		foreach ($states as $key => $value) {

			$st = new Entities\SystemAccountStatus;

			$st -> setName($key);
			$st -> setDescription($value);
			$st -> setDateCreated(new DateTime());
			$st -> setDateLastModified(new DateTime());
			$st -> setIsValid(1);

			$this -> CI -> doctrine -> em -> persist($st);

			try {
				$this -> CI -> doctrine -> em -> flush();
				$errs .= 'SUCCESS : ' . $key . '| ';
			} catch( \PDOException $e ) {
				$errs .= $e -> getMessage() . '<br/><br/>';
			}
		}

		return $errs;
	}

	public function createGradingModeDefaults() {

		$states = array('TOTAL_GRADING' => 'Total score for subjects <>4', 'AGGREGATE_GRADING' => 'For exactly 4 subjects for upper primary');

		$errs = '';
		foreach ($states as $key => $value) {

			$gm = new Entities\GradingMode;

			$gm -> setName($key);
			$gm -> setDescription($value);
			$gm -> setDateCreated(new DateTime());
			$gm -> setDateLastModified(new DateTime());
			$gm -> setIsValid(1);

			$this -> CI -> doctrine -> em -> persist($gm);

			try {
				$this -> CI -> doctrine -> em -> flush();
			} catch( \PDOException $e ) {
				$errs .= $e -> getMessage() . '|<br/><br/>';
			}

			$errs .= 'SUCCESS : ' . $key . '|<br/><br/>';
		}

		return $errs;

	}

	public function updateFeesSummaries() {
		
		$this -> CI -> load -> library('Accountsservice');
		$acSvc = new Accountsservice();
		
		$acSvc->utilityUpdateFeesSummaries();

	}


	public function createDebtsStatusDefaults() {
		$states = array('OUTSTANDING' => 'Not yet cleared', 'SETTLED' => 'Cleared');

		$errs = '';
		foreach ($states as $key => $value) {

			$st = new Entities\DebtStatus;

			$st -> setName($key);
			$st -> setDescription($value);
			$st -> setDateCreated(new DateTime());
			$st -> setDateLastModified(new DateTime());
			$st -> setIsValid(1);

			$this -> CI -> doctrine -> em -> persist($st);

			try {
				$this -> CI -> doctrine -> em -> flush();
			} catch( \PDOException $e ) {
				$errs .= $e -> getMessage() . '|<br/><br/>';
			}

			$errs .= 'SUCCESS : ' . $key . '|<br/><br/>';
		}

		return $errs;

	}
	
	
		public function createDebtTypes() {
			
		$kins = array('Fees' => 'Unpaid Fees', 'Personal' => 'Individual debt', 'Other'=>'Other debt');

		$errs = '';
		foreach ($kins as $key => $value) {

			$dtype = new Entities\DebtType;

			$dtype -> setName(strtoupper($key));
			$dtype -> setDescription($value);
			$dtype -> setDateCreated(new DateTime());
			$dtype -> setDateLastModified(new DateTime());
			$dtype -> setIsValid(1);

			$this -> CI -> doctrine -> em -> persist($dtype);

			try {
				$this -> CI -> doctrine -> em -> flush();
			} catch( \PDOException $e ) {
				$errs .= $e -> getMessage() . '|<br/><br/>';
			}

			$errs .= 'SUCCESS : ' . $key . '|<br/><br/>';
		}

		return $errs;

	}
}
?>
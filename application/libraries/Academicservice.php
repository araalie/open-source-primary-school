<?php

class Academicservice {

	function __construct() {
		$this -> CI = &get_instance();
	}

	public function editYear($form) {

		$newYr = new Entities\AcademicYear;

		if ($form['date_start'] == '' || $form['date_end'] == '') {
			return array('success' => FALSE, 'msg' => 'error|Invalid Date Range|Start and End Dates are required.');
		}

		$d1 = new DateTime($form['date_start']);
		$d2 = new DateTime($form['date_end']);

		if ($d1 >= $d2) {
			return array('success' => FALSE, 'msg' => 'error|Invalid Date Range|End date CANNOT be before Start date for an Academic Year');
		}

		$interval = date_diff($d2, $d1);
		$days = $interval -> format('%a');

		if ($days < 8 * 30) {

			return array('success' => FALSE, 'msg' => 'error|Too Few Months|An Academic Year Must have at least 8 months between start and end.');

		}

		$id = $form['year_id'];

		if (is_null($id) || $id == '') {

			$newYr -> setName($form['year_name']);

			$newYr -> setRemarks($form['remarks']);

			$newYr -> setDateBegan($d1);
			$newYr -> setDateEnded($d2);
			$newYr -> setAcademicYearStatus($this -> getYearStatusByName('NEW'));

			$newYr -> setDateCreated(new DateTime());
			$newYr -> setDateLastModified(new DateTime());
			$newYr -> setIsValid(1);
		} else {
			$newYr = $this -> CI -> doctrine -> em -> find('Entities\AcademicYear', $id);

			if ($newYr == NULL) {
				return array('success' => FALSE, 'msg' => 'error|Year not found|This Academic Year Does not Exist');
			}

			$newYr -> setRemarks($form['remarks']);

			$newYr -> setDateBegan($d1);
			$newYr -> setDateEnded($d2);

			$newYr -> setDateLastModified(new DateTime());
			$newYr -> setIsValid(1);

		}

		$this -> CI -> doctrine -> em -> persist($newYr);

		try {
			$this -> CI -> doctrine -> em -> flush();
		} catch( \PDOException $e ) {
			return array('success' => FALSE, 'msg' => 'error|Failure: Year was not saved.|' . $e -> getMessage());
		}

		return array('success' => TRUE, 'year' => $newYr);
	}

	public function editTerm($form) {

		$newTm = new Entities\Term;

		if ($form['date_start'] == '' || $form['date_end'] == '') {
			return array('success' => FALSE, 'msg' => 'error|Invalid Date Range|Start and End Dates are required for the TERM to be valid.');
		}

		$d1 = new DateTime($form['date_start']);
		$d2 = new DateTime($form['date_end']);

		if ($d1 >= $d2) {
			return array('success' => FALSE, 'msg' => 'error|Invalid Date Range|End date CANNOT be before Start date for a TERM.');
		}

		$interval = date_diff($d2, $d1);
		$days = $interval -> format('%a');

		if ($days < 5) {

			return array('success' => FALSE, 'msg' => 'error|Too Few Days|A Term must have at least 5 days between start and end.');

		}

		//check

		$acYr = $this -> CI -> doctrine -> em -> find('Entities\AcademicYear', $form['year_id']);

		if (is_null($acYr)) {
			return array('success' => FALSE, 'msg' => 'error|No Year Specified|No Academic Year has been selected.');
		}

		$ttype = $this -> CI -> doctrine -> em -> find('Entities\TermType', $form['term_type_id']);

		if (is_null($ttype)) {
			return array('success' => FALSE, 'msg' => 'error|No Term Specifide|No Term category has been provided.');
		}

		$id = $form['term_id'];

		if (is_null($id) || $id == '') {

			$generatedTermName = '[' . $ttype -> getName() . '] - [' . $acYr -> getName() . ']';

			$chkTerm = $this -> CI -> doctrine -> em -> getRepository('Entities\Term') -> findOneBy(array('name' => $generatedTermName));

			if (!is_null($chkTerm)) {
				return array('success' => FALSE, 'msg' => 'error|Conflict|' . $acYr -> getName() . ' already exists in Academic year ' . $ttype -> getName());
			}

			$result = $this -> checkTermOverlap($d1, $d2);

			if (!is_null($result)) {
				return array('success' => FALSE, 'msg' => 'error|Conflicting Dates|' . $result);
			}

			$newTm -> setAcademicYear($acYr);
			$newTm -> setName($generatedTermName);
			$newTm -> setTermType($ttype);

			$newTm -> setDescription($form['remarks']);

			$newTm -> setDateBegan($d1);
			$newTm -> setDateEnded($d2);
			$newTm -> setTermStatus($this -> getTermStatusByName('NEW'));

			$newTm -> setDateCreated(new DateTime());
			$newTm -> setDateLastModified(new DateTime());
			$newTm -> setIsValid(1);
		} else {
			$newTm = $this -> CI -> doctrine -> em -> find('Entities\Term', $id);

			if ($newTm == NULL) {
				return array('success' => FALSE, 'msg' => 'error|Year not found|This Term Does not Exist');
			}

			$result = $this -> checkTermOverlap($d1, $d2, $id);

			if (!is_null($result)) {
				return array('success' => FALSE, 'msg' => 'error|Conflicting Dates|' . $result);
			}

			$newTm -> setDescription($form['remarks']);

			$newTm -> setDateBegan($d1);
			$newTm -> setDateEnded($d2);

			$newTm -> setDateLastModified(new DateTime());
			$newTm -> setIsValid(1);

		}

		$this -> CI -> doctrine -> em -> persist($newTm);

		try {
			$this -> CI -> doctrine -> em -> flush();
		} catch( \PDOException $e ) {
			WriteLog(LogAction::EditTerm, 'term-creation-edit', -1, $e -> getMessage());
			return array('success' => FALSE, 'msg' => 'error|Failure: Term was not saved.|' . $e -> getMessage());
		}

		$prevT = $newTm -> getPreviousTerm();

		if (is_null($prevT)) {
			$previousTerm = $this -> getPreviousTerm($d1);

			if (!is_null($previousTerm)) {

				$previousTerm -> setNextTerm($newTm);
				$previousTerm -> setDateLastModified(new DateTime());

				$newTm -> setPreviousTerm($previousTerm);
				$newTm -> setDateLastModified(new DateTime());

				$this -> CI -> doctrine -> em -> persist($newTm);
				$this -> CI -> doctrine -> em -> persist($previousTerm);

				try {
					$this -> CI -> doctrine -> em -> flush();
				} catch( \PDOException $e ) {
					WriteLog(LogAction::EditTerm, 'term-creation-edit', -1, $e -> getMessage());
				}

			}
		}

		return array('success' => TRUE, 'term' => $newTm);
	}

	//Edit real class instance
	public function editClassInstance($form) {

		//check
		$term = $this -> CI -> doctrine -> em -> find('Entities\Term', $form['term_id']);

		if (is_null($term)) {
			return array('success' => FALSE, 'msg' => 'error|No Term Specified|A Term must be selected.');
		}

		$ctype = $this -> CI -> doctrine -> em -> find('Entities\ClassType', $form['class_type_id']);

		if (is_null($ctype)) {
			return array('success' => FALSE, 'msg' => 'error|No Class Specifide|No Class category has been provided.');
		}

		$newCi = new Entities\ClassInstance;

		$id = $form['classi_id'];

		if (is_null($id) || $id == '') {

			$generatedClassiName = '[' . $ctype -> getName() . '] - ' . $term -> getName();

			$chkCi = $this -> CI -> doctrine -> em -> getRepository('Entities\ClassInstance') -> findOneBy(array('name' => $generatedClassiName));

			if (!is_null($chkCi)) {
				return array('success' => FALSE, 'msg' => 'error|Conflicting Class|' . $chkCi -> getName() . ' already exists.');
			}

			$newCi = new Entities\ClassInstance;

			$newCi -> setTerm($term);
			$newCi -> setName($generatedClassiName);

			$newCi -> setDescription($form['remarks']);
			$newCi -> setClassType($ctype);
			$newCi -> setClassInstanceStatus($this -> getClassStatusByName('NEW'));

			$newCi -> setDateCreated(new DateTime());
			$newCi -> setDateLastModified(new DateTime());
			$newCi -> setIsValid(1);
		} else {
			$newCi = $this -> CI -> doctrine -> em -> find('Entities\ClassInstance', $id);

			if ($newCi == NULL) {
				return array('success' => FALSE, 'msg' => 'error|Year not found|This Term Does not Exist');
			}
			$newCi -> setDescription($form['remarks']);
			$newCi -> setDateLastModified(new DateTime());
		}

		$this -> CI -> doctrine -> em -> persist($newCi);

		try {
			$this -> CI -> doctrine -> em -> flush();
		} catch( \PDOException $e ) {
			WriteLog(LogAction::TermSwitch, 'auto-class', $form['term_id'], $e -> getMessage());
			return array('success' => FALSE, 'msg' => 'error|Failure: Class was not created.|' . $e -> getMessage());
		}

		return array('success' => TRUE, 'classi' => $newCi);
	}

	//end of edit class instance

	public function autoCreateClassInstances($termId) {

		$term = $this -> CI -> doctrine -> em -> find('Entities\Term', $termId);

		if (is_null($term)) {
			return array('success' => FALSE, 'msg' => 'Invalid Term');
		}

		$classTypes = $this -> getClassTypes();

		foreach ($classTypes as $c) {
			$idetails = array();

			$idetails['classi_id'] = NULL;
			$idetails['term_id'] = $termId;
			$idetails['class_type_id'] = $c -> getId();
			$idetails['remarks'] = 'Automatically created at new term';

			$this -> editClassInstance($idetails);
		}

	}

	public function getPreviousTerm($nearestDate) {
		$date = $nearestDate;
		//new DateTime($nearestDate);

		$queryBuilder = $this -> CI -> doctrine -> em -> createQueryBuilder();

		$queryBuilder -> select('t') -> from('Entities\Term', 't') -> where(':d1 > t.date_ended ') -> orderBy('t.date_ended', 'DESC') -> setParameter('d1', $date);

		$query = $queryBuilder -> getQuery();

		$results = $query -> getResult();

		if (is_array($results) && count($results) > 0) {
			return $results[0];
			// latest previous term
		}

		return NULL;
	}

	public function getYear($id) {
		return $this -> CI -> doctrine -> em -> find('Entities\AcademicYear', $id);
	}

	public function getTerm($id) {

		if (is_null($id)) {
			return NULL;
		}

		return $this -> CI -> doctrine -> em -> find('Entities\Term', $id);
	}

	public function getClassInstance($id) {

		if (is_null($id)) {
			return NULL;
		}

		return $this -> CI -> doctrine -> em -> find('Entities\ClassInstance', $id);
	}

	public function getAllYears() {

		$query = $this -> CI -> doctrine -> em -> createQuery('SELECT y FROM Entities\AcademicYear y LEFT JOIN y.academic_year_status s');

		return $query -> getResult();

	}

	public function getTerms() {

		$query = $this -> CI -> doctrine -> em -> createQuery('SELECT t FROM Entities\Term t LEFT JOIN t.term_status s JOIN t.academic_year a ORDER BY t.date_began DESC');

		return $query -> getResult();
	}

	public function getClassTypes() {

		$query = $this -> CI -> doctrine -> em -> createQuery('SELECT t FROM Entities\ClassType t WHERE t.is_valid=1 ORDER BY t.level');

		return $query -> getResult();
	}

	private function checkTermOverlap($startDate, $endDate, $yId = NULL) {

		if (is_null($yId)) {

			$queryBuilder = $this -> CI -> doctrine -> em -> createQueryBuilder();

			$queryBuilder -> select('t') -> from('Entities\Term', 't') -> where('t.date_began <= :d1 AND :d1 <=t.date_ended ') -> orWhere('t.date_began <= :d2 AND :d2 <=t.date_ended ') -> setParameter('d1', $startDate) -> setParameter('d2', $endDate);

			$query = $queryBuilder -> getQuery();

			$result = $query -> getResult();

			if (!is_null($result)) {
				$msg = NULL;

				foreach ($result as $t) {
					if (is_null($msg)) {
						$msg = 'The dates specified conflict with these terms: ';
					}
					$msg .= $t -> getName();
				}

				return $msg;
			}

		} else {

			$queryBuilder = $this -> CI -> doctrine -> em -> createQueryBuilder();

			$queryBuilder -> select('t') -> from('Entities\Term', 't') -> where('t.date_began <= :d1 AND :d1 <=t.date_ended ') -> orWhere('t.date_began <= :d2 AND :d2 <=t.date_ended ') -> andWhere('t.id != :id') -> setParameter('d1', $startDate) -> setParameter('d2', $endDate) -> setParameter('id', $yId);

			$query = $queryBuilder -> getQuery();

			$result = $query -> getResult();

			if (!is_null($result)) {
				$msg = NULL;

				foreach ($result as $t) {
					if (is_null($msg)) {
						$msg = 'The dates specified conflict with these terms: ';
					}
					$msg .= $t -> getName() . '<br/>';
				}

				return $msg;
			}
		}
		return NULL;
	}

	public function getYearStatusByName($state) {

		$st = $this -> CI -> doctrine -> em -> getRepository('Entities\AcademicYearStatus') -> findOneBy(array('name' => $state));

		return $st;

	}

	public function getTermStatusByName($state) {

		$st = $this -> CI -> doctrine -> em -> getRepository('Entities\TermStatus') -> findOneBy(array('name' => $state));

		return $st;
	}

	public function getClassStatusByName($state) {

		$st = $this -> CI -> doctrine -> em -> getRepository('Entities\ClassInstanceStatus') -> findOneBy(array('name' => $state));

		return $st;

	}

	public function getTermsInstances() {

		$queryBuilder = $this -> CI -> doctrine -> em -> createQueryBuilder();

		$queryBuilder -> select('t') -> from('Entities\Term', 't');

		$query = $queryBuilder -> getQuery();

		return $query -> getResult();
	}

	public function getCurrentClassInstances($criteria = NULL) {

		$term = Utilities::getCurrentTerm();

		if (is_null($term)) {
			return NULL;
		}

		$query = $this -> CI -> doctrine -> em -> createQuery('SELECT c FROM Entities\ClassInstance c LEFT JOIN c.class_instance_status s
		 JOIN c.class_type y JOIN c.term t WHERE t.id=?1 ORDER BY y.school_division,  y.level');
		$query -> setParameter(1, $term -> getId());

		return $query -> getResult();
	}

	public function createCurrentTerm($termId) {

		$currentTerm = Utilities::getCurrentTerm(TRUE);

		if (is_null($currentTerm)) {
			return array('success' => FALSE, 'msg' => 'No Current Term');
		}

		$this -> CI -> load -> library('Accountsservice');

		$acSvc = new Accountsservice();

		$defs = $acSvc -> getCurrentTermDefaulterSummary();
		
		$opResult = array();

		if ($defs['success'] && ($defs['count'] > 0)) {
			$ds = $defs['defaulters'];
			$i = 0;
			foreach ($ds as $d) {
				$debtDetail = $d;

				$debtDetail['narrative'] = 'Unpaid compulsary fees';
				$res1 = $acSvc -> createFeesDebt($debtDetail);
				//print_r($debtDetail);
				//echo '<br/>';
				$i++;
			}

		}

		$res2 = $acSvc -> closePaidUpFeesSummaries();

		$newCurrentTerm = $this -> CI -> doctrine -> em -> find('Entities\Term', $termId);

		if (is_null($newCurrentTerm)) {
			return array('success' => FALSE, 'msg' => 'Unknown Term');
		} else {
			$newCurrentTerm -> setTermStatus($this -> getTermStatusByName('IN_PROGRESS'));
			$newCurrentTerm -> setDateLastModified(new DateTime());
			$newCurrentTerm -> setIsValid(1);

			$this -> CI -> doctrine -> em -> persist($newCurrentTerm);

			//retire old current term
			$currentTerm -> setTermStatus($this -> getTermStatusByName('CLOSED'));
			$currentTerm -> setDateLastModified(new DateTime());

			$this -> CI -> doctrine -> em -> persist($currentTerm);

			try {
				$this -> CI -> doctrine -> em -> flush();
				WriteLog(LogAction::TermSwitch, 'term-switch', $newCurrentTerm -> getId(), 'New term has started: ' . $newCurrentTerm -> getName());
			} catch( \PDOException $e ) {
				WriteLog(LogAction::TermSwitch, 'term-switch', -1, $e -> getMessage());
				return array('success' => FALSE, 'msg' => 'error|Failure: Current Term could not be set.|' . $e -> getMessage());
			}

			$this -> autoCreateClassInstances($newCurrentTerm -> getId());
			
			$this->closeClassInstances();

			return array('success' => TRUE, 'term' => $newCurrentTerm);
		}

	}

	public function getPopulatedClasses() {

		$sql = "SELECT ci.id, ci.term_id, ci.name FROM class_instance ci 
				JOIN class_instance_status s ON s.id = ci.class_instance_status_id AND s.name='ACTIVE'
				JOIN class_type ct ON ct.id = ci.class_type_id
				JOIN school_division sd ON sd.id = ct.school_division_id
				where ci.id IN(SELECT DISTINCT class_instance_id FROM student st JOIN student_status ss ON ss.id=st.student_status_id AND ss.name='CURRENTLY_ENROLLED')
				ORDER BY sd.id, ct.`level`";

		$rsm = new \Doctrine\ORM\Query\ResultSetMapping;
		$rsm -> addScalarResult('id', 'id');
		$rsm -> addScalarResult('term_id', 'term_id');
		$rsm -> addScalarResult('name', 'name');

		$query = $this -> CI -> doctrine -> em -> createNativeQuery($sql, $rsm);
		$results = $query -> getArrayResult();

		return $results;
	}


	//private methods

	public function closeClassInstances() {

		$currentTerm = Utilities::getCurrentTerm(TRUE);

		if (is_null($currentTerm)) {
			return array('success' => FALSE, 'msg' => 'No Current Term');
		}

		$query = $this -> CI -> doctrine -> em -> createQuery('SELECT c FROM Entities\ClassInstance c JOIN c.class_instance_status s' . ' JOIN c.term t WHERE t.id=?1 AND s.name=?2');

		$query -> setParameter(1, $currentTerm -> getId());
		$query -> setParameter(2, 'NEW');

		$currentClassesToActivate = $query -> getResult();

		if (count($currentClassesToActivate) > 0) {
			//echo count($currentClassesToActivate);
			//1. update to ACTIVE state
			foreach ($currentClassesToActivate as $k) {

				$k -> setClassInstanceStatus($this -> getClassStatusByName('ACTIVE'));
				$k -> setDateLastModified(new DateTime());

				$this -> CI -> doctrine -> em -> persist($k);

			}
			
			//2. close any old terms

			$query = $this -> CI -> doctrine -> em -> createQuery('SELECT c FROM Entities\ClassInstance c JOIN c.class_instance_status s' . ' JOIN c.term t WHERE t.date_ended<?1 AND (s.name=?2 OR  s.name=?3)');

			$query -> setParameter(1, $currentTerm -> getDateBegan() -> format('Y-m-d'));
			$query -> setParameter(2, 'NEW');
			$query -> setParameter(3, 'ACTIVE');

			$unclosedClasses = $query -> getResult();

			if (count($unclosedClasses) > 0) {
				//echo ' to close '.count($unclosedClasses);
				foreach ($unclosedClasses as $u) {

					$u -> setClassInstanceStatus($this -> getClassStatusByName('CLOSED'));
					$u -> setDateLastModified(new DateTime());

					$this -> CI -> doctrine -> em -> persist($u);
				}
			}

			$this -> CI -> doctrine -> em -> flush();
		}

	}

	private function DeassociateSubjects($classiId, $subjectIdList) {

		if (empty($subjectIdList)) {
			return;
		}

		$queryBuilder = $this -> CI -> doctrine -> em -> createQueryBuilder();

		$queryBuilder -> add('select', 's') -> add('from', 'Entities\SubjectInstance s') -> add('where', $queryBuilder -> expr() -> andx($queryBuilder -> expr() -> eq('s.class_instance', ':classiid'), $queryBuilder -> expr() -> in('s.study_subject', ':idz'))) -> setParameter('classiid', $classiId) -> setParameter('idz', $subjectIdList);

		$query = $queryBuilder -> getQuery();

		$result = $query -> getResult();

		foreach ($result as $r) {
			$r -> setIsValid(0);
			$r -> setDateLastModified(new DateTime());
			$this -> CI -> doctrine -> em -> persist($r);
			$this -> CI -> doctrine -> em -> flush();
		}

	}

}
?>
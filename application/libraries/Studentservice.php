<?php

class Studentservice {

	function __construct() {
		$this -> CI = &get_instance();
	}

	public function editStudent($form) {

		$this -> CI -> load -> library('Accountsservice');

		$accSvc = new Accountsservice();

		$student = new Entities\Student;

		$id = $form['student_id'];

		if (is_null($id) || $id == '') {
			$student -> setFirstName(strtoupper(trim($form['first_name'])));
			$student -> setSurname(strtoupper(trim($form['surname'])));
			$student -> setGender(trim($form['student_gender']));
			$student -> setOtherNames(trim(strtoupper($form['other_names'])));

			$student -> setTelephone(trim($form['telephone']));
			$student -> setEmail(trim($form['student_email']));

			$student -> setAddress(trim($form['student_address']));
			//$student->setRemarks($form['student_remarks']);

			//$student->setYearEnrolled(NULL);
			$student -> setYearCompleted(NULL);

			if (intval($form['year_enrolled']) > 0) {
				$student -> setYearEnrolled($form['year_enrolled']);
			}

			if (intval($form['year_end']) > 0) {
				$student -> setYearCompleted($form['year_end']);
			}

			if ($form['dob'] == '') {
				$student -> setDateOfBirth(NULL);
			} else {
				$dob = new DateTime($form['dob']);

				$student -> setDateOfBirth($dob);

			}

			if ($form['classi_id'] != '') {
				$student -> setClassInstance($this -> CI -> doctrine -> em -> getReference('Entities\ClassInstance', $form['classi_id']));
			}

			if ($form['fees_profile'] != '') {
				$student -> setFeesProfile($this -> CI -> doctrine -> em -> getReference('Entities\FeesProfile', $form['fees_profile']));
			}

			$student -> setStudentStatus($this -> getStudentStatusByName('CURRENTLY_ENROLLED'));

			$this -> CI -> load -> library('Housemanager');
			$hSvc = new Housemanager();
			$student -> setHouse($this -> CI -> doctrine -> em -> getReference('Entities\House', $hSvc -> getNextHouse()));

			$student -> setStudentNumber($this -> createStudentNumber($form['year_enrolled']));

			$student -> setDateCreated(new DateTime());
			$student -> setDateLastModified(new DateTime());
			$student -> setIsValid(1);

			$this -> CI -> doctrine -> em -> persist($student);

			try {
				$this -> CI -> doctrine -> em -> flush();
			} catch( \PDOException $e ) {
				WriteLog(LogAction::EditStudent, 'student', -1, $form['surname'] . ' ' . $e -> getMessage());
				return NULL;
			}

			$sac = $student -> getAccount();

			if (is_null($sac)) {
				$student -> setAccount($accSvc -> createStudentAccount($student -> getSurname(), $student -> getFirstName(), $student -> getStudentNumber()));
				$this -> CI -> doctrine -> em -> persist($student);
				$this -> CI -> doctrine -> em -> flush();
			}

			$this->createStudentCurrentClassHistory($student->getId());
			return $student;

		} else {
			$student = $this -> CI -> doctrine -> em -> find('Entities\Student', $id);

			if ($student == NULL) {
				return NULL;
			}

			$student -> setFirstName(strtoupper($form['first_name']));
			$student -> setSurname(strtoupper($form['surname']));
			$student -> setGender($form['student_gender']);
			$student -> setOtherNames(strtoupper($form['other_names']));

			$student -> setTelephone($form['telephone']);
			$student -> setEmail($form['student_email']);

			$student -> setAddress($form['student_address']);

			if (intval($form['year_end']) > 0) {
				$student -> setYearCompleted($form['year_end']);
			}

			if ($form['dob'] == '') {
				$student -> setDateOfBirth(NULL);
			} else {
				$dob = new DateTime($form['dob']);

				$student -> setDateOfBirth($dob);
			}

			//added automatically
			if (!$student -> getHouse()) {

				$this -> CI -> load -> library('Housemanager');
				$hSvc = new Housemanager();
				$student -> setHouse($this -> CI -> doctrine -> em -> getReference('Entities\House', $hSvc -> getNextHouse()));
			}

			if ($form['classi_id'] != '') {
				$student -> setClassInstance($this -> CI -> doctrine -> em -> getReference('Entities\ClassInstance', $form['classi_id']));
			}

			if ($form['fees_profile'] != '') {
				$student -> setFeesProfile($this -> CI -> doctrine -> em -> getReference('Entities\FeesProfile', $form['fees_profile']));
			}

			if ($form['year_enrolled'] != $student -> getYearEnrolled()) {
				WriteLog(LogAction::EditStudent, 'student', $student -> getId(), 'Changing Year ENROLLED from: ' . $student -> getYearEnrolled() . ' to ' . $form['year_enrolled']);
				$student -> setYearEnrolled($form['year_enrolled']);
				$student -> setStudentNumber($this -> createStudentNumber($form['year_enrolled']));
			}

			$student -> setDateLastModified(new DateTime());

			$this -> CI -> doctrine -> em -> persist($student);

			try {
				$this -> CI -> doctrine -> em -> flush();
			} catch( \PDOException $e ) {
				WriteLog(LogAction::EditStudent, 'student', $student -> getId(), $form['surname'] . ' ' . $e -> getMessage());
			}

			$sac = $student -> getAccount();

			if (is_null($sac) || $changedYearOfEnrollment) {
				$student -> setAccount($accSvc -> createStudentAccount($student -> getSurname(), $student -> getFirstName(), $student -> getStudentNumber()));
				$this -> CI -> doctrine -> em -> persist($student);
				$this -> CI -> doctrine -> em -> flush();
			}
			
			$this->createStudentCurrentClassHistory($student->getId());

			return $student;
		}

		return NULL;
	}

	public function getStudent($id) {

		$query = $this -> CI -> doctrine -> em -> createQuery('SELECT s FROM Entities\Student s LEFT JOIN s.house h ' 
		.' LEFT JOIN s.class_instance k '
		. ' LEFT JOIN s.student_status j LEFT JOIN s.fees_profile f WHERE s.id = ?1');
		$query -> setParameter(1, $id);
		$student = $query -> getResult();

		if (is_array($student) && count($student) > 0) {
			return $student[0];
		}
		return NULL;
	}

	public function getStudentList($page = 1, $size = 25, $criteria = NULL) {

		$query = $this -> CI -> doctrine -> em -> createQuery('SELECT s FROM Entities\Student s LEFT JOIN s.house h JOIN s.student_status t
		WHERE t.name=?1  ORDER BY s.date_created DESC') 
		-> setFirstResult($page - 1) -> setMaxResults($size)
		->setParameter(1,"CURRENTLY_ENROLLED");

		$paginator = new Doctrine\ORM\Tools\Pagination\Paginator($query, $fetchJoin = true);

		return $paginator;
	}

	public function getStudents($criteria = NULL) {

		$queryBuilder = $this -> CI -> doctrine -> em -> createQueryBuilder();

		$queryBuilder -> select(array('s', 'c', 'f', 'u', 'h')) -> from('Entities\Student', 's') -> leftJoin('s.class_instance', 'c') -> leftJoin('s.fees_profile', 'f') -> leftJoin('s.student_status', 'u') -> leftJoin('s.house', 'h');

		if (!is_null($criteria) && is_array($criteria)) {
			$cc = array();
			if (array_key_exists('class_instance', $criteria) && intval($criteria['class_instance']) > 0) {
				$queryBuilder -> andWhere('c.id=:cl');
				//->setParameter(':cl', $criteria['class_instance']);
				$cc['cl'] = $criteria['class_instance'];
			}

			if (array_key_exists('status', $criteria) && intval($criteria['status']) > 0) {
				$queryBuilder -> andWhere('u.id=:u1');
				$cc['u1'] = $criteria['status'];
			}

			if (array_key_exists('eyear', $criteria) && intval($criteria['eyear']) > 1000) {
				$queryBuilder -> andWhere('s.year_enrolled=:y');
				//
				$cc['y'] = $criteria['eyear'];
			}

			if (array_key_exists('sno', $criteria) && $criteria['sno'] != '') {
				$queryBuilder -> andWhere('s.student_number=:n');
				$cc['n'] = strtoupper(trim($criteria['sno']));
			}

			if (array_key_exists('name', $criteria) && $criteria['name'] != '') {
				$queryBuilder -> andWhere("s.surname LIKE :n1 OR s.first_name LIKE :n2");
				$cc['n1'] = strtoupper('%' . trim($criteria['name']) . '%');
				$cc['n2'] = strtoupper('%' . trim($criteria['name']) . '%');
			}

			if (array_key_exists('student_id', $criteria) && $criteria['student_id'] != '') {
				$queryBuilder -> andWhere('s.id=:i');
				$cc['i'] = $criteria['student_id'];
			}
			
			if (count($cc) > 0) {
				$queryBuilder -> setParameters($cc);
			}
		}

		$queryBuilder -> orderBy("s.surname");
		$query = $queryBuilder -> getQuery();

		$result = $query -> getResult();

		return $result;
	}

	public function getStudentsAsArray($criteria = NULL) {

		$a = array();
		$res = $this -> getStudents($criteria);

		$c = 1;
		foreach ($res as $r) {
			$cls = 'N/A';
			$clsId = '';
			if ($r -> getClassInstance()) {
				$cls = $r -> getClassInstance() -> getName();
				$clsId = $r -> getClassInstance() -> getId();
			}

			$fp = NULL;
			$fpId = NULL;

			if ($r -> getFeesProfile()) {
				$fp = $r -> getFeesProfile() -> getName();
				$fpId = $r -> getFeesProfile() -> getId();
			}

			$status = NULL;

			if ($r -> getStudentStatus()) {
				$status = $r -> getStudentStatus() -> getName();
			}

			$house = NULL;

			if ($r -> getHouse()) {
				$house = $r -> getHouse() -> getName();
			}

			$a[] = array('index' => $c, 'id' => $r -> getId(), 'first_name' => $r -> getFirstName(), 'surname' => $r -> getSurname(), 'gender' => $r -> getGender(), 'student_number' => $r -> getStudentNumber(), 'class' => Utilities::getShortClassName($cls), 'class_id' => $clsId, 'fees_profile' => $fp, 'fees_profile_id' => $fpId, 'status' => $status, 'house' => $house, 'enrolled' => $r -> getYearEnrolled());
			$c++;
		}

		return $a;
	}

	public function getStudentStatusByName($state) {
		$st = $this -> CI -> doctrine -> em -> getRepository('Entities\StudentStatus') -> findOneBy(array('name' => $state));
		return $st;
	}

	public function assignStudents2Class($classiId, $students) {

		$class = $this -> CI -> doctrine -> em -> find('Entities\ClassInstance', $classiId);

		if ($class == NULL) {
			return array('success' => FALSE, 'msg' => 'Invalid Class');
		}

		if (is_null($students) || !is_array($students) || !(count($students) > 0)) {
			return array('success' => FALSE, 'msg' => 'Invalid Class');
		}

		$i = 0;

		foreach ($students as $sid) {
			$student = $this -> CI -> doctrine -> em -> find('Entities\Student', $sid);

			if (!is_null($student)) {
				$student -> setClassInstance($class);
				$student -> setDateLastModified(new DateTime());
				$this -> CI -> doctrine -> em -> persist($student);
				$this -> CI -> doctrine -> em -> flush();

				$i++;

				$this -> getStudentClassHistory($student -> getId(), $class -> getId(), 'Moved to class');
			}
		}

		$msg = 'No Assingments were done';

		if ($i > 0) {
			$msg = $i . ' Assignment(s) Done';
		}

		return array('success' => TRUE, 'msg' => $msg);
	}

	public function assignStudents2FeesProfile($feesProfileId, $students) {

		$profile = $this -> CI -> doctrine -> em -> find('Entities\FeesProfile', $feesProfileId);

		if ($profile == NULL) {
			return array('success' => FALSE, 'msg' => 'Invalid Fees Profile');
		}

		if (is_null($students) || !is_array($students) || !(count($students) > 0)) {
			return array('success' => FALSE, 'msg' => 'Invalid Students');
		}

		$i = 0;

		foreach ($students as $sid) {
			$student = $this -> CI -> doctrine -> em -> find('Entities\Student', $sid);

			if (!is_null($student)) {
				$student -> setFeesProfile($profile);
				$student -> setDateLastModified(new DateTime());
				
				$this -> CI -> doctrine -> em -> persist($student);
				$this -> CI -> doctrine -> em -> flush();

				$i++;
				
				$h = $this->createStudentCurrentClassHistory($student->getId());
				
				$h_prof = $h->getFeesProfile();
				
				if(is_null($h_prof) || ( !is_null($h_prof) && ($h_prof->getId()!=$profile->getId()))){
					$h->setFeesProfile($profile);
					$h->setDateLastModified(new DateTime());

					$this -> CI -> doctrine -> em -> persist($h);
					$this -> CI -> doctrine -> em -> flush();
				}
				
				
				WriteLog(LogAction::FeesProfileActivity, 'student', $student -> getId(), $student -> getSurname() . ' moved to ' . $profile -> getName());
			}
		}

		$msg = 'No Assingments were done';

		if ($i > 0) {
			$msg = $i . ' ' . $profile -> getName() . ' Assignment(s) Done';
		}

		return array('success' => TRUE, 'msg' => $msg);
	}


	public function createStudentCurrentClassHistory($studentId)
	{
		$stud = $this->getStudent($studentId);
		
		if($stud==NULL){
			return NULL;
		}
		
		
		return $this->getStudentClassHistory($studentId);
	}


	public function getStudentClassHistory($studentId, $classiId=NULL, $comment = NULL) {
		
		$term = NULL;
		$currentClass= NULL;
		
		$std = $this->getStudent($studentId);
		
		if(is_null($std)){
			 	WriteLog(LogAction::Error, 'student-history', $studentId, 'Unknown student'.$studentId);
			 	return NULL;					
		}			
		
		if($classiId==NULL){
			$currentClass = $std->getClassInstance();	
		}else{
			$currentClass = $this -> CI -> doctrine -> em -> find('Entities\ClassInstance', $classiId);
		}
		
		if(is_null($currentClass)){
			 	WriteLog(LogAction::StudentEdit, 'student-edit', $studentId, 'Error creating class history because of invalid class instance ');
			 	return NULL;					
		}
				
		$term = $this -> getTermViaClass($currentClass->getId());	
		
		if(is_null($term)){
			
			$term=Utilities::getCurrentTerm();

			if(is_null($term)){
			 	WriteLog(LogAction::StudentEdit, 'student-edit', $studentId, 'Error creating class history because of invalid class instance term. Class Id =' .$classiId);
			 	return NULL;					
			}
		}
		
		$qb = $this -> CI -> doctrine -> em -> createQuery('SELECT h FROM Entities\StudentClassHistory h JOIN h.class_instance c WHERE h.student=?1 AND h.term=?2 ');
		$qb -> setParameter(1, $studentId);
		$qb -> setParameter(2, $term->getId());

		$sh = $qb -> getResult();
		
		if(is_array($sh) && count($sh)==1){
			
			$his = $sh[0];
			
			$ci = $his->getClassInstance();
			
			$update=FALSE;
			
			if($currentClass->getId()!=$ci->getId()){
				$update=TRUE;
				$his->setClassInstance($currentClass);
			}
		
			if(is_null($his->getFeesProfile())){
				$update=TRUE;
				
				$his->setFeesProfile($std->getFeesProfile());
			}else if(!is_null($his->getFeesProfile()) && !is_null($std->getFeesProfile())){
				
				if($his->getFeesProfile()->getId()!=$std->getFeesProfile()->getId()){
					$update=TRUE;
					$his->setFeesProfile($std->getFeesProfile());
				}
			}
			
			if($update){
				$his-> setDateLastModified(new DateTime());
				$his -> setIsValid(1);	
				
				$this -> CI -> doctrine -> em -> persist($his);
				$this -> CI -> doctrine -> em -> flush();
			}
			return $his;
		}
		

		$hist = new Entities\StudentClassHistory;
		$hist -> setStudent($std);
		$hist -> setClassInstance($currentClass);
		$hist -> setTerm($term);
		$hist -> setFeesProfile($std->getFeesProfile());

		if (!is_null($comment)) {
			$hist -> setComment($comment);
		}

		$hist -> setDateCreated(new DateTime());
		$hist -> setDateLastModified(new DateTime());
		$hist -> setIsValid(1);

		$this -> CI -> doctrine -> em -> persist($hist);

		try {
			$this -> CI -> doctrine -> em -> flush();
			return $hist;
		} catch( \PDOException $e ) {
			if ($e -> getCode() === '23000') {
				WriteLog(LogAction::StudentEdit, 'student-edit', $studentId, 'Error creating class history ' . $e -> getMessage());
				return NULL;
			}
		}
	}


	public function getTermViaClass($classiId) {

		$queryBuilder = $this->CI -> doctrine -> em -> createQueryBuilder();

		$queryBuilder -> select(array('c','t')) -> from('Entities\ClassInstance', 'c') 
		-> innerJoin('c.term', 't') -> where('c.id=:cid') 
		-> setParameter('cid', $classiId);

		$query = $queryBuilder -> getQuery();

		$term = $query -> getResult();
		
		if(is_array($term) && count($term)>0)
		{
			return $term[0]->getTerm();	
		}
		
		else return NULL;
	}


	public function findStudents($criteria = NULL, $page=1, $pageSize=25) {

		if(intval($page)<1){
			$page = 1;
		}
		if(intval($pageSize)<5){
			$pageSize = 5;
		}		
		$queryBuilder = $this -> CI -> doctrine -> em -> createQueryBuilder();

		$queryBuilder -> select(array('s', 'c', 'f', 'u', 'h')) -> from('Entities\Student', 's') 
		-> leftJoin('s.class_instance', 'c') 
		-> leftJoin('s.fees_profile', 'f') 
		-> leftJoin('s.student_status', 'u') 
		-> leftJoin('s.house', 'h');

		if (!is_null($criteria) && is_array($criteria)) {
			$cc = array();

			if (array_key_exists('sstudent_status', $criteria) && intval($criteria['sstudent_status']) > 0) {
				$queryBuilder -> andWhere('u.id=:u1');
				$cc['u1'] = $criteria['sstudent_status'];
			}


			if (array_key_exists('sstd_num', $criteria) && $criteria['sstd_num'] != '') {
				$queryBuilder -> andWhere('s.student_number=:n');
				$cc['n'] = strtoupper(trim($criteria['sstd_num']));
			}

			if (array_key_exists('search_name', $criteria) && $criteria['search_name'] != '') {
				$queryBuilder -> andWhere("s.surname LIKE :n1 OR s.first_name LIKE :n2");
				$cc['n1'] = strtoupper('%' . trim($criteria['search_name']) . '%');
				$cc['n2'] = strtoupper('%' . trim($criteria['search_name']) . '%');
			}

			if (count($cc) > 0) {
				$queryBuilder -> setParameters($cc);
			}
		}
		
		$queryBuilder -> orderBy("s.surname");
		$queryBuilder ->setFirstResult(($page-1)*$pageSize)
                       ->setMaxResults($pageSize);
		$query = $queryBuilder -> getQuery();
		
		$paginator = new Doctrine\ORM\Tools\Pagination\Paginator($queryBuilder, $fetchJoinCollection = true);


		$result = array('data'=>$paginator, 'total'=>count($paginator), 'currentPage'=>$page,'pageSize'=>$pageSize);

		return $result;
	}


	public function changeStudentsStatus($form){
		
		$nextState = intval($form['next_state']);
		
		$ids = explode('|', $form['ids']);
		
		if($nextState<1 || count($ids)<1){
			return array('success'=>FALSE, 'msg'=>'Either no students were selected or no state was chosen');
		}
		
		$new_state = $this->CI->doctrine->em->find('Entities\StudentStatus', $nextState);
		
		if(is_null($new_state)){
			return array('success'=>FALSE, 'msg'=>'No state was chosen');
		}
		$good =0;
		$bad=0;
		
		foreach($ids as $s){
			$student = $this->getStudent($s);
			
			if(!is_null($student)){
				$old_state = $student->getStudentStatus();
				
				if(is_null($old_state) || (!is_null($old_state) && $old_state->getName()!=$new_state->getName())){
					$student->setStudentStatus($new_state);
					$student-> setDateLastModified(new DateTime());
					
					$this -> CI -> doctrine -> em -> persist($student);
					$this -> CI -> doctrine -> em -> flush();
					
					$good++;
				} else {
					$bad++;
				}
			}
		}
		
		if($good>0){
			return array('success'=>TRUE, 'msg'=>$good.' out of '.($good+$bad).' have changed status.');
		}else{
			return array('success'=>FALSE, 'msg'=>$good.' out of '.($good+$bad).' have changed status.');
		}
		
	}
	
	public function createStudentNumber($yearOfEnrollment) {

		$letters = 'ABCDEFGHJKLMNPQRTVWXYZABCDEFGHJKLMNPQRTVWXYZ';

		$yearPosition = $yearOfEnrollment - 2003;

		if ($yearPosition >= 0 && $yearPosition < 23) {// must be changed in the year 2025

			$ch = $letters[$yearPosition];

			$query = $this -> CI -> doctrine -> em -> createQuery('SELECT MAX(s.student_number) FROM Entities\Student s WHERE s.student_number LIKE ?1');
			$query -> setParameter(1, $ch . '%');

			$last_num = $query -> getSingleScalarResult();

			$tmp_num = str_replace($ch, '', $last_num);

			if (intval($tmp_num) > 0) {

				$next = $ch . str_pad(1 + (int)$tmp_num, 3, "0", STR_PAD_LEFT);

				return $next;

			} else {

				$next = $ch . str_pad(1, 3, "0", STR_PAD_LEFT);

				return $next;
			}

		}
		return NULL;
	}

}
?>
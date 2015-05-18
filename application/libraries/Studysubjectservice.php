<?php

class Studysubjectservice {

	function __construct() {
		$this -> CI = &get_instance();
	}

	public function editSubject($name, $description, $id = NULL) {

		$sub = new Entities\StudySubject;

		if (is_null($id) || $id == '') {
			$sub -> setName(strtoupper($name));
			$sub -> setDescription($description);
			$sub -> setDateCreated(new DateTime());
			$sub -> setDateLastModified(new DateTime());
			$sub -> setIsValid(1);
		} else {
			$sub = $this -> CI -> doctrine -> em -> find('Entities\StudySubject', $id);

			if ($sub == NULL) {
				return NULL;
			}

			$sub -> setName(strtoupper($name));
			$sub -> setDescription($description);
			$sub -> setDateLastModified(new DateTime());
		}

		$this -> CI -> doctrine -> em -> persist($sub);

		try {
			$this -> CI -> doctrine -> em -> flush();
		} catch( \PDOException $e ) {
			if ($e -> getCode() === '23000') {
				//echo $e->getMessage();
				return NULL;

			}
		}

		return $sub;
	}

	public function getSubject($id) {
		return $this -> CI -> doctrine -> em -> find('Entities\StudySubject', $id);
	}

	public function getAllSubjects() {
		$records = $this -> CI -> doctrine -> em -> getRepository("Entities\StudySubject") -> findAll();

		$query = $this -> CI -> doctrine -> em -> createQuery('SELECT y FROM Entities\StudySubject y ORDER BY y.name');

		return $query -> getResult();
	}

	public function manageClassSubjects($form) {

		$classi_id = $form['classi_id'];

		$currentClass = $this -> CI -> doctrine -> em -> find('Entities\ClassInstance', $classi_id);

		if (is_null($currentClass)) {
			return array('success' => FALSE, 'msg' => 'This class does not exist');
		}

		if (empty($form['attached_subjects'])) {
			$result = $this -> getClassSubjects($classi_id);

			foreach ($result as $r) {
				$r -> setIsValid(0);
				$r -> setDateLastModified(new DateTime());
				$this -> CI -> doctrine -> em -> persist($r);
				$this -> CI -> doctrine -> em -> flush();
			}
			return;
		}

		$subjects = $form['attached_subjects'];

		$c = count($subjects);

		if ($c == 0) {
			return array('success' => FALSE, 'msg' => 'No subjects chosen');
		}

		$already = $this -> getClassSubjects($classi_id);

		$oldAssingments = array();
		$toDelete = array();

		if (count($already) > 0) {

			foreach ($already as $a) {

				$saved = FALSE;
				foreach ($subjects as $i) {
					if ($a -> getStudySubject() && $a -> getStudySubject() -> getId() == $i) {
						$saved = TRUE;
						if ($a -> getIsValid() == 0) {//reassign, formerly deleted
							$a -> setIsValid(1);
							$a -> setDateLastModified(new DateTime());
							$this -> CI -> doctrine -> em -> persist($a);
							$this -> CI -> doctrine -> em -> flush();
						}
					}
				}
				if ($saved) {
					$oldAssingments[] = $a -> getStudySubject() -> getId();
				} else {
					$toDelete[] = $a -> getStudySubject() -> getId();
				}
			}

		}

		$this -> DeassociateSubjects($classi_id, $toDelete);

		$newAssignments = array_diff($subjects, $oldAssingments, $toDelete);

		$this -> associateSubjects($classi_id, $newAssignments);
	}

	public function getClassSubjects($classiId) {

		$queryBuilder = $this -> CI -> doctrine -> em -> createQueryBuilder();

		$queryBuilder -> add('select', 'c,s') -> add('from', 'Entities\SubjectInstance c') -> add('where', 'c.class_instance = :classiid') -> leftJoin('c.study_subject', 's')
		// ->  add('orderBy', 'u.name ASC');
		-> setParameter('classiid', $classiId);

		$query = $queryBuilder -> getQuery();

		return $query -> getResult();

	}

	private function associateSubjects($classiId, $subjectIdList) {

		if (empty($subjectIdList)) {
			return;
		}

		$classI = $this -> CI -> doctrine -> em -> find('Entities\ClassInstance', $classiId);

		foreach ($subjectIdList as $si) {

			$subject = $this -> CI -> doctrine -> em -> find('Entities\StudySubject', $si);

			$generatedName = '[' . $subject -> getName() . '] - ' . $classI -> getName();

			$a = new Entities\SubjectInstance;
			$a -> setName($generatedName);
			$a -> setSubjectInstanceStatus($this -> getSubjectInstantStatusByName('ACTIVE'));
			$a -> setClassInstance($classI);
			$a -> setStudySubject($subject);
			$a -> setIsValid(1);
			$a -> setDateCreated(new DateTime());
			$a -> setDateLastModified(new DateTime());

			$this -> CI -> doctrine -> em -> persist($a);
			$this -> CI -> doctrine -> em -> flush();
		}
	}

	public function getSubjectInstantStatusByName($state) {

		$st = $this -> CI -> doctrine -> em -> getRepository('Entities\SubjectInstanceStatus') -> findOneBy(array('name' => $state));

		return $st;

	}

	public function createSubjectStatusDefaults() {
		$states = array('ACTIVE' => 'Newly created/marks being entered, etc', 'ARCHIVED' => 'Permanent - cannot be edited anymore.');

		$errs = '';

		foreach ($states as $key => $value) {

			$st = new Entities\SubjectInstanceStatus;

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

	private function DeassociateSubjects($classiId, $subjectIdList) {

		if (empty($subjectIdList)) {
			return;
		}

		$this -> CI -> load -> library('Exammanager');
		$exMan = new Exammanager();

		$queryBuilder = $this -> CI -> doctrine -> em -> createQueryBuilder();

		$queryBuilder -> add('select', 'c') -> add('from', 'Entities\SubjectInstance c') -> add('where', $queryBuilder -> expr() -> andx($queryBuilder -> expr() -> eq('c.class_instance', ':classiid'), $queryBuilder -> expr() -> in('c.study_subject', ':idz'))) -> setParameter('classiid', $classiId) -> setParameter('idz', $subjectIdList);

		$query = $queryBuilder -> getQuery();

		$result = $query -> getResult();

		foreach ($result as $r) {
			// only detach if there are no marks associated with the subject in any exam
			if ($exMan -> subjectHasMarks($r -> getId()) == FALSE) {
				$r -> setIsValid(0);
				$r -> setDateLastModified(new DateTime());
				$this -> CI -> doctrine -> em -> persist($r);
				$this -> CI -> doctrine -> em -> flush();
			}
		}

	}

}
?>
<?php

class Exammanager {

	function __construct() {
		$this -> CI = &get_instance();
	}

	public function editExam($input) {

		if (intval($input['exam_id']) > 0) {//edit

		} else {//new
			$term = Utilities::getCurrentTerm();

			if (is_null($term)) {
				return array('success' => FALSE, 'msg' => 'Invalid name');
			}
			$nname = trim($input['exam_name']);

			if (strlen($nname) < 4) {
				return array('success' => FALSE, 'msg' => 'Invalid name');
			}

			$nname = '[' . strtoupper($nname) . '] - ' . $term -> getName();

			if (!is_null($this -> getExamByName($nname))) {
				return array('success' => FALSE, 'msg' => 'An exam with a similar name already exists');
			}

			$ex = new Entities\Exam;
			$ex -> setName($nname);
			$ex -> setTerm($term);
			$ex -> setRemarks($input['description']);
			$ex -> setDateCreated(new DateTime());
			$ex -> setDateLastModified(new DateTime());
			$ex -> setIsValid(1);

			$this -> CI -> doctrine -> em -> persist($ex);

			try {
				$this -> CI -> doctrine -> em -> flush();
			} catch( \PDOException $e ) {
				if ($e -> getCode() === '23000') {
					WriteLog(LogAction::Exam, 'exam-creation', -1, $e -> getMessage());
					return array('success' => FALSE, 'msg' => $e -> getMessage());
				}
			}

			$classList = $input['class-choices'];

			if (is_array($classList) && count($classList) > 0) {

				foreach ($classList as $c) {
					$ce = new Entities\ClassExam;
					$ce -> setExam($ex);
					$ce -> setClassInstance($this -> CI -> doctrine -> em -> getReference('Entities\ClassInstance', $c));
					$ce -> setExamStatus($this -> getExamStatusByName('NEW'));
					$ce -> setDateCreated(new DateTime());
					$ce -> setDateLastModified(new DateTime());
					$ce -> setIsValid(1);
					$this -> CI -> doctrine -> em -> persist($ce);
				}

				try {
					$this -> CI -> doctrine -> em -> flush();
				} catch( \PDOException $e ) {
					if ($e -> getCode() === '23000') {
						WriteLog(LogAction::Exam, 'exam-creation', -1, $e -> getMessage());
						return array('success' => FALSE, 'msg' => $e -> getMessage());
					}
				}

			}

			return array('success' => TRUE, 'id' => $ex -> getId(), 'msg' => 'New exam created successfully');
		}

	}

	public function getCurrentTermExams() {

		$term = Utilities::getCurrentTerm();

		if (is_null($term)) {
			return array('success' => FALSE, 'msg' => 'Invalid term');
		}

		$exsQ = $this -> CI -> doctrine -> em -> createQuery('SELECT g FROM Entities\Exam g WHERE g.term=?1 ORDER BY g.date_created DESC');
		$exsQ -> setParameter(1, $term -> getId());

		$exs = $exsQ -> getResult();

		$result = array();

		foreach ($exs as $x) {
			$tmp = array('id' => $x -> getId(), 'name' => $x -> getName());

			$qb = $this -> CI -> doctrine -> em -> createQuery('SELECT g FROM Entities\ClassExam g
			JOIN g.class_instance c JOIN c.class_type t WHERE g.exam=?1 ORDER BY t.school_division DESC, t.level DESC');
			$qb -> setParameter(1, $x -> getId());

			$cx = $qb -> getResult();

			$ts = array();
			foreach ($cx as $i) {
				$t = array('id' => $i -> getId(), 'name' => $i -> getClassInstance() -> getClassType() -> getName());
				$ts[] = $t;
			}

			$tmp['classes'] = $ts;
			$result[] = $tmp;
		}

		return $result;
	}

	public function getCurrentTermExamsList() {

		$term = Utilities::getCurrentTerm();

		if (is_null($term)) {
			return NULL;
		}

		$exsQ = $this -> CI -> doctrine -> em -> createQuery('SELECT g FROM Entities\Exam g WHERE g.term=?1 ORDER BY g.date_created DESC');
		$exsQ -> setParameter(1, $term -> getId());

		return $exsQ -> getResult();
	}

	public function getExamByName($name) {
		return $this -> CI -> doctrine -> em -> getRepository('Entities\Exam') -> findOneBy(array('name' => $name));
	}

	public function getExamStatusByName($state) {
		$st = $this -> CI -> doctrine -> em -> getRepository('Entities\ExamStatus') -> findOneBy(array('name' => $state));
		return $st;
	}

	public function getMarksStatusByName($state) {
		$st = $this -> CI -> doctrine -> em -> getRepository('Entities\ExamDoneStatus') -> findOneBy(array('name' => $state));
		return $st;
	}

	public function getExamSummaryStatusByName($state) {
		$st = $this -> CI -> doctrine -> em -> getRepository('Entities\ExamResultsStatus') -> findOneBy(array('name' => $state));
		return $st;
	}

	public function getMyClasses($examId) {

		$queryBuilder = $this -> CI -> doctrine -> em -> createQueryBuilder();

		$queryBuilder -> select(array('e', 'c')) -> from('Entities\ClassExam', 'e') -> innerJoin('e.class_instance', 'c') -> innerJoin('c.class_type', 't') -> innerJoin('t.school_division', 'd') -> where('e.exam=:eid') -> orderBy('d.id,t.level', 'ASC')
		// ->                            orderBy('t.level','DESC')
		-> setParameter('eid', $examId);

		$query = $queryBuilder -> getQuery();

		return $query -> getResult();
	}

	public function getMySubjects($classInstanceId) {

		$subjects = $this -> CI -> doctrine -> em -> createQuery('SELECT g FROM Entities\SubjectInstance g WHERE g.class_instance=?1');
		$subjects -> setParameter(1, $classInstanceId);

		return $subjects -> getResult();
	}

	public function getMyStudentsMarks($subjectId, $examId, $grading = NULL) {

		if ($subjectId < 1 || $examId < 1) {
			return array('success' => FALSE, 'msg' => 'Invalid exam or subject');
		}

		$subJ = $this -> CI -> doctrine -> em -> find('Entities\SubjectInstance', $subjectId);

		if (is_null($subJ)) {
			return array('success' => FALSE, 'msg' => 'Invalid subject instance');
		}

		$subject_status = $subJ -> getSubjectInstanceStatus();

		if (is_null($subject_status)) {
			$subject_status = $this -> CI -> doctrine -> em -> getRepository('Entities\SubjectInstanceStatus') -> findOneBy(array('name' => 'ACTIVE'));
			$subJ -> setSubjectInstanceStatus($subject_status);
			$subJ -> setDateLastModified(new DateTime());
			$this -> CI -> doctrine -> em -> persist($subJ);
			$this -> CI -> doctrine -> em -> flush();
		}

		$classInstance = $this -> getClassFromSubject($subjectId);

		if (is_null($classInstance)) {
			return array('success' => FALSE, 'msg' => 'Invalid class instance');
		}

		$selectedDynamicRanges = NULL;

		$gradingScheme = $subJ -> getGrading();

		if (is_null($gradingScheme)) {
			if (!is_null($grading) && $grading > 0) {

				$gs = $this -> CI -> doctrine -> em -> find('Entities\Grading', $grading);

				if (!is_null($gs)) {
					$selectedDynamicRanges = $this -> getGradeRanges($gs -> getId());
				}
			}
		}

		$queryBuilder = $this -> CI -> doctrine -> em -> createQueryBuilder();

		$queryBuilder -> select(array('s')) -> from('Entities\Student', 's') -> innerJoin('s.class_instance', 'c') -> where('c.id=:id') -> orderBy('s.surname') -> setParameter('id', $classInstance -> getId());

		$query = $queryBuilder -> getQuery();

		$results = $query -> getResult();

		$marks = array();

		$actualMarks = $this -> getSubjectResultsforExam($subjectId, $examId);

		foreach ($results as $s) {

			$date = NULL;
			$marksScored = NULL;
			$comments = NULL;
			$grade = NULL;

			$key = $s -> getId();

			if (array_key_exists($key, $actualMarks)) {
				$date = $actualMarks[$key]['date'];
				$marksScored = $actualMarks[$key]['marks'];
				$comments = $actualMarks[$key]['comments'];
				$grade = $actualMarks[$key]['grade'];

				if (is_array($selectedDynamicRanges) && count($selectedDynamicRanges) > 0 && $marksScored >= 0) {
					foreach ($selectedDynamicRanges as $r) {
						if ($marksScored >= $r['min'] && $marksScored <= $r['max']) {
							$grade = $r['grade'];
							break;
						}
					}
				}

			}

			$marks[$key] = array('student_id' => $key, 'surname' => $s -> getSurname(), 'first_name' => $s -> getFirstName(), 'student_number' => $s -> getStudentNumber(), 'exam' => $examId, 'subject' => $subjectId, 'marks' => $marksScored, 'grade' => $grade, 'comments' => $comments, 'date' => $date);
		}
		// sort alphabetically by name
		usort($marks, 'compare_surname');

		return array('success' => TRUE, 'marks' => $marks, 'subject_status' => $subject_status -> getName());
	}

	public function getClassFromSubject($id) {

		$qb = $this -> CI -> doctrine -> em -> createQuery('SELECT s FROM Entities\SubjectInstance s
		JOIN s.class_instance c WHERE s.id=?1');
		$qb -> setParameter(1, $id);

		$res = $qb -> getResult();

		if (is_null($res) || count($res) == 0) {
			return NULL;
		}

		return $res[0] -> getClassInstance();
	}

	public function getSubjectResultsforExam($subjectId, $examId) {

		$results = array();

		$qb = $this -> CI -> doctrine -> em -> createQuery('SELECT g FROM Entities\ExamDone g
		JOIN g.exam e JOIN g.student s WHERE g.subject_instance=?1 AND g.exam=?2');
		$qb -> setParameter(1, $subjectId);
		$qb -> setParameter(2, $examId);

		$_marksEntered = $qb -> getResult();

		foreach ($_marksEntered as $m) {
			$results[$m -> getStudent() -> getId()] = array('sid' => $m -> getStudent() -> getId(), 'marks' => $m -> getMarks(), 'grade' => $m -> getGrade(), 'date' => $m -> getDateLastModified(), 'comments' => $m -> getComments());
		}
		return $results;
	}

	public function postMarks($mform) {

		$student = $mform['student'];
		$exam = $mform['exam'];
		$subject = $mform['subject'];
		$marks = $mform['marks'];
		$comment = $mform['comment'];

		if ($marks == '') {
			$marks = NULL;
		}

		if (!is_null($marks)) {
			$val = intval($marks);
			if ($val < 0 || $val > 100) {
				return array('success' => FALSE, 'msg' => 'Invalid marks. Valid range is: 0-100');
			}
		}

		$class_exam_status = $this -> getClassExamStatus($subject, $exam);

		if (!is_null($class_exam_status)) {
			if ($class_exam_status -> getName() == 'ARCHIVED') {
				return array('success' => FALSE, 'msg' => 'The marks for this subject were finalized.<br/> No updates can be made anymore.');
			}
		}

		$qb = $this -> CI -> doctrine -> em -> createQuery('SELECT g FROM Entities\ExamDone g
		JOIN g.exam e JOIN g.student s WHERE g.subject_instance=?1 AND g.exam=?2 AND g.student=?3');
		$qb -> setParameter(1, $subject);
		$qb -> setParameter(2, $exam);
		$qb -> setParameter(3, $student);
		$qb -> setMaxResults(1);

		$_marksEntered = $qb -> getResult();

		$_class = $this -> getClassViaSubject($subject);

		if (is_null($_marksEntered) || count($_marksEntered) == 0) {

			$examResult = new Entities\ExamDone;

			$examResult -> setMarks($marks);
			$examResult -> setSubjectInstance($this -> CI -> doctrine -> em -> getReference('Entities\SubjectInstance', $subject));
			$examResult -> setExam($this -> CI -> doctrine -> em -> getReference('Entities\Exam', $exam));
			$examResult -> setStudent($this -> CI -> doctrine -> em -> getReference('Entities\Student', $student));

			if ($comment != '') {
				$examResult -> setComments($comment);
			}

			$examResult -> setDateCreated(new DateTime());
			$examResult -> setDateLastModified(new DateTime());
			$examResult -> setIsValid(1);
			$this -> CI -> doctrine -> em -> persist($examResult);

			try {

				$this -> CI -> doctrine -> em -> flush();

				$this -> makeExamSummary($student, $_class -> getId(), $exam);

				return array('success' => TRUE, 'msg' => 'Marks saved successfully');
			} catch( \PDOException $e ) {

				WriteLog(LogAction::Exam, 'exam-marks', $student, $e -> getMessage());

				return array('success' => FALSE, 'msg' => 'Marks not saved');
			}

		} else {//update

			$examResult = $_marksEntered[0];

			$examResult -> setMarks($marks);
			$examResult -> setComments($comment);
			$examResult -> setDateLastModified(new DateTime());

			$this -> CI -> doctrine -> em -> persist($examResult);

			try {
				$this -> CI -> doctrine -> em -> flush();

				$this -> makeExamSummary($student, $_class -> getId(), $exam);

				return array('success' => TRUE, 'msg' => 'Marks updated successfully');

			} catch( \PDOException $e ) {

				WriteLog(LogAction::Exam, 'exam-marks', $student, $e -> getMessage());

				return array('success' => FALSE, 'msg' => 'Marks not updated');
			}

			return array('success' => TRUE, 'msg' => 'Marks updated successfully');
		}
	}

	public function getClassResults($classInstance, $exam) {

		if (!(intval($classInstance) > 0) && !(intval($exam) > 0)) {
			return array('success' => FALSE, 'msg' => 'Invalid Class and or Exam specified');
		}

		$gradingMode = $this -> getGradingMode($classInstance, $exam);

		$subjectsSQL = "select ss.name  AS `subject_name`, si.id AS `subject_instance`, si.subject_instance_status_id AS `subject_status`, si.teacher_id AS `teacher` 
			, g.name AS `grading`
			FROM subject_instance si
			JOIN study_subject ss ON ss.id=si.study_subject_id
			LEFT JOIN subject_exam_grading eg ON eg.subject_instance_id= si.id AND eg.exam_id=?
			LEFT JOIN grading g ON g.id = eg.grading_id
			where si.class_instance_id=?";

		$rsm = new \Doctrine\ORM\Query\ResultSetMapping;
		$rsm -> addScalarResult('subject_instance', 'subject_instance');
		$rsm -> addScalarResult('subject_status', 'subject_status');
		$rsm -> addScalarResult('subject_name', 'subject_name');
		$rsm -> addScalarResult('teacher', 'teacher');
		$rsm -> addScalarResult('grading', 'grading');

		$query = $this -> CI -> doctrine -> em -> createNativeQuery($subjectsSQL, $rsm);
		$query -> setParameter(1, $exam);
		$query -> setParameter(2, $classInstance);
		$eSubs = $query -> getArrayResult();

		$subjectIds = '';

		if (is_array($eSubs) && count($eSubs) > 0) {

			$rsm2 = new \Doctrine\ORM\Query\ResultSetMapping;
			$rsm2 -> addScalarResult('student_id', 'student_id');
			$rsm2 -> addScalarResult('surname', 'surname');
			$rsm2 -> addScalarResult('first_name', 'first_name');
			$rsm2 -> addScalarResult('student_number', 'student_number');
			$rsm2 -> addScalarResult('total_marks', 'total_marks');
			$rsm2 -> addScalarResult('grade', 'grade');
			$rsm2 -> addScalarResult('division', 'division');
			$rsm2 -> addScalarResult('grade', 'grade');
			$rsm2 -> addScalarResult('total_aggregates', 'total_aggregates');
			$rsm2 -> addScalarResult('position', 'position');

			$pivot = 'SELECT s.id AS `student_id`, s.surname,s.first_name,s.student_number, ';

			foreach ($eSubs as $es) {
				$pivot .= "SUM(CASE WHEN ed.subject_instance_id={$es['subject_instance']} THEN ed.marks ELSE NULL END) AS `marks_{$es['subject_instance']}`," . "MAX(CASE WHEN ed.subject_instance_id={$es['subject_instance']} THEN ed.grade ELSE NULL END) AS `grade_{$es['subject_instance']}`,";
				$subjectIds .= $es['subject_instance'] . ',';

				$rsm2 -> addScalarResult('marks_' . $es['subject_instance'], 'marks_' . $es['subject_instance']);
				$rsm2 -> addScalarResult('grade_' . $es['subject_instance'], 'grade_' . $es['subject_instance']);
			}

			$subjectIds = '(' . substr($subjectIds, 0, -1) . ')';

			$pivot .= ' MAX(e.total_marks) AS `total_marks`,
					  MAX(e.grade) AS `grade`,
					  MAX(e.total_aggregates) AS `total_aggregates`,
					  MAX(e.division) AS `division`,
					  MAX(e.position) AS `position` ';

			$pivot .= " FROM exam_done ed 
				RIGHT JOIN (SELECT s.* FROM student s JOIN student_class_history h ON s.id=h.student_id AND h.class_instance_id=?) s 
				ON s.id=ed.student_id AND ed.exam_id=? AND ed.subject_instance_id IN $subjectIds
				LEFT JOIN exam_results_summary e ON e.student_id=s.id AND e.exam_id=?
				GROUP BY s.id, s.surname,s.first_name,s.student_number 
				ORDER BY ";

			$gradingModeName = $gradingMode -> getName();

			$status = $this -> getClassExamStatus($classInstance, $exam);

			if (!is_null($status) && $status -> getName() == 'ARCHIVED') {

				$pivot .= ' -e.position DESC ';

			} else {

				if ($gradingModeName == 'TOTAL_GRADING') {
					$pivot .= ' e.total_marks DESC ';
				} else {
					$pivot .= ' -e.total_aggregates DESC, e.total_marks DESC ';
				}

			}

			$pivot .= ", s.surname ASC";

			$query = $this -> CI -> doctrine -> em -> createNativeQuery($pivot, $rsm2);

			$query -> setParameter(1, $classInstance);
			$query -> setParameter(2, $exam);
			$query -> setParameter(3, $exam);

			$marksListing = $query -> getArrayResult();

			if (!is_null($status) && $status -> getName() == 'NEW') {

				$this -> rankStudents($marksListing, $exam, $gradingModeName);
			}

			$marksListing = $query -> getArrayResult();

			$stats = $this -> getClassMarksStats($marksListing, $eSubs);

			$statusName = '';

			if (!is_null($status)) {
				$statusName = $status -> getName();
			}

			$info = array();
			$info['grading_mode'] = $gradingModeName;
			$info['exam_status'] = $statusName;

			return array('success' => TRUE, 'results' => $marksListing, 'subject_list' => $eSubs, 'marks_stats' => $stats, 'info' => $info);

		} else {

			return array('success' => FALSE, 'msg' => 'No subjects for this class/exam');
		}

	}

	public function rankStudents($marks, $examId, $gradingMode) {

		$ranks = array(1);

		for ($i = 0; $i < count($marks); $i++) {

			$qm = $this -> CI -> doctrine -> em -> createQuery('SELECT g FROM Entities\ExamResultsSummary g
																WHERE g.student=?1 AND g.exam=?2');

			$qm -> setParameter(1, $marks[$i]['student_id']);
			$qm -> setParameter(2, $examId);

			$qm -> setMaxResults(1);

			$examSummaryz = $qm -> getResult();

			if (!(is_array($examSummaryz) && count($examSummaryz) > 0)) {
				return;
				//abort
			}

			$esummary = $examSummaryz[0];

			$position = -1;

			if ($gradingMode == 'TOTALS_GRADING') {

				if ($i > 0) {
					if ($marks[$i]['total_marks'] != $marks[$i - 1]['total_marks']) {
						$ranks[$i] = $i + 1;
						$position = $ranks[$i];
					} else {
						$ranks[$i] = $ranks[$i - 1];
						$position = $ranks[$i];
					}
				} else {
					$position = 1;
				}
			} else {

				if ($i > 0) {
					if ($marks[$i]['total_aggregates'] != $marks[$i - 1]['total_aggregates']) {
						$ranks[$i] = $i + 1;
						$position = $ranks[$i];
					} else {
						if ($marks[$i]['total_marks'] != $marks[$i - 1]['total_marks']) {
							$ranks[$i] = $i + 1;
							$position = $ranks[$i];
						} else {
							$ranks[$i] = $ranks[$i - 1];
							$position = $ranks[$i];
						}
					}
				} else {
					$position = 1;
				}

			}

			$esummary -> setDateLastModified(new DateTime());
			$esummary -> setPosition($position);

			$this -> CI -> doctrine -> em -> persist($esummary);

			try {
				$this -> CI -> doctrine -> em -> flush();
			} catch( \PDOException $e ) {

			}
		}

	}

	public function getClassMarksStats($marks, $subjects) {

		$subIds = array();

		foreach ($subjects as $s) {
			$subIds[] = $s['subject_instance'];
		}

		$total = 0;
		$nulls = 0;

		foreach ($marks as $m) {

			foreach ($subIds as $i) {
				$key = 'marks_' . $i;
				$score = $m[$key];

				if (is_null($score)) {
					$nulls++;
				}
				$total++;
			}
		}

		return array('marks_count' => $total, 'null_marks_count' => $nulls);
	}

	public function applyGradingSchemes($classInstance, $exam, $subjectSchemes) {

		$ssSpecs = array();

		$parr = explode('#', $subjectSchemes);

		foreach ($parr as $p) {
			$ssSpecs[] = explode('|', $p);
		}

		foreach ($ssSpecs as $w) {
			if (count($w) == 2) {

				$subjectInstance = intval($w[0]);
				$gScheme = intval($w[1]);

				if (($subjectInstance > 0) && ($gScheme > 0)) {
					$this -> updateAggregates($subjectInstance, $exam, $gScheme);
				}

			}
		}

		$this -> updateGradedExamResultsSummary($classInstance, $exam);

		return $this -> getClassResults($classInstance, $exam);
	}

	public function updateGradedExamResultsSummary($classInstanceId, $examId) {

		$qSummary = $this -> CI -> doctrine -> em -> createQuery('SELECT g FROM Entities\ExamResultsSummary g JOIN g.student s WHERE g.class_instance=?1 AND g.exam=?2');
		$qSummary -> setParameter(1, $classInstanceId);
		$qSummary -> setParameter(2, $examId);

		$examSummaryz = $qSummary -> getResult();

		foreach ($examSummaryz as $e) {

			$qScores = $this -> CI -> doctrine -> em -> createQuery('SELECT g FROM Entities\ExamDone g WHERE g.student=?1 AND g.exam=?2 AND g.grade IS NOT NULL');
			$qScores -> setParameter(1, $e -> getStudent() -> getId());
			$qScores -> setParameter(2, $examId);

			$subs = $qScores -> getArrayResult();
			$subsWithMarks = count($subs);

			if ($subsWithMarks == 4) {

				$totalAggs = 0;

				foreach ($subs as $u) {
					$totalAggs += intval($this -> getAggregate($u['grade']));
				}

				// add division
				$div = 4;

				if (($totalAggs >= 4) && ($totalAggs <= 12)) {
					$div = 1;
				} else if (($totalAggs >= 13) && ($totalAggs <= 20)) {
					$div = 2;
				} else if (($totalAggs >= 21) && ($totalAggs <= 28)) {
					$div = 3;
				}

				$e -> setDivision($div);
				$e -> setTotalAggregates($totalAggs);
				$e -> setDateLastModified(new DateTime());

				//update db
				$this -> CI -> doctrine -> em -> persist($e);
				$this -> CI -> doctrine -> em -> flush();

			}

		}
	}

	public function updateAggregates($subjectInstanceId, $examId, $gradingId) {

		$query = $this -> CI -> doctrine -> em -> createQuery('SELECT s,t FROM Entities\SubjectInstance s
					LEFT JOIN s.subject_instance_status t WHERE s.id=?1 AND s.is_valid=1');

		$query -> setParameter(1, $subjectInstanceId);
		$result = $query -> getResult();

		$sub = $result[0];
		$status = $sub -> getSubjectInstanceStatus();

		if (is_null($status)) {

			$this -> CI -> load -> library('Studysubjectservice');
			$ss = new Studysubjectservice();

			$sub -> setSubjectInstanceStatus($ss -> getSubjectInstantStatusByName('ACTIVE'));

		} else {

			if ($status -> getName() == 'ARCHIVED') {
				return;
			}
		}

		$this -> subjectGrading($subjectInstanceId, $examId, $gradingId);

		/* deprecated: 28th,Oct,2012
		 $sub -> setGrading($this -> CI -> doctrine -> em -> getReference('Entities\Grading', $gradingId));
		 $sub -> setDateLastModified(new DateTime());

		 //update db
		 $this -> CI -> doctrine -> em -> persist($sub);
		 $this -> CI -> doctrine -> em -> flush();
		 */
		$qb = $this -> CI -> doctrine -> em -> createQuery('SELECT g FROM Entities\GradingRange g WHERE g.grading=?1 ORDER BY g.maximum ASC');
		$qb -> setParameter(1, $gradingId);

		$ranges = $qb -> getArrayResult();
		//print_r($ranges);
		$qScores = $this -> CI -> doctrine -> em -> createQuery('SELECT g FROM Entities\ExamDone g WHERE g.subject_instance=?1 AND g.exam=?2');
		$qScores -> setParameter(1, $subjectInstanceId);
		$qScores -> setParameter(2, $examId);

		$scores = $qScores -> getResult();

		foreach ($scores as $s) {

			foreach ($ranges as $r) {
				$marks = $s -> getMarks();

				if (($marks >= $r['minimum']) && ($marks <= $r['maximum'])) {
					$s -> setGrade($r['code']);
					$s -> setDateLastModified(new DateTime());
					$this -> CI -> doctrine -> em -> persist($s);
					$this -> CI -> doctrine -> em -> flush();
					break;
				}
			}
		}
	}

	public function makeExamSummary($studentId, $classInstance, $exam, $params = NULL) {

		$qb = $this -> CI -> doctrine -> em -> createQuery('SELECT g FROM Entities\ExamResultsSummary g
		WHERE g.student=?1 AND g.class_instance=?2 AND g.exam=?3');
		$qb -> setParameter(1, $studentId);
		$qb -> setParameter(2, $classInstance);
		$qb -> setParameter(3, $exam);
		$qb -> setMaxResults(1);

		$examSummaryz = $qb -> getResult();

		$perfSummary = $this -> getExamSummary($studentId, $exam, $classInstance);

		$term = $this -> getTermViaClass($classInstance);

		if (is_null($examSummaryz) || count($examSummaryz) == 0) {

			$newStatus = $this -> getExamSummaryStatusByName('NEW');

			$examSummary = new Entities\ExamResultsSummary;

			$examSummary -> setClassInstance($this -> CI -> doctrine -> em -> getReference('Entities\ClassInstance', $classInstance));
			$examSummary -> setExam($this -> CI -> doctrine -> em -> getReference('Entities\Exam', $exam));
			$examSummary -> setStudent($this -> CI -> doctrine -> em -> getReference('Entities\Student', $studentId));
			$examSummary -> setTerm($term);
			$examSummary -> setExamResultsStatus($newStatus);

			if (!is_null($perfSummary['total'])) {
				$examSummary -> setTotalMarks($perfSummary['total']);
			}

			$examSummary -> setDateCreated(new DateTime());
			$examSummary -> setDateLastModified(new DateTime());
			$examSummary -> setIsValid(1);
			$this -> CI -> doctrine -> em -> persist($examSummary);

			try {
				$this -> CI -> doctrine -> em -> flush();

				return array('success' => TRUE, 'msg' => 'Exam Summary created successfully');
			} catch( \PDOException $e ) {

				WriteLog(LogAction::Exam, 'exam-summary', $student, $e -> getMessage());
				return array('success' => FALSE, 'msg' => 'Exam Summary not saved');
			}

		} else {//update

			$examSummary = $examSummaryz[0];

			$examSummary -> setDateLastModified(new DateTime());

			if (!is_null($perfSummary['total'])) {
				$examSummary -> setTotalMarks($perfSummary['total']);
			}

			$this -> CI -> doctrine -> em -> persist($examSummary);

			try {
				$this -> CI -> doctrine -> em -> flush();
				return array('success' => TRUE, 'msg' => 'Exam Summary updated successfully');
			} catch( \PDOException $e ) {
				WriteLog(LogAction::Exam, 'exam-summary', $student, $e -> getMessage());
				return array('success' => FALSE, 'msg' => 'Exam Summary not updated');
			}

			return array('success' => TRUE, 'msg' => 'Marks updated successfully');
		}
	}

	public function getExamSummary($studentId, $examId, $classInstance) {

		$results = array();
		$results['total'] = null;
		$results['total_subjects_done'] = null;
		$results['total_class_subjects_in_exam'] = null;
		$results['marks_list'] = null;

		//getSubjectsInClassExamsCount()
		$qb = $this -> CI -> doctrine -> em -> createQuery('SELECT g FROM Entities\ExamDone g
		JOIN g.subject_instance si WHERE g.student=?1 AND g.exam=?2 AND g.is_valid=1');

		$qb -> setParameter(1, $studentId);
		$qb -> setParameter(2, $examId);

		$scores = $qb -> getArrayResult();

		if (is_array($scores) && count($scores) > 0) {

			//get number of subjects for this exam for this class
			$subQuery = $this -> CI -> doctrine -> em -> createQuery('SELECT COUNT(i.id) FROM Entities\SubjectInstance i
		WHERE i.class_instance=?1 AND i.is_valid=1');

			$subQuery -> setParameter(1, $classInstance);

			$subCount = $subQuery -> getArrayResult();
			//echo json_encode($subCount[0][1]);
			$results['total_class_subjects_in_exam'] = $subCount[0][1];
			//
			$results['total_subjects_done'] = count($scores);
			$total = 0;

			foreach ($scores as $s) {
				$total += $s['marks'];
			}

			if ($total > 0) {
				$results['total'] = $total;
			}
		}

		return $results;

	}

	public function getClassViaSubject($subjectId) {

		$queryBuilder = $this -> CI -> doctrine -> em -> createQueryBuilder();

		$queryBuilder -> select(array('s', 'c')) -> from('Entities\SubjectInstance', 's') -> innerJoin('s.class_instance', 'c') -> where('s.id=:sid') -> setParameter('sid', $subjectId);

		$query = $queryBuilder -> getQuery();

		$sub = $query -> getResult();

		if (is_array($sub) && count($sub) > 0) {
			return $sub[0] -> getClassInstance();
		} else
			return NULL;
	}

	public function getGradeRanges($grading) {

		$ranges = array();

		$qb = $this -> CI -> doctrine -> em -> createQuery('SELECT g FROM Entities\GradingRange g WHERE g.grading=?1 ORDER BY g.maximum DESC');
		$qb -> setParameter(1, $grading);

		$rg = $qb -> getResult();

		foreach ($rg as $i) {
			$ranges[] = array('grade' => $i -> getCode(), 'min' => $i -> getMinimum(), 'max' => $i -> getMaximum());
		}

		return $ranges;
	}

	public function getSubjectsInClassExamsCount($classiId, $examId) {

		$sql = "select e.exam_id,e.class_instance_id, COUNT(*) AS `count` from class_exam e
				join subject_instance s ON s.class_instance_id=e.class_instance_id
				WHERE e.class_instance_id=? AND e.exam=? AND s.is_valid=1 AND e.is_valid=1
				GROUP BY e.exam_id,e.class_instance_id";

		$rsm = new \Doctrine\ORM\Query\ResultSetMapping;
		$rsm -> addScalarResult('total', 'total');

		$query = $this -> CI -> doctrine -> em -> createNativeQuery($sql, $rsm);
		$query -> setParameter(1, $classiId);
		$query -> setParameter(2, $examId);

		$result = $query -> getArrayResult();

		return $result[0]['count'];
	}

	public function getClassExamStatus($classInstance, $exam) {

		$query = $this -> CI -> doctrine -> em -> createQuery('SELECT e,s FROM Entities\ClassExam e
		JOIN e.exam_status s WHERE e.class_instance=?1 AND e.exam=?2 AND e.is_valid=1');

		$query -> setParameter(1, $classInstance);
		$query -> setParameter(2, $exam);

		$result = $query -> getResult();

		if (!is_null($result) && count($result) == 1) {

			$class_exam = $result[0];
			return $class_exam -> getExamStatus();
		}

		return NULL;

	}

	public function getGradingMode($classInstanceId, $examId) {

		$query = $this -> CI -> doctrine -> em -> createQuery('SELECT e,i,t,m FROM Entities\ClassExam e
		JOIN e.class_instance i JOIN i.class_type t JOIN t.default_grading_mode m WHERE e.class_instance=?1 AND e.exam=?2 AND e.is_valid=1');

		$query -> setParameter(1, $classInstanceId);
		$query -> setParameter(2, $examId);

		$result = $query -> getResult();

		if (!is_null($result) && count($result) == 1) {

			$ce = $result[0];

			$gmode = $ce -> getGradingMode();

			if ($gmode == NULL) {

				$ce -> setGradingMode($ce -> getClassInstance() -> getClassType() -> getDefaultGradingMode());
				$ce -> setDateLastModified(new DateTime());

				$this -> CI -> doctrine -> em -> persist($ce);

				try {
					$this -> CI -> doctrine -> em -> flush();
					return $ce -> getClassInstance() -> getClassType() -> getDefaultGradingMode();
				} catch( \PDOException $e ) {

				}

			}
			return $gmode;
		}

		return NULL;
	}

	public function getExamById($id) {
		return $this -> CI -> doctrine -> em -> find('Entities\Exam', $id);
	}

	public function subjectHasMarks($subjectId) {

		$results = array();

		$qb = $this -> CI -> doctrine -> em -> createQuery('SELECT g FROM Entities\ExamDone g
		JOIN g.exam e WHERE g.subject_instance=?1 AND g.is_valid=1');
		$qb -> setParameter(1, $subjectId);

		$_marksEntered = $qb -> getArrayResult();

		if (!is_null($_marksEntered) && is_array($_marksEntered) && count($_marksEntered) > 0) {
			return TRUE;
		}

		return FALSE;
	}

	public function closeClassExam($classInstance, $exam) {

		$query = $this -> CI -> doctrine -> em -> createQuery('SELECT e,s FROM Entities\ClassExam e
		JOIN e.exam_status s
		WHERE e.class_instance=?1 AND e.exam=?2 AND e.is_valid=1');

		$query -> setParameter(1, $classInstance);
		$query -> setParameter(2, $exam);

		$result = $query -> getResult();

		if (!is_null($result) && count($result) == 1) {

			$class_exam = $result[0];
			$status = $class_exam -> getExamStatus();

			if (!is_null($status) && $status -> getName() == 'ARCHIVED') {
				return array('success' => FALSE, 'msg' => 'Exam is already archived.');
			}

			$gmode = $this -> getGradingMode($classInstance, $exam);

			if ($gmode -> getName() == 'AGGREGATE_GRADING') {
				$aggSubjectCount = $this -> getAggregatedSubjectCount($classInstance, $exam);
				
				if($aggSubjectCount!=4){
					return array('success' => FALSE, 'msg' => 'One or more subjects does not have a grading specified.<br/>Apply the grading and try again.');
				}
			}
			//1. seal marks

			//1.1 get the marks objects
			$queryBuilder = $this -> CI -> doctrine -> em -> createQueryBuilder();

			$queryBuilder -> select('e') -> from('Entities\ExamDone', 'e') -> innerJoin('e.subject_instance', 's') -> innerJoin('s.class_instance', 'c') -> where('e.exam=:eid AND c.id=:cid') -> setParameter('eid', $exam) -> setParameter('cid', $classInstance);

			$query = $queryBuilder -> getQuery();

			$marks = $query -> getResult();

			$archiveMarkStatus = $this -> getMarksStatusByName('ARCHIVED');

			foreach ($marks as $mk) {
				$mk -> setExamDoneStatus($archiveMarkStatus);
				$mk -> setDateLastModified(new DateTime());
				$this -> CI -> doctrine -> em -> persist($mk);
			}
			//2. seal summaries

			$qsumm = $this -> CI -> doctrine -> em -> createQuery('SELECT g FROM Entities\ExamResultsSummary g WHERE g.class_instance=?1 AND g.exam=?2');
			$qsumm -> setParameter(1, $classInstance);
			$qsumm -> setParameter(2, $exam);

			$summz = $qsumm -> getResult();

			$archiveSummaryStatus = $this -> getExamSummaryStatusByName('ARCHIVED');

			foreach ($summz as $su) {
				$su -> setExamResultsStatus($archiveSummaryStatus);
				$su -> setDateLastModified(new DateTime());
				$this -> CI -> doctrine -> em -> persist($su);
			}

			//3. seal exam class instance

			$queryKlassExam = $this -> CI -> doctrine -> em -> createQuery('SELECT e FROM Entities\ClassExam e
		 WHERE e.class_instance=?1 AND e.exam=?2 AND e.is_valid=1');

			$queryKlassExam -> setParameter(1, $classInstance);
			$queryKlassExam -> setParameter(2, $exam);

			$rKlassExam = $queryKlassExam -> getResult();

			$klassExam = $rKlassExam[0];

			$exClassStatus = $this -> getExamStatusByName('ARCHIVED');

			$klassExam -> setExamStatus($exClassStatus);
			$klassExam -> setDateLastModified(new DateTime());
			$this -> CI -> doctrine -> em -> persist($klassExam);

			try {
				$this -> CI -> doctrine -> em -> flush();
				return array('success' => TRUE, 'msg' => 'Exam finalized successfully');
			} catch( \PDOException $e ) {
				WriteLog(LogAction::Exam, 'exam-closure', -1, $e -> getMessage());
				return array('success' => FALSE, 'msg' => 'Exam was not finalized' . $e -> getMessage());
			}

		}

		return array('success' => FALSE, 'msg' => 'Unknown Class-Exam.');

	}

	public function subjectGrading($subject_instance, $exam_instance, $grading = NULL) {

		$qb = $this -> CI -> doctrine -> em -> createQuery('SELECT s,g FROM Entities\SubjectExamGrading s
		LEFT JOIN s.grading g
		WHERE s.subject_instance=?1 AND s.exam=?2');

		$qb -> setParameter(1, $subject_instance);
		$qb -> setParameter(2, $exam_instance);
		$qb -> setMaxResults(1);

		$subGradingz = $qb -> getResult();

		$subGrading = NULL;

		if (is_null($subGradingz) || count($subGradingz) == 0) {

			$subGrading = new Entities\SubjectExamGrading;

			$subGrading -> setSubjectInstance($this -> CI -> doctrine -> em -> getReference('Entities\SubjectInstance', $subject_instance));
			$subGrading -> setExam($this -> CI -> doctrine -> em -> getReference('Entities\Exam', $exam_instance));

			if (!is_null($grading)) {
				$subGrading -> setGrading($this -> CI -> doctrine -> em -> getReference('Entities\Grading', $grading));
			}

			$subGrading -> setDateCreated(new DateTime());
			$subGrading -> setDateLastModified(new DateTime());
			$subGrading -> setIsValid(1);
			$this -> CI -> doctrine -> em -> persist($subGrading);

			try {
				$this -> CI -> doctrine -> em -> flush();
				return array('success' => TRUE, 'msg' => 'Exam Subject Grading created successfully', 'subject_grading' => $subGrading);
			} catch( \PDOException $e ) {

				WriteLog(LogAction::Exam, 'exam-subject-grading', $exam_instance, $e -> getMessage());
				return array('success' => FALSE, 'msg' => 'Exam Subject Grading not saved.');
			}

		} else {//update

			$subGrading = $subGradingz[0];

			if (!is_null($grading)) {
				$sg = $subGrading -> getGrading();
				if (is_null($sg) || (!is_null($sg) && $sg -> getId() != $grading)) {

					$subGrading -> setGrading($this -> CI -> doctrine -> em -> getReference('Entities\Grading', $grading));
					$subGrading -> setDateLastModified(new DateTime());
					$this -> CI -> doctrine -> em -> persist($subGrading);
					$this -> CI -> doctrine -> em -> flush();
				}
			}

			return array('success' => TRUE, 'msg' => 'Grading exists', 'subject_grading' => $subGrading);
		}
	}

	public function getAggregatedSubjectCount($classiId, $exam) {

		$sql = "select COUNT(DISTINCT sg.id) AS `number` from subject_exam_grading sg
				JOIN subject_instance si ON si.class_instance_id=? AND sg.exam_id=?;";

		$rsm = new \Doctrine\ORM\Query\ResultSetMapping;
		$rsm -> addScalarResult('number', 'number');

		$query = $this -> CI -> doctrine -> em -> createNativeQuery($sql, $rsm);
		$query -> setParameter(1, $classiId);
		$query -> setParameter(2, $exam);

		$result = $query -> getArrayResult();

		return $result[0]['number'];
	}

	//private

	private function getAggregate($grade) {
		return preg_replace('/\D/', '', $grade);
	}

	private function getTermViaClass($classiId) {//exists in StudentService too

		$queryBuilder = $this -> CI -> doctrine -> em -> createQueryBuilder();

		$queryBuilder -> select(array('c', 't')) -> from('Entities\ClassInstance', 'c') -> innerJoin('c.term', 't') -> where('c.id=:cid') -> setParameter('cid', $classiId);

		$query = $queryBuilder -> getQuery();

		$term = $query -> getResult();

		if (is_array($term) && count($term) > 0) {
			return $term[0] -> getTerm();
		} else
			return NULL;
	}

}
?>
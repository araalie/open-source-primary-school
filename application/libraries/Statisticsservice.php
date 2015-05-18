<?php

class Statisticsservice {

	public function __construct() {
		$this -> CI = &get_instance();
	}

	public function classStatisticsThisTerm() {
		$sql = "SELECT summary.class as 'class', SUM(summary.total) AS 'total'
		FROM (select ct.name as 'class', ct.school_division_id, ct.`level`, 0 AS 'total' from class_type ct
		JOIN school_division d on ct.school_division_id = d.id
		UNION
		SELECT ct.name as 'class', ct.school_division_id, ct.`level`, count(*) as 'total' FROM student s
		JOIN class_instance ci ON ci.id = s.class_instance_id
		JOIN class_type ct ON ci.class_type_id=ct.id
		WHERE s.student_status_id = (select ss.id from student_status ss where ss.name='CURRENTLY_ENROLLED' LIMIT 1)
			GROUP BY ct.name,ct.school_division_id, ct.`level`) summary
		GROUP BY summary.class
		ORDER BY summary.school_division_id, summary.`level`;";

		$rsm = new \Doctrine\ORM\Query\ResultSetMapping;
		$rsm -> addScalarResult('class', 'class');
		$rsm -> addScalarResult('total', 'total');

		$query = $this -> CI -> doctrine -> em -> createNativeQuery($sql, $rsm);

		return $query -> getArrayResult();
	}

	public function subjectScoreSummary1($studentMarks) {
		
		$stat = array();

		$stat['No Marks'] = 0;
		$stat['Below 40%'] = 0;
		$stat['40 - 49%'] = 0;
		$stat['50 - 59%'] = 0;
		$stat['60 - 69%'] = 0;
		$stat['70 - 79%'] = 0;
		$stat['80%+'] = 0;

		foreach ($studentMarks as $sm) {

			if (is_null($sm['marks']) || $sm['marks'] == '') {
				$stat['No Marks'] = $stat['No Marks'] + 1;
			} else if (intval($sm['marks']) < 40) {
				$stat['Below 40%'] = $stat['Below 40%'] + 1;
			} else if (intval($sm['marks']) >= 40 && intval($sm['marks']) < 50) {
				$stat['40 - 49%'] = $stat['40 - 49%'] + 1;
			} else if (intval($sm['marks']) >= 50 && intval($sm['marks']) < 60) {
				$stat['50 - 59%'] = $stat['50 - 59%'] + 1;
			} else if (intval($sm['marks']) >= 60 && intval($sm['marks']) < 70) {
				$stat['60 - 69%'] = $stat['60 - 69%'] + 1;
			} else if (intval($sm['marks']) >= 70 && intval($sm['marks']) < 80) {
				$stat['70 - 79%'] = $stat['70 - 79%'] + 1;
			} else if (intval($sm['marks']) >= 80) {
				$stat['80%+'] = $stat['80%+'] + 1;
			}
		}

		return $stat;

	}

	public function subjectDivisionSummary($studentMarks) {

		$this -> CI -> load -> library('Gradingmanager');
		$gMan = new Gradingmanager();

		$stat = array();

		foreach ($gMan->getGrades() as $g) {
			$stat[$g] = 0;
		}

		foreach ($studentMarks as $sm) {

			$key = $sm['grade'];
			if (is_null($key) || $key == '') {
				$key = 'UnGraded';
			}

			if (!array_key_exists($key, $stat)) {
				$stat[$key] = 0;
			}
			
			$stat[$key] = $stat[$key] + 1;
		}

		return $stat;
	}

}
?>
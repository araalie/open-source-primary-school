<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Feesreportservice {

	private $CI;
	
	function __construct() {
		$this -> CI = &get_instance();
	}
	
	
	public function getClassFeesProjection($classiId, $freqName='TERMINAL', $term= NULL) {

		$termId =-1;
		
		if(is_null($term)){
			$ttm = Utilities::getCurrentTerm();
			if(!is_null($ttm)){
				$termId = $ttm->getId();
			}
		}
		
		$sql = "SELECT st.id AS `student_id`, st.first_name,st.surname, st.student_number
				, ci.name AS `class_name`, sfs.fee_frequency_type_id, h.term_id, ci.id AS `class_id`, h.fees_profile_id
				, sfs.compulsary_amount_paid, sfs.compulsary_amount_owed
				, sfs.other_amount_paid, sfs.other_amount_owed 
				, (SELECT fp.name FROM fees_profile fp WHERE fp.id=h.fees_profile_id) AS `fees_profile` FROM student st
				LEFT JOIN student_class_history h ON h.student_id=st.id
				LEFT JOIN class_instance ci ON ci.id = h.class_instance_id
				LEFT JOIN student_fees_summary sfs ON st.id = sfs.student_id AND sfs.term_id = ? 
				AND sfs.fee_frequency_type_id=(SELECT id FROM fee_frequency_type q WHERE q.name=?)
				WHERE ci.id=?
				ORDER BY  st.surname";

		$rsm = new \Doctrine\ORM\Query\ResultSetMapping;
		$rsm -> addScalarResult('student_id', 'student_id');
		$rsm -> addScalarResult('first_name', 'first_name');
		$rsm -> addScalarResult('surname', 'surname');
		$rsm -> addScalarResult('student_number', 'student_number');
		$rsm -> addScalarResult('class_name', 'class_name');
		$rsm -> addScalarResult('fee_frequency_type_id', 'fee_frequency_type_id');
		$rsm -> addScalarResult('term_id', 'term_id');
		$rsm -> addScalarResult('class_id', 'class_id');
		$rsm -> addScalarResult('fees_profile_id', 'fees_profile_id');
		$rsm -> addScalarResult('compulsary_amount_paid', 'compulsary_amount_paid');
		$rsm -> addScalarResult('compulsary_amount_owed', 'compulsary_amount_owed');
		$rsm -> addScalarResult('other_amount_paid', 'other_amount_paid');
		$rsm -> addScalarResult('other_amount_owed', 'other_amount_owed');
		$rsm -> addScalarResult('fees_profile', 'fees_profile');

		$query = $this -> CI -> doctrine -> em -> createNativeQuery($sql, $rsm);

		
		$query -> setParameter(1, $termId);
		$query -> setParameter(2, $freqName);
		$query -> setParameter(3, $classiId);
		
		$results = $query -> getArrayResult();

		return $results;
	}
	
}

?>
<?php

class Accountsservice {

	function __construct() {
		$this -> CI = &get_instance();

		$this -> MainFeesAccount = 'MAIN FEES SCHOOL ACCOUNT';
		$this -> BursariesAccount = 'BURSARIES ACCOUNT';
		$this -> Receivables = 'RECEIVABLES ACCOUNT';
		$this -> Debtors = 'DEBTORS ACCOUNT';
	}

	public function createStudentAccount($surname, $first_name, $student_number) {
		$accName = $surname . ' ' . $first_name . ' [' . $student_number . ']';

		$ac = $this -> CI -> doctrine -> em -> getRepository('Entities\Account') -> findOneBy(array('name' => $accName));

		if (is_null($ac)) {// create acc

			$ac = new Entities\Account;

			$ac -> setName(strtoupper($accName));
			$ac -> setDescription('Student Account for : ' . $accName);
			$ac -> setAccountType($this -> getAccountTypeByName('student'));
			$ac -> setDateCreated(new DateTime());
			$ac -> setDateLastModified(new DateTime());
			$ac -> setIsValid(1);

			$this -> CI -> doctrine -> em -> persist($ac);

			try {
				$this -> CI -> doctrine -> em -> flush();
			} catch( \PDOException $e ) {
				WriteLog(LogAction::AccountError, 'account-creation', -1, $e -> getMessage());
			}

			return $ac;

		} else {// already exists

			return $ac;
		}
	}

	public function createSchoolAccounts() {

		$accounts = array($this -> MainFeesAccount => 'Used for receiving fees from students', $this -> BursariesAccount => 'Record of monies given to students as part of fees', $this -> Receivables => 'Records of monies spent by the school - students repay later.', $this -> Debtors => 'Various amounts owed to the school');

		foreach ($accounts as $key => $value) {

			$ac = $this -> CI -> doctrine -> em -> getRepository('Entities\Account') -> findOneBy(array('name' => $key));

			if (is_null($ac)) {// create acc

				$ac = new Entities\Account;

				$ac -> setName(strtoupper($key));
				$ac -> setDescription($key . ': ' . $value);
				$ac -> setAccountType($this -> getAccountTypeByName('school'));
				$ac -> setDateCreated(new DateTime());
				$ac -> setDateLastModified(new DateTime());
				$ac -> setIsValid(1);

				$this -> CI -> doctrine -> em -> persist($ac);

				try {
					$this -> CI -> doctrine -> em -> flush();
				} catch( \PDOException $e ) {
					WriteLog(LogAction::AccountError, 'account-creation', -1, $e -> getMessage());
				}

			} else {
				// already exists
			}
		}
	}

	public function getAccountTypeByName($name) {

		$st = $this -> CI -> doctrine -> em -> getRepository('Entities\AccountType') -> findOneBy(array('name' => strtoupper($name)));

		return $st;
	}

	public function getAccountByName($name) {

		$st = $this -> CI -> doctrine -> em -> getRepository('Entities\Account') -> findOneBy(array('name' => strtoupper($name)));

		return $st;
	}

	public function getTransactionTypeByName($name) {

		$st = $this -> CI -> doctrine -> em -> getRepository('Entities\TransactionType') -> findOneBy(array('name' => strtoupper($name)));

		return $st;
	}


	public function getFeesSummaryStatusByName($name) {

		$st = $this -> CI -> doctrine -> em -> getRepository('Entities\FeesSummaryStatus') -> findOneBy(array('name' => strtoupper($name)));

		return $st;
	}


	public function getFeesComponents() {

		$queryBuilder = $this -> CI -> doctrine -> em -> createQueryBuilder();

		$queryBuilder -> select(array('f', 'p', 't')) -> from('Entities\StudentFeeType', 'f') -> innerJoin('f.fees_profile', 'p') -> leftJoin('f.fee_frequency_type', 't') -> orderBy('f.name');

		$query = $queryBuilder -> getQuery();

		return $query -> getResult();
	}

	public function getClassFeesStructureForAll($classiId) {

		$fees = array();
		$types = $this -> getFeesComponents();

		foreach ($types as $t) {
			$fees[$t -> getId()] = array('id' => $t -> getId(), 'name' => $t -> getName(), 'profile' => $t -> getFeesProfile() -> getName(), 'amount' => NULL, 'is_compulsary' => NULL, 'frequency' => $t -> getFeeFrequencyType() -> getName());
		}
		$queryBuilder = $this -> CI -> doctrine -> em -> createQueryBuilder();

		$queryBuilder -> select(array('f', 't', 'q')) -> from('Entities\TermFees', 'f') -> innerJoin('f.student_fee_type', 't') -> innerJoin('f.class_instance', 'c') -> innerJoin('t.fee_frequency_type', 'q') -> where('c.id=:cid') -> setParameter('cid', $classiId);

		$query = $queryBuilder -> getQuery();

		$result = $query -> getResult();

		foreach ($result as $r) {
			$fees[$r -> getStudentFeeType() -> getId()]['amount'] = $r -> getAmount();
			$fees[$r -> getStudentFeeType() -> getId()]['is_compulsary'] = $r -> getIsCompulsary();
		}

		return $fees;
	}

	public function getClassFeesStructureDisplay($classiId) {

		$display = array();

		$raw = $this -> getClassFeesStructureForAll($classiId);

		$cats = $this -> getFeesFrequencies();

		foreach ($cats as $c) {
			$display[$c['name']][] = NULL;
		}

		foreach ($raw as $key => $value) {
			$display[$value['frequency']][] = $value;
		}

		return $display;

	}

	public function getClassFeesProfileStructure($classiId, $feesProfileId, $frequencyName) {

		$frequencyName = urldecode($frequencyName);

		$freq = $this -> getFeesFrequencyByName($frequencyName);

		$pred = 'f.amount>0 AND f.is_valid=1 AND c.id=:cid AND ( fp.id=:fid OR fp.costing_only=1) ' . ' AND q.id=:freq';

		$queryBuilder = $this -> CI -> doctrine -> em -> createQueryBuilder();

		$queryBuilder -> select(array('f', 't', 'fp', 'q')) -> from('Entities\TermFees', 'f') -> innerJoin('f.student_fee_type', 't') -> innerJoin('f.class_instance', 'c') -> innerJoin('t.fees_profile', 'fp') -> innerJoin('t.fee_frequency_type', 'q') -> where($pred) -> setParameter('cid', $classiId) -> setParameter('fid', $feesProfileId)
		// ->   setParameter('must', $isCompulsary)
		-> setParameter('freq', $freq -> getId());

		$query = $queryBuilder -> getQuery();

		$result = $query -> getResult();

		$fees = array();

		foreach ($result as $r) {
			$fees[$r -> getStudentFeeType() -> getId()]['amount'] = $r -> getAmount();
			//$fees[$r -> getStudentFeeType() -> getId()]['profile'] = $r ->getFeeProfile()-> getName();
			$fees[$r -> getStudentFeeType() -> getId()]['name'] = $r -> getStudentFeeType() -> getName();
			$fees[$r -> getStudentFeeType() -> getId()]['id'] = $r -> getStudentFeeType() -> getId();
			$fees[$r -> getStudentFeeType() -> getId()]['is_compulsary'] = $r -> getIsCompulsary();
		}

		return $fees;
	}

	public function updateClassFeeStructure($form) {

		$classiId = $form['classi_id'];

		if (!(intval($classiId) > 0)) {
			return array('success' => FALSE, 'msg' => 'Invalid Class');
		}

		$types = $this -> getFeesComponents();

		$updates = 0;

		foreach ($types as $t) {
			$feeName = 'fee_' . $t -> getId();
			$isCompulsary = 'compulsary_fee_' . $t -> getId();

			if (floatval($form[$feeName]) > 0) {
				$comp = FALSE;

				if (isset($form[$isCompulsary]) && $form[$isCompulsary] == 'on') {
					$comp = TRUE;
				}

				if ($this -> updateTermFee($classiId, $t -> getId(), $form[$feeName], $comp)) {
					$updates++;
				}
			}
		}
		$feedback = '';
		if ($updates > 0) {
			$feedback = 'Fees Structure updated successfully.<br/>' . $updates . ' made.';
		}
		return array('success' => TRUE, 'msg' => $feedback);
	}

	public function getTotalCompulsaryClassFees($classiId, $feesProfileId, $freqName) {

		$sql = "select sum(amount) AS 'total' from term_fees tf
		JOIN student_fee_type sf ON sf.id = tf.student_fee_type_id
		JOIN fees_profile p ON p.id = sf.fees_profile_id
		JOIN fee_frequency_type q ON q.id= sf.fee_frequency_type_id AND q.name=?
		WHERE tf.class_instance_id =? AND ( p.id=? OR p.costing_only=1) AND tf.is_compulsary=1 ";

		$rsm = new \Doctrine\ORM\Query\ResultSetMapping;
		$rsm -> addScalarResult('total', 'total');

		$query = $this -> CI -> doctrine -> em -> createNativeQuery($sql, $rsm);
		$query -> setParameter(1, $freqName);
		$query -> setParameter(2, $classiId);
		$query -> setParameter(3, $feesProfileId);

		$result = $query -> getArrayResult();

		return $result[0]['total'];
	}

	public function getCompulsaryTotalClassFees($classiId, $feesProfileId, $freqName) {

		$sql = "select sum(amount) AS 'total' from term_fees tf
		JOIN student_fee_type sf ON sf.id = tf.student_fee_type_id
		join fee_frequency_type q ON q.id= sf.fee_frequency_type_id
		JOIN fees_profile p ON p.id = sf.fees_profile_id
		WHERE tf.is_compulsary=1 AND tf.class_instance_id =? AND ( p.id=? OR p.costing_only=1) 
		AND q.name=?";

		$rsm = new \Doctrine\ORM\Query\ResultSetMapping;
		$rsm -> addScalarResult('total', 'total');

		$query = $this -> CI -> doctrine -> em -> createNativeQuery($sql, $rsm);
		$query -> setParameter(1, $classiId);
		$query -> setParameter(2, $feesProfileId);
		$query -> setParameter(3, $freqName);

		$result = $query -> getArrayResult();

		return $result[0]['total'];
	}

	public function postFees($form) {

		$current_term = Utilities::getCurrentTerm();

		$studentId = $form['active_student_id'];

		$account = $this -> getStudentAccount($studentId);

		if (is_null($account)) {
			return array('success' => FALSE, 'msg' => 'No Fees Posted: Student has no account');
		}

		$fees = array();
		$feeTypeList = array();

		foreach ($form as $key => $value) {
			if (strstr($key, 'fee') && floatval($value) > 0) {
				$amount = str_replace(array(','), '', $value);
				$fees[] = array('id' => substr($key, 9), 'amount' => $amount);
				$feeTypeList[] = substr($key, 9);
			}
		}

		$paid = count($fees);

		if ($paid == 0) {
			return array('success' => FALSE, 'msg' => 'No Fees Posted.');
		}

		$transxn = new Entities\Transaction;

		$transxn -> setTransactionType($this -> getTransactionTypeByName('FEES DEPOSIT'));
		$transxn -> setTerm($current_term);
		$transxn -> setCreatedBy($this -> CI -> session -> userdata('username'));
		$transxn -> setNarrative($form['narrative']);
		$transxn -> setPaySlipNumber($form['pay-slip']);

		$done = new DateTime($form['date-done']);
		$transxn -> setDateDone($done);

		$transxn -> setDateCreated(new DateTime());
		$transxn -> setDateLastModified(new DateTime());
		$transxn -> setIsValid(1);

		$this -> CI -> doctrine -> em -> persist($transxn);

		$schoolAcc = $this -> CI -> doctrine -> em -> getRepository('Entities\Account') -> findOneBy(array('name' => $this -> MainFeesAccount));

		foreach ($fees as $f) {

			$studentPost = new Entities\AccountPosting;

			$studentPost -> setAccount($account);
			$studentPost -> setAmount(-1 * $f['amount']);
			$studentPost -> setStudentFeeType($this -> CI -> doctrine -> em -> getReference('Entities\StudentFeeType', $f['id']));
			$studentPost -> setTransaction($transxn);
			$studentPost -> setDateCreated(new DateTime());
			$studentPost -> setDateLastModified(new DateTime());
			$studentPost -> setIsValid(1);
			$this -> CI -> doctrine -> em -> persist($studentPost);

			$schoolPost = new Entities\AccountPosting;

			$schoolPost -> setAccount($schoolAcc);
			$schoolPost -> setAmount($f['amount']);
			$schoolPost -> setStudentFeeType($this -> CI -> doctrine -> em -> getReference('Entities\StudentFeeType', $f['id']));
			$schoolPost -> setTransaction($transxn);
			$schoolPost -> setDateCreated(new DateTime());
			$schoolPost -> setDateLastModified(new DateTime());
			$schoolPost -> setIsValid(1);

			$this -> CI -> doctrine -> em -> persist($schoolPost);

		}

		try {

			$this -> CI -> doctrine -> em -> flush();

			foreach ($feeTypeList as $ft) {
				$feeType = $this -> CI -> doctrine -> em -> find('Entities\StudentFeeType', $ft);

				if (!is_null($feeType)) {

					$freqType = $feeType -> getFeeFrequencyType();

					if (!is_null($freqType)) {
						$this -> updateStudentFeesSummary($studentId, $current_term -> getId(), $freqType -> getName());
					}
				}

			}

			return array('success' => TRUE, 'msg' => 'Fees posted', 'studentId' => $studentId);

		} catch( \PDOException $e ) {
			WriteLog(LogAction::AccountError, 'student', $account -> getId(), $e -> getMessage());
			return array('success' => FALSE, 'msg' => 'Fees not posted');
		}

	}

	public function payForInventoryItems($form) {
		$ids = explode('|', $form['pay_for_items']);

		foreach ($ids as $p) {
			$inv = $this -> CI -> doctrine -> em -> find('Entities\StudentInventoryRequirement', $p);

			if (!is_null($inv)) {
				$inv -> setWasPaid(1);
				$this -> CI -> doctrine -> em -> persist($inv);

				$trxn = $inv -> getTransaction();
				if (!is_null($trxn)) {
					$trxn -> setPaySlipNumber($form['requirements_pay_slip']);
				}

				$this -> CI -> doctrine -> em -> flush();

			}
		}

		return array('success' => TRUE);
	}

	public function getStudentAccount($studentId) {

		$student = $this -> CI -> doctrine -> em -> find('Entities\Student', $studentId);

		if ($student != NULL) {
			$sac = $student -> getAccount();

			if (is_null($sac)) {
				$newAC = $this -> createStudentAccount($student -> getSurname(), $student -> getFirstName(), $student -> getStudentNumber());

				$student -> setAccount($newAC);
				$this -> CI -> doctrine -> em -> persist($student);
				$this -> CI -> doctrine -> em -> flush();

				return $newAC;
			} else {
				return $sac;
			}
		}

		return NULL;

	}

	public function postBursary($form) {

		$studentId = $form['student_id'];
		$amount = $form['bursary_amount'];
		$comments = $form['bursary_comments'];

		$current_term = Utilities::getCurrentTerm();

		$account = $this -> getStudentAccount($studentId);

		if (is_null($account)) {
			return array('success' => FALSE, 'msg' => 'No Bursary Posted: Student has no account');
		}

		$transxn = new Entities\Transaction;

		$transxn -> setTransactionType($this -> getTransactionTypeByName('BURSARY'));
		$transxn -> setTerm($current_term);
		$transxn -> setCreatedBy($this -> CI -> session -> userdata('username'));
		$transxn -> setNarrative('Bursary. ' . $comments);
		$transxn -> setPaySlipNumber('BURSARY/SCHOOL');

		$transxn -> setDateDone(new DateTime());

		$transxn -> setDateCreated(new DateTime());
		$transxn -> setDateLastModified(new DateTime());
		$transxn -> setIsValid(1);

		$this -> CI -> doctrine -> em -> persist($transxn);

		$bursaryAcc = $this -> CI -> doctrine -> em -> getRepository('Entities\Account') -> findOneBy(array('name' => $this -> BursariesAccount));

		$bursaryRec = new Entities\Bursary;

		$bursaryRec -> setStudent($this -> CI -> doctrine -> em -> getReference('Entities\Student', $studentId));
		$bursaryRec -> setTerm($current_term);
		$bursaryRec -> setAmount($amount);
		$bursaryRec -> setComments($comments);
		$bursaryRec -> setTransaction($transxn);
		$bursaryRec -> setGivenBy($this -> CI -> session -> userdata('surname') . ' ' . $this -> CI -> session -> userdata('first_name'));
		$bursaryRec -> setDateCreated(new DateTime());
		$bursaryRec -> setDateLastModified(new DateTime());
		$bursaryRec -> setIsValid(1);

		$this -> CI -> doctrine -> em -> persist($bursaryRec);

		$studentPost = new Entities\AccountPosting;

		$studentPost -> setAccount($account);
		$studentPost -> setAmount($amount);
		$studentPost -> setTransaction($transxn);
		$studentPost -> setDateCreated(new DateTime());
		$studentPost -> setDateLastModified(new DateTime());
		$studentPost -> setIsValid(1);

		$this -> CI -> doctrine -> em -> persist($studentPost);

		$bursaryPost = new Entities\AccountPosting;

		$bursaryPost -> setAccount($bursaryAcc);
		$bursaryPost -> setAmount(-1 * $amount);
		$bursaryPost -> setTransaction($transxn);
		$bursaryPost -> setDateCreated(new DateTime());
		$bursaryPost -> setDateLastModified(new DateTime());
		$bursaryPost -> setIsValid(1);

		$this -> CI -> doctrine -> em -> persist($bursaryPost);

		try {

			$this -> CI -> doctrine -> em -> flush();

			//bursaries are terminal

			$this -> updateStudentFeesSummary($studentId, $current_term -> getId(), 'TERMINAL');

			$this -> CI -> load -> library('Studentservice');
			$stSvc = new Studentservice();
			$std = $stSvc -> getStudent($studentId);

			if ($std -> getClassInstance()) {
				$stSvc -> getStudentClassHistory($studentId, $std -> getClassInstance() -> getId());
			}

			return array('success' => TRUE, 'msg' => 'Bursary offer posted', 'studentId' => $studentId);

		} catch( \PDOException $e ) {
			WriteLog(LogAction::BursaryOperation, 'bursary', $account -> getId(), $e -> getMessage());
			return array('success' => FALSE, 'msg' => 'Bursary award not posted');
		}

	}

	public function getFeesPaidForTerm($studentId, $termId) {
		$sql = "select ap.id, ft.name, DATE_FORMAT(tx.date_done, '%d-%b-%Y') AS `date_done`, ap.amount, tx.id AS `tx_id`, tx.pay_slip_number, fs.is_compulsary from account_posting ap
		join transaction tx ON ap.transaction_id = tx.id
		join student st ON st.account_id= ap.account_id
		join student_fee_type ft ON ft.id = ap.student_fee_type_id
		join account ac ON ac.id= ap.account_id
		join term_fees fs ON fs.student_fee_type_id = ap.student_fee_type_id AND fs.class_instance_id = st.class_instance_id
		JOIN fee_frequency_type q ON ft.fee_frequency_type_id=q.id AND q.name=?
		where st.id=? and tx.term_id =? AND ap.is_valid=1
		ORDER BY tx.date_done DESC
		";

		$rsm = new \Doctrine\ORM\Query\ResultSetMapping;
		$rsm -> addScalarResult('id', 'id');
		$rsm -> addScalarResult('name', 'name');
		$rsm -> addScalarResult('date_done', 'date_done');
		$rsm -> addScalarResult('amount', 'amount');
		$rsm -> addScalarResult('tx_id', 'tx_id');
		$rsm -> addScalarResult('is_compulsary', 'is_compulsary');
		$rsm -> addScalarResult('pay_slip_number', 'pay_slip_number');

		$query = $this -> CI -> doctrine -> em -> createNativeQuery($sql, $rsm);
		$query -> setParameter(1, 'TERMINAL');
		$query -> setParameter(2, $studentId);
		$query -> setParameter(3, $termId);

		return $query -> getArrayResult();

	}

	public function getFeesPaidBreakDownForTerm($studentId, $termId, $termFreqName = 'TERMINAL') {
		$sql = "select ap.id, ft.name, DATE_FORMAT(tx.date_done, '%d-%b-%Y') AS `date_done`, ap.amount, tx.id AS `tx_id`, tx.pay_slip_number, fs.is_compulsary from account_posting ap
		join transaction tx ON ap.transaction_id = tx.id
		join student st ON st.account_id= ap.account_id
		join student_fee_type ft ON ft.id = ap.student_fee_type_id
		JOIN fee_frequency_type q ON ft.fee_frequency_type_id=q.id AND q.name=? 
		join account ac ON ac.id= ap.account_id
		join term_fees fs ON fs.student_fee_type_id = ap.student_fee_type_id AND fs.class_instance_id = st.class_instance_id
		where st.id=? and tx.term_id =? AND ap.is_valid=1
		ORDER BY tx.date_done DESC
		";

		$rsm = new \Doctrine\ORM\Query\ResultSetMapping;
		$rsm -> addScalarResult('id', 'id');
		$rsm -> addScalarResult('name', 'name');
		$rsm -> addScalarResult('date_done', 'date_done');
		$rsm -> addScalarResult('amount', 'amount');
		$rsm -> addScalarResult('tx_id', 'tx_id');
		$rsm -> addScalarResult('is_compulsary', 'is_compulsary');
		$rsm -> addScalarResult('pay_slip_number', 'pay_slip_number');

		$query = $this -> CI -> doctrine -> em -> createNativeQuery($sql, $rsm);

		$query -> setParameter(1, $termFreqName);
		$query -> setParameter(2, $studentId);
		$query -> setParameter(3, $termId);

		return $query -> getArrayResult();

	}

	public function getFeesPaidBreakDownForYear($studentId, $termId) {
		$sql = "select ap.id, ft.name, DATE_FORMAT(tx.date_done, '%d-%b-%Y') AS `date_done`, ap.amount, tx.id AS `tx_id`, tx.pay_slip_number, fs.is_compulsary from account_posting ap
		join transaction tx ON ap.transaction_id = tx.id
		join student st ON st.account_id= ap.account_id
		join student_fee_type ft ON ft.id = ap.student_fee_type_id
		JOIN fee_frequency_type q ON ft.fee_frequency_type_id=q.id AND q.name=? 
		join account ac ON ac.id= ap.account_id
		join term_fees fs ON fs.student_fee_type_id = ap.student_fee_type_id AND fs.class_instance_id = st.class_instance_id
		JOIN term tm ON tm.id = tx.term_id 
		where st.id=? and tm.academic_year_id=? AND ap.is_valid=1
		ORDER BY tx.date_done DESC
		";

		$rsm = new \Doctrine\ORM\Query\ResultSetMapping;
		$rsm -> addScalarResult('id', 'id');
		$rsm -> addScalarResult('name', 'name');
		$rsm -> addScalarResult('date_done', 'date_done');
		$rsm -> addScalarResult('amount', 'amount');
		$rsm -> addScalarResult('tx_id', 'tx_id');
		$rsm -> addScalarResult('is_compulsary', 'is_compulsary');
		$rsm -> addScalarResult('pay_slip_number', 'pay_slip_number');

		$query = $this -> CI -> doctrine -> em -> createNativeQuery($sql, $rsm);

		$query -> setParameter(1, 'ANNUAL');
		$query -> setParameter(2, $studentId);

		$yQuery = $this -> CI -> doctrine -> em -> createQuery('SELECT t FROM Entities\Term t ' . ' JOIN t.academic_year y WHERE y.is_valid=1 AND t.id=?1');
		$yQuery -> setParameter(1, $termId);

		$acTerms = $yQuery -> getResult();

		$tterm = $acTerms[0];
		//must exist!!!

		$query -> setParameter(3, $tterm -> getAcademicYear() -> getId());

		return $query -> getArrayResult();
	}

	public function getFeesPaidBreakDownForOnce($studentId) {

		$sql = "select ap.id, ft.name, DATE_FORMAT(tx.date_done, '%d-%b-%Y') AS `date_done`, ap.amount, tx.id AS `tx_id`, tx.pay_slip_number, fs.is_compulsary from account_posting ap
		join transaction tx ON ap.transaction_id = tx.id
		join student st ON st.account_id= ap.account_id
		join student_fee_type ft ON ft.id = ap.student_fee_type_id
		JOIN fee_frequency_type q ON ft.fee_frequency_type_id=q.id AND q.name=? 
		join account ac ON ac.id= ap.account_id
		join term_fees fs ON fs.student_fee_type_id = ap.student_fee_type_id AND fs.class_instance_id = st.class_instance_id
		JOIN term tm ON tm.id = tx.term_id 
		where st.id=? AND ap.is_valid=1
		ORDER BY tx.date_done DESC
		";

		$rsm = new \Doctrine\ORM\Query\ResultSetMapping;
		$rsm -> addScalarResult('id', 'id');
		$rsm -> addScalarResult('name', 'name');
		$rsm -> addScalarResult('date_done', 'date_done');
		$rsm -> addScalarResult('amount', 'amount');
		$rsm -> addScalarResult('tx_id', 'tx_id');
		$rsm -> addScalarResult('is_compulsary', 'is_compulsary');
		$rsm -> addScalarResult('pay_slip_number', 'pay_slip_number');

		$query = $this -> CI -> doctrine -> em -> createNativeQuery($sql, $rsm);

		$query -> setParameter(1, 'ONCE');
		$query -> setParameter(2, $studentId);

		return $query -> getArrayResult();
	}

	public function getTermBursaries($termId = NULL) {

		if (is_null($termId)) {
			$current_term = Utilities::getCurrentTerm();
			$termId = $current_term -> getId();
		}

		$sql = "SELECT s.id AS `student_id`, s.surname, s.first_name, s.student_number, b.amount, c.name AS `class`, f.name AS `fees_profile`,100.00*amount/
				IFNULL((select sum(amount) AS 'total' from term_fees tf
				JOIN student_fee_type sf ON sf.id = tf.student_fee_type_id
				JOIN fees_profile p ON p.id = sf.fees_profile_id
				WHERE tf.class_instance_id =c.id AND ( p.id=f.id OR p.costing_only=1) AND tf.is_compulsary=1),1) AS `pct`  
				from bursary b
				JOIN student s ON s.id=b.student_id
				JOIN class_instance c ON c.id = s.class_instance_id
				JOIN fees_profile f ON f.id = s.fees_profile_id
				WHERE s.is_valid=1 AND b.is_valid=1 AND b.term_id=?
				ORDER BY s.surname
						";
		$rsm = new \Doctrine\ORM\Query\ResultSetMapping;
		$rsm -> addScalarResult('student_id', 'student_id');
		$rsm -> addScalarResult('surname', 'surname');
		$rsm -> addScalarResult('first_name', 'first_name');
		$rsm -> addScalarResult('student_number', 'student_number');
		$rsm -> addScalarResult('amount', 'amount');
		$rsm -> addScalarResult('class', 'class');
		$rsm -> addScalarResult('fees_profile', 'fees_profile');
		$rsm -> addScalarResult('pct', 'pct');

		$query = $this -> CI -> doctrine -> em -> createNativeQuery($sql, $rsm);
		$query -> setParameter(1, $termId);

		return $query -> getArrayResult();

	}

	public function getStudentBursary($studentId, $termId = NULL) {

		if (is_null($termId)) {
			$current_term = Utilities::getCurrentTerm();
			$termId = $current_term -> getId();
		}

		$query = $this -> CI -> doctrine -> em -> createQuery('SELECT b FROM Entities\Bursary b ' . ' JOIN b.term t WHERE b.is_valid=1 AND b.student=?1 AND t.id = ?2');
		$query -> setParameter(1, $studentId);
		$query -> setParameter(2, $termId);

		return $query -> getArrayResult();

	}

	public function deleteBursary($bursaryId) {

		$query = $this -> CI -> doctrine -> em -> createQuery('SELECT b FROM Entities\Bursary b ' . ' JOIN b.student s JOIN b.transaction t ' . 'JOIN t.term m WHERE b.is_valid=1 AND b.id = ?1');
		$query -> setParameter(1, $bursaryId);

		$found = $query -> getResult();

		if (is_array($found) && count($found) == 1) {

			$bursary = $found[0];

			$bursary -> setIsValid(0);
			$this -> CI -> doctrine -> em -> persist($bursary);

			$txn = $bursary -> getTransaction();
			$txn -> setIsValid(0);
			$this -> CI -> doctrine -> em -> persist($txn);

			$ap_query = $this -> CI -> doctrine -> em -> createQuery('SELECT a FROM Entities\AccountPosting a ' . ' JOIN a.transaction t WHERE a.is_valid=1 AND t.id = ?1');
			$ap_query -> setParameter(1, $txn -> getId());

			$aps = $ap_query -> getResult();

			foreach ($aps as $a) {
				$a -> setIsValid(0);
				$this -> CI -> doctrine -> em -> persist($a);
			}

			$this -> CI -> doctrine -> em -> flush();

			//bursaries are terminal
			$this -> updateStudentFeesSummary($bursary -> getStudent() -> getId(), $bursary -> getTransaction() -> getTerm() -> getId(), 'TERMINAL');

			return array('success' => TRUE, 'msg' => 'deleted');
		}

		return array('success' => FALSE, 'msg' => 'Unknown bursary');
	}

	//start of required items
	public function editRequiredItem($form) {
		$id = $form['item_id'];
		$name = $form['item_name'];
		$description = $form['description'];
		$fee_profile_id = $form['fees_profile_id'];

		$profile = $this -> CI -> doctrine -> em -> find('Entities\FeesProfile', $fee_profile_id);

		$itm = new Entities\StudentRequiredItem;

		if (is_null($id) || $id == '') {
			$itm -> setName(strtoupper($name));
			$itm -> setDescription($description);
			$itm -> setFeesProfile($profile);
			$itm -> setDateCreated(new DateTime());
			$itm -> setDateLastModified(new DateTime());
			$itm -> setIsValid(1);
		} else {
			$itm = $this -> CI -> doctrine -> em -> find('Entities\StudentRequiredItem', $id);

			if ($itm == NULL) {
				return NULL;
			}

			$itm -> setName(strtoupper($name));
			$itm -> setDescription($description);
			$itm -> setFeesProfile($profile);
			$itm -> setDateLastModified(new DateTime());
		}

		$this -> CI -> doctrine -> em -> persist($itm);

		try {
			$this -> CI -> doctrine -> em -> flush();
		} catch( \PDOException $e ) {
			if ($e -> getCode() === '23000') {
				return NULL;
			}
		}

		return $itm;
	}

	public function getAllRequiredItems($feesProfileId = NULL) {

		$qb = $this -> CI -> doctrine -> em -> createQueryBuilder();

		if (is_null($feesProfileId)) {
			$qb -> select(array('i', 'f')) -> from('Entities\StudentRequiredItem', 'i') -> leftJoin('i.fees_profile', 'f') -> where('i.is_valid = 1 ') -> orderBy('i.name', 'ASC');
			$query = $qb -> getQuery();
		} else {

			$qb -> select(array('i', 'f')) -> from('Entities\StudentRequiredItem', 'i') -> leftJoin('i.fees_profile', 'f') -> where('i.is_valid = 1 AND (f.costing_only=1 OR i.fees_profile=?1)') -> orderBy('i.name', 'ASC');

			$qb -> setParameter(1, $feesProfileId);

			$query = $qb -> getQuery();
		}

		return $query -> getResult();
	}

	public function getRequiredItem($id) {
		return $this -> CI -> doctrine -> em -> find('Entities\StudentRequiredItem', $id);
	}

	public function getItemsCostStructure() {

		$term = Utilities::getCurrentTerm();

		if (is_null($term)) {
			return NULL;
		}

		$prices = array();
		$types = $this -> getAllRequiredItems();

		foreach ($types as $t) {
			$profile = 'N/A';
			if ($t -> GetFeesProfile()) {
				$profile = $t -> getFeesProfile() -> getName();
			}
			$prices[$t -> getId()] = array('id' => $t -> getId(), 'name' => $t -> getName(), 'fees_profile' => $profile, 'price' => NULL, 'transport' => NULL);
		}

		$queryBuilder = $this -> CI -> doctrine -> em -> createQueryBuilder();

		$queryBuilder -> select(array('p', 'r')) -> from('Entities\StudentRequiredItemTermPrice', 'p') -> innerJoin('p.student_required_item', 'r') -> innerJoin('p.term', 't') -> where('t.id=:tid') -> setParameter('tid', $term -> getId());

		$query = $queryBuilder -> getQuery();

		$result = $query -> getResult();

		foreach ($result as $r) {
			$prices[$r -> getStudentRequiredItem() -> getId()]['price'] = $r -> getPrice();
			$prices[$r -> getStudentRequiredItem() -> getId()]['transport'] = $r -> getTransportCost();
		}

		return $prices;
	}

	public function updateRequirementsCosts($form) {

		$term = Utilities::getCurrentTerm();

		if (is_null($term)) {
			return array('success' => FALSE, 'msg' => 'Invalid Current Term');
		}

		$types = $this -> getAllRequiredItems();

		$updates = 0;

		foreach ($types as $t) {
			$feeName = 'price_' . $t -> getId();
			$transName = 'transport_' . $t -> getId();

			if (intval($form[$feeName]) > 0 || floatval($form[$transName]) > 0) {
				if ($this -> updateTermRequirementsCosts($t -> getId(), $form[$feeName], $form[$transName])) {
					$updates++;
				}
			}
		}

		$feedback = '';

		if ($updates > 0) {
			$feedback = 'Requirements Costs updated successfully.<br/>' . $updates . ' made.';
		}
		return array('success' => TRUE, 'msg' => $feedback);
	}

	public function getStudentItemInventory($studentId, $termId = NULL) {

		$term = NULL;

		if (is_null($termId)) {

			$term = Utilities::getCurrentTerm();

			if (is_null($term)) {
				return array('success' => FALSE, 'msg' => 'Invalid Current Term');
			}

			$termId = $term -> getId();
		}

		$sql = "select siv.id, siv.student_id, siv.class_instance_id, siv.term_id, siv.transaction_id, IFNULL(siv.number_brought_by_student,0) AS `number_brought_by_student`,
				 IFNULL(siv.number_bought_by_school,0) AS `number_bought_by_school`, siv.date_created, siv.date_last_modified, siv.is_valid ,
				 si.id AS `item_id`, si.name AS `item_name`, ip.price, ip.transport_cost,  
				 IFNULL(siv.number_bought_by_school,0)*IFNULL(ip.price,0) +
				  CASE WHEN siv.number_bought_by_school>0 THEN IFNULL(ip.transport_cost,0) ELSE NULL END AS `total_charge`, was_paid 
				 FROM student_inventory_requirement siv
				LEFT JOIN student_required_item si ON si.id = siv.student_required_item_id
				LEFT JOIN student_required_item_term_price ip ON ip.student_required_item_id = si.id 
				WHERE siv.is_valid=1 AND siv.student_id=? AND siv.term_id=?";

		$rsm = new \Doctrine\ORM\Query\ResultSetMapping;
		$rsm -> addScalarResult('id', 'id');
		$rsm -> addScalarResult('student_id', 'student_id');
		$rsm -> addScalarResult('class_instance_id', 'class_instance_id');
		$rsm -> addScalarResult('term_id', 'term_id');
		$rsm -> addScalarResult('transaction_id', 'transaction_id');
		$rsm -> addScalarResult('number_brought_by_student', 'number_brought_by_student');
		$rsm -> addScalarResult('number_bought_by_school', 'number_bought_by_school');
		$rsm -> addScalarResult('date_created', 'date_created');
		$rsm -> addScalarResult('date_last_modified', 'date_last_modified');
		$rsm -> addScalarResult('is_valid', 'is_valid');
		$rsm -> addScalarResult('item_id', 'item_id');
		$rsm -> addScalarResult('item_name', 'item_name');
		$rsm -> addScalarResult('price', 'price');
		$rsm -> addScalarResult('transport_cost', 'transport_cost');
		$rsm -> addScalarResult('total_charge', 'total_charge');
		$rsm -> addScalarResult('was_paid', 'was_paid');

		$query = $this -> CI -> doctrine -> em -> createNativeQuery($sql, $rsm);
		$query -> setParameter(1, $studentId);
		$query -> setParameter(2, $termId);

		$result = $query -> getArrayResult();
		$count = count($result);

		$this -> CI -> load -> library('Studentservice');

		$stSvc = new Studentservice();

		$cStudent = $stSvc -> getStudent($studentId);

		if (!is_null($cStudent -> getFeesProfile())) {

			if ($count != count($this -> getAllRequiredItems($cStudent -> getFeesProfile() -> getId()))) {

				$this -> createDefaultStudentInventoryForTerm($studentId, $termId);

				$result = $query -> getArrayResult();
			}
		}

		return $result;

	}

	public function getTermProcurables() {

		$term = Utilities::getCurrentTerm();

		if (is_null($term)) {
			return array('success' => FALSE, 'msg' => 'Invalid Current Term');
		}

		$prices = array();

		$queryBuilder = $this -> CI -> doctrine -> em -> createQueryBuilder();

		$queryBuilder -> select(array('p', 'r')) -> from('Entities\StudentRequiredItemTermPrice', 'p') -> innerJoin('p.student_required_item', 'r') -> innerJoin('p.term', 't') -> where('t.id=:tid') -> setParameter('tid', $term -> getId());

		$query = $queryBuilder -> getQuery();

		$result = $query -> getResult();

		foreach ($result as $r) {
			$prices[] = array('item_id' => $r -> getId(), 'item_name' => $r -> getStudentRequiredItem() -> getName(), 'price' => $r -> getPrice(), 'transport_cost' => $r -> getTransportCost());
		}

		return $prices;
	}

	public function createDefaultStudentInventoryForTerm($studentId, $termId) {

		$query = $this -> CI -> doctrine -> em -> createQuery('SELECT s FROM Entities\Student s LEFT JOIN s.class_instance h' . ' LEFT JOIN s.fees_profile f' . '  WHERE s.id = ?1');
		$query -> setParameter(1, $studentId);

		$students = $query -> getResult();

		if (is_array($students) && count($students) > 0) {
			$student = $students[0];
		} else {
			return FALSE;
		}
		$feesId = -1;
		if (!is_null($student -> getFeesProfile())) {
			$feesId = $student -> getFeesProfile() -> getId();
		}
		$items = $this -> getAllRequiredItems($feesId);

		foreach ($items as $k) {

			$qb = $this -> CI -> doctrine -> em -> createQuery('SELECT r FROM Entities\StudentInventoryRequirement r
			JOIN r.class_instance c JOIN r.student_required_item i 
			JOIN r.student s  WHERE s.id=?1 AND r.term=?2 AND i.id=?3 ');
			$qb -> setParameter(1, $studentId);
			$qb -> setParameter(2, $termId);
			$qb -> setParameter(3, $k -> getId());

			$cx = $qb -> getResult();

			if (count($cx) == 0) {
				$invi = new Entities\StudentInventoryRequirement;

				$invi -> setStudent($student);
				$invi -> setTerm($this -> CI -> doctrine -> em -> getReference('Entities\Term', $termId));
				$invi -> setStudentRequiredItem($k);
				$invi -> setClassInstance($student -> getClassInstance());
				$invi -> setDateCreated(new DateTime());
				$invi -> setDateLastModified(new DateTime());
				$invi -> setIsValid(1);

				$this -> CI -> doctrine -> em -> persist($invi);
				$this -> CI -> doctrine -> em -> flush();

			}
		}

	}

	//end of required items processing

	public function editFeesComponent($form) {

		$id = $form['component_id'];
		$name = $form['component'];
		$description = $form['description'];
		$fee_profile_id = $form['fees_profile_id'];
		$fee_freq_id = $form['fees_freq_id'];

		$fcomp = new Entities\StudentFeeType;
		$profile = $this -> CI -> doctrine -> em -> find('Entities\FeesProfile', $fee_profile_id);

		//get frequency
		$fq = new Entities\FeeFrequencyType;
		$freq = $this -> CI -> doctrine -> em -> find('Entities\FeeFrequencyType', $fee_freq_id);

		if (is_null($id) || $id == '') {
			$fcomp -> setName(strtoupper($name));
			$fcomp -> setNarrative($description);
			$fcomp -> setFeesProfile($profile);
			$fcomp -> setFeeFrequencyType($freq);
			$fcomp -> setDateCreated(new DateTime());
			$fcomp -> setDateLastModified(new DateTime());
			$fcomp -> setIsValid(1);
		} else {
			$fcomp = $this -> CI -> doctrine -> em -> find('Entities\StudentFeeType', $id);

			if ($fcomp == NULL) {
				return array('success' => FALSE, 'msg' => 'Invalid Component');
			}

			$fcomp -> setName(strtoupper($name));
			$fcomp -> setFeesProfile($profile);
			$fcomp -> setFeeFrequencyType($freq);
			$fcomp -> setNarrative($description);
			$fcomp -> setDateLastModified(new DateTime());
		}

		$this -> CI -> doctrine -> em -> persist($fcomp);

		try {
			$this -> CI -> doctrine -> em -> flush();
		} catch( \PDOException $e ) {
			if ($e -> getCode() === '23000') {
				return array('success' => FALSE, 'msg' => 'Failed to save ' . $e -> getMessage());
			}
		}

		WriteLog(LogAction::FeesProfileActivity, 'fees-component', $fcomp -> getId(), $fcomp -> getName() . ' has been created/updated.');

		return array('success' => TRUE, 'msg' => 'Fees component has been saved. ', 'cmp' => $fcomp);
	}

	public function getFeesComponent($id) {
		return $this -> CI -> doctrine -> em -> find('Entities\StudentFeeType', $id);
	}

	public function getFeesFrequencyByName($fqName) {
		$st = $this -> CI -> doctrine -> em -> getRepository('Entities\FeeFrequencyType') -> findOneBy(array('name' => $fqName));
		return $st;
	}

	public function deletePosting($form) {

		$txnId = $form['txnId'];
		$txn = $this -> CI -> doctrine -> em -> find('Entities\Transaction', $txnId);

		if (is_null($txn)) {
			return array('success' => FALSE, 'msg' => 'Account entries were not found');
		} else {

			if ($txn -> getIsValid() == 0) {
				return array('success' => FALSE, 'msg' => 'Account transaction was deleted already');
			}

			$pQ = $this -> CI -> doctrine -> em -> createQuery('SELECT g FROM Entities\AccountPosting g WHERE g.transaction=?1 AND g.is_valid=1');
			$pQ -> setParameter(1, $txn);
			$postings = $pQ -> getResult();

			$countDel = 0;
			foreach ($postings as $apost) {
				$apost -> setIsValid(0);
				$apost -> setDateLastModified(new DateTime());
				$this -> CI -> doctrine -> em -> persist($apost);
				$this -> CI -> doctrine -> em -> flush();

				if ($apost -> getAmount() < 0) {
					$st = $this -> CI -> doctrine -> em -> getRepository('Entities\Student') -> findOneBy(array('account' => $apost -> getAccount()));
					WriteLog(LogAction::DeleteFeesEntry, 'fees', $apost -> getId(), (-1 * $apost -> getAmount()) . ' UGX has been deleted from ' . $st -> getSurname() . ' ' . $st -> getFirstName() . ' A/C.');
				}
				$countDel++;
			}

			if ($countDel > 0) {
				return array('success' => TRUE, 'msg' => 'Account entry has been deleted');
			} else {
				return array('success' => FALSE, 'msg' => 'Nothing was deleted');
			}

		}

	}

	public function updateInventory($form) {

		if ($form['brought'] == $form['new_brought']) {
			return array('success' => FALSE, 'msg' => 'No change in number of items brought by student. No need to update');
		} else {
			$req = $this -> CI -> doctrine -> em -> find('Entities\StudentInventoryRequirement', $form['rid']);
			if (is_null($req)) {
				return array('success' => FALSE, 'msg' => 'No record found');
			}

			$req -> setDateLastModified(new DateTime());
			$req -> setNumberBroughtByStudent(intval($form['new_brought']));

			$this -> CI -> doctrine -> em -> persist($req);
			$this -> CI -> doctrine -> em -> flush();

			return array('success' => TRUE, 'msg' => 'Updated');
		}

	}

	public function updateChargeInventory($form) {

		$term = Utilities::getCurrentTerm();

		if ($form['bought'] == $form['new_bought']) {
			return array('success' => FALSE, 'msg' => 'No change in number of items bought. No update needed.');
		} else {
			$req = $this -> CI -> doctrine -> em -> find('Entities\StudentInventoryRequirement', $form['rid']);
			if (is_null($req)) {
				return array('success' => FALSE, 'msg' => 'No record found');
			}

			$transxn = $req -> getTransaction();
			$item = $req -> getStudentRequiredItem();

			$exsQ = $this -> CI -> doctrine -> em -> createQuery('SELECT g FROM Entities\StudentRequiredItemTermPrice g WHERE g.term=?1 AND g.student_required_item=?2 ORDER BY g.date_created DESC');
			$exsQ -> setParameter(1, $term -> getId());
			$exsQ -> setParameter(2, $item -> getId());

			$price = NULL;
			$prices = $exsQ -> getResult();

			if (is_array($prices) && count($prices) > 0 && $prices[0] -> getPrice() > 0) {
				$price = $prices[0];
			}

			if (!is_null($price)) {

				$date = new DateTime();

				$req -> setDateLastModified($date);
				$req -> setNumberBoughtBySchool(intval($form['new_bought']));

				$cost = NULL;

				if (!is_null($form['new_bought'])) {
					$cost = $form['new_bought'] * $price -> getPrice() + $price -> getTransportCost();
				}

				if (is_null($transxn)) {

					$transxn = new Entities\Transaction;

					$transxn -> setTransactionType($this -> getTransactionTypeByName('SCHOOL REQUIREMENT CHARGE'));
					$transxn -> setTerm($term);
					$transxn -> setCreatedBy($this -> CI -> session -> userdata('username'));
					$transxn -> setNarrative('(' . $form['new_bought'] . ') ' . $item -> getName());
					$transxn -> setPaySlipNumber('school');

					$transxn -> setDateDone($date);
					$transxn -> setDateCreated($date);
					$transxn -> setDateLastModified($date);
					$transxn -> setIsValid(1);

					$req -> setTransaction($transxn);

					$this -> CI -> doctrine -> em -> persist($transxn);

					$mainFeesPost = new Entities\AccountPosting;

					$mainFeesPost -> setAccount($this -> getAccountByName($this -> MainFeesAccount));
					$mainFeesPost -> setAmount(-1 * $cost);
					$mainFeesPost -> setStudentFeeType(NULL);
					$mainFeesPost -> setTransaction($transxn);
					$mainFeesPost -> setDateCreated($date);
					$mainFeesPost -> setDateLastModified($date);
					$mainFeesPost -> setIsValid(1);
					$this -> CI -> doctrine -> em -> persist($mainFeesPost);

					$recPost = new Entities\AccountPosting;

					$recPost -> setAccount($this -> getAccountByName($this -> Receivables));
					$recPost -> setAmount($cost);
					$recPost -> setStudentFeeType(NULL);
					$recPost -> setTransaction($transxn);
					$recPost -> setDateCreated($date);
					$recPost -> setDateLastModified($date);
					$recPost -> setIsValid(1);

					$this -> CI -> doctrine -> em -> persist($recPost);

				} else {
					$postsQ = $this -> CI -> doctrine -> em -> createQuery('SELECT g FROM Entities\AccountPosting g WHERE g.transaction=?1 AND g.is_valid=1');
					$postsQ -> setParameter(1, $transxn -> getId());

					$posts = $postsQ -> getResult();

					foreach ($posts as $p) {
						$oldCost = $p -> getAmount();
						if ($oldCost > 0) {
							$p -> setAmount($cost);
						} else {
							$p -> setAmount($cost * -1);
						}

						$p -> setDateLastModified($date);
						$this -> CI -> doctrine -> em -> persist($p);
					}
				}
			} else {
				return array('success' => FALSE, 'msg' => 'No price/transport for item. Therefore no charge can be made.');
			}

			$this -> CI -> doctrine -> em -> persist($req);
			$this -> CI -> doctrine -> em -> flush();

			return array('success' => TRUE, 'msg' => 'Updated');
		}

	}

	public function deleteFromInventory($form) {

		$req = $this -> CI -> doctrine -> em -> find('Entities\StudentInventoryRequirement', $form['rid']);
		if (is_null($req)) {
			return array('success' => FALSE, 'msg' => 'No record found');
		}

		$req -> setDateLastModified(new DateTime());
		$req -> setNumberBroughtByStudent(NULL);

		$this -> CI -> doctrine -> em -> persist($req);
		$this -> CI -> doctrine -> em -> flush();

		return array('success' => TRUE, 'msg' => 'Updated');

	}

	public function getFeesFrequencies() {
		return $this -> CI -> doctrine -> em -> createQuery('SELECT g FROM Entities\FeeFrequencyType g ORDER BY g.display_position') -> getArrayResult();
	}

	/*
	 * Call after committing to account posts
	 * Added $freqName eg TERMINAL, ANNUAL, ADHOC, ONCE, HOLIDAY
	 * */
	public function updateStudentFeesSummary($studentId, $termId, $freqName) {

		$student = $this -> getStudent($studentId);

		if (is_null($student)) {
			return;
		}

		$classi = $student -> getClassInstance();

		if (is_null($classi)) {
			return;
		}

		$feesProf = $student -> getFeesProfile();

		if (is_null($feesProf)) {
			return;
		}

		$this -> CI -> load -> library('Studentservice');

		$stSvc = new Studentservice();

		$stSvc -> getStudentClassHistory($studentId);

		$sql = " select IFNULL(tf.is_compulsary,0) AS `compulsary`, SUM(ap.amount) AS `amount` from account_posting ap
					join transaction tx ON ap.transaction_id = tx.id
					join student st ON st.account_id= ap.account_id
					join account ac ON ac.id= ap.account_id
					JOIN term_fees tf ON tf.student_fee_type_id = ap.student_fee_type_id
					JOIN student_fee_type sf ON sf.id =  ap.student_fee_type_id
					JOIN fee_frequency_type q ON q.id = sf.fee_frequency_type_id AND q.name=?
					where st.id=? and tx.term_id =? AND ap.is_valid=1   AND tf.class_instance_id=(SELECT h.class_instance_id FROM student_class_history h WHERE h.student_id=? AND h.term_id=?)
					GROUP BY tf.is_compulsary";

		$rsm = new \Doctrine\ORM\Query\ResultSetMapping;
		$rsm -> addScalarResult('compulsary', 'compulsary');
		$rsm -> addScalarResult('amount', 'amount');

		$query = $this -> CI -> doctrine -> em -> createNativeQuery($sql, $rsm);

		$query -> setParameter(1, $freqName);
		$query -> setParameter(2, $studentId);
		$query -> setParameter(3, $termId);
		$query -> setParameter(4, $studentId);
		$query -> setParameter(5, $termId);

		$amountResult = $query -> getArrayResult();

		$queryBuilder = $this -> CI -> doctrine -> em -> createQueryBuilder();
		$queryBuilder -> select(array('fs', 's')) -> from('Entities\StudentFeesSummary', 'fs') -> innerJoin('fs.student', 's') -> innerJoin('fs.fee_frequency_type', 'q') -> where('s.id=:sid AND fs.term=:tid AND q.name=:qname') -> setParameters(array('sid' => $studentId, 'tid' => $termId, 'qname' => $freqName));

		$query = $queryBuilder -> getQuery();
		$result = $query -> getResult();

		$summary = NULL;

		if (is_null($result) || count($result) == 0) {

			$summary = new Entities\StudentFeesSummary;

			$summary -> setTerm($this -> CI -> doctrine -> em -> getReference('Entities\Term', $termId));
			$summary -> setFeeFrequencyType($this -> getFeesFrequencyByName($freqName));
			$summary -> setStudent($student);
			$summary -> setDateCreated(new DateTime());
			$summary -> setIsValid(1);

		} else {

			$summary = $result[0];
		}

		$compulsoryCurrentlyPaid = 0;
		$othersCurrentlyPaid = 0;

		for ($k = 0; $k < count($amountResult); $k++) {

			if ($amountResult[$k]['compulsary'] == 1) {
				$compulsoryCurrentlyPaid = -1 * $amountResult[$k]['amount'];
			}

			if ($amountResult[$k]['compulsary'] == 0) {
				$othersCurrentlyPaid = -1 * $amountResult[$k]['amount'];
			}
		}

		if ($freqName == 'TERMINAL') {

			$bursaries = $this -> getStudentBursary($studentId, $termId);

			if (is_array($bursaries) && count($bursaries) > 0) {

				foreach ($bursaries as $b) {
					$compulsoryCurrentlyPaid += $b['amount'];
				}
			}
		}

		$summary -> setCompulsaryAmountOwed($this -> getCompulsaryTotalClassFees($classi -> getId(), $feesProf -> getId(), $freqName));

		$summary -> setCompulsaryAmountPaid($compulsoryCurrentlyPaid);
		$summary -> setOtherAmountPaid($othersCurrentlyPaid);

		$summary -> setDateLastModified(new DateTime());

		$this -> CI -> doctrine -> em -> persist($summary);
		$this -> CI -> doctrine -> em -> flush();

	}

	public function getStudentTermFeesSummary($studentId, $termId) {

		$student = $this -> getStudent($studentId);

		if (is_null($student)) {
			return array('success' => FALSE, 'msg' => 'Unknown student');
		}

		$classi = $student -> getClassInstance();

		if (is_null($classi)) {
			return array('success' => FALSE, 'msg' => 'Unknown class');
		}

		$feesProf = $student -> getFeesProfile();

		if (is_null($feesProf)) {
			return array('success' => FALSE, 'msg' => 'Student has no Fees Profile');
		}

		$queryBuilder = $this -> CI -> doctrine -> em -> createQueryBuilder();
		$queryBuilder -> select(array('fs', 's')) -> from('Entities\StudentFeesSummary', 'fs') -> innerJoin('fs.student', 's') -> where('s.id=:sid AND fs.term=:tid') -> setParameters(array('sid' => $studentId, 'tid' => $termId));

		$query = $queryBuilder -> getQuery();
		$result = $query -> getResult();

		$summary = NULL;

		if (is_null($result) || count($result) == 0) {
			return array('success' => FALSE, 'msg' => 'Student has made no payments yet');
		} else {
			$summary = $result[0];
			return array('success' => TRUE);
		}

	}

	//start of privates

	private function getStudent($id) {

		$query = $this -> CI -> doctrine -> em -> createQuery('SELECT s FROM Entities\Student s LEFT JOIN s.house h' . ' LEFT JOIN s.class_instance c LEFT JOIN s.fees_profile f WHERE s.id = ?1');

		$query -> setParameter(1, $id);

		$student = $query -> getResult();

		if (is_array($student) && count($student) > 0) {
			return $student[0];
		}
		return NULL;
	}

	private function updateTermFee($classiId, $feeTypeId, $amount, $makeCompulsary = FALSE) {

		$queryBuilder = $this -> CI -> doctrine -> em -> createQueryBuilder();

		$queryBuilder -> select(array('f', 't')) -> from('Entities\TermFees', 'f') -> innerJoin('f.student_fee_type', 't') -> innerJoin('f.class_instance', 'c') -> where('c.id=:cid AND t.id=:tid') -> setParameters(array('cid' => $classiId, 'tid' => $feeTypeId));

		$query = $queryBuilder -> getQuery();

		$result = $query -> getResult();

		$cmps = 0;

		if ($makeCompulsary == TRUE) {
			$cmps = 1;
		}

		if (is_null($result) || count($result) == 0) {

			$tf = new Entities\TermFees;

			$tf -> setClassInstance($this -> CI -> doctrine -> em -> getReference('Entities\ClassInstance', $classiId));
			$tf -> setStudentFeeType($this -> CI -> doctrine -> em -> getReference('Entities\StudentFeeType', $feeTypeId));
			$tf -> setAmount($amount);
			$tf -> setIsCompulsary($cmps);
			$tf -> setDateCreated(new DateTime());
			$tf -> setDateLastModified(new DateTime());
			$tf -> setIsValid(1);

			$this -> CI -> doctrine -> em -> persist($tf);

			try {
				$this -> CI -> doctrine -> em -> flush();

				return TRUE;
			} catch( \PDOException $e ) {
				WriteLog(LogAction::Fees, 'term-fee', $classiId, $e -> getMessage());
			}
		} else {
			$upd = $result[0];
			if ($upd -> getAmount() != $amount || $upd -> getIsCompulsary() != $cmps) {

				$upd -> setIsCompulsary($cmps);
				$upd -> setAmount($amount);
				$upd -> setDateLastModified(new DateTime());

				$this -> CI -> doctrine -> em -> persist($upd);
				$this -> CI -> doctrine -> em -> flush();

				return TRUE;
			}
		}

		return FALSE;
	}

	private function updateTermRequirementsCosts($typeId, $price, $transport) {

		$term = Utilities::getCurrentTerm();

		if (is_null($term)) {
			return FALSE;
		}

		$queryBuilder = $this -> CI -> doctrine -> em -> createQueryBuilder();

		$queryBuilder -> select(array('p', 'r')) -> from('Entities\StudentRequiredItemTermPrice', 'p') -> innerJoin('p.student_required_item', 'r') -> innerJoin('p.term', 't') -> where('t.id=:cid AND r.id=:rid') -> setParameters(array('cid' => $term -> getId(), 'rid' => $typeId));

		$query = $queryBuilder -> getQuery();

		$result = $query -> getResult();

		if (is_null($result) || count($result) == 0) {

			$tf = new Entities\StudentRequiredItemTermPrice;

			$tf -> setStudentRequiredItem($this -> CI -> doctrine -> em -> getReference('Entities\StudentRequiredItem', $typeId));
			$tf -> setTerm($term);
			$tf -> setPrice($price);
			$tf -> setTransportCost($transport);
			$tf -> setDateCreated(new DateTime());
			$tf -> setDateLastModified(new DateTime());
			$tf -> setIsValid(1);

			$this -> CI -> doctrine -> em -> persist($tf);

			try {
				$this -> CI -> doctrine -> em -> flush();

				return TRUE;
			} catch( \PDOException $e ) {
				WriteLog(LogAction::Fees, 'item-fee', $typeId, $e -> getMessage());
			}
		} else {
			$upd = $result[0];

			if ($upd -> getPrice() != $price || $upd -> getTransportCost() != $transport) {

				$upd -> setPrice($price);
				$upd -> setTransportCost($transport);
				$upd -> setDateLastModified(new DateTime());

				$this -> CI -> doctrine -> em -> persist($upd);
				$this -> CI -> doctrine -> em -> flush();

				return TRUE;
			}
		}

		return FALSE;
	}

	public function utilityUpdateFeesSummaries() {

		$fst = $this -> CI -> doctrine -> em -> getRepository('Entities\FeesSummaryStatus') -> findOneBy(array('name' => 'CURRENT'));

		$nullSumQuery = $this -> CI -> doctrine -> em -> createQuery('SELECT f FROM Entities\StudentFeesSummary f WHERE f.fees_summary_status IS NULL');

		$ns = $nullSumQuery -> getResult();

		foreach ($ns as $s) {
			$s -> setFeesSummaryStatus($fst);
			$s -> setDateLastModified(new DateTime());
			$this -> CI -> doctrine -> em -> persist($s);
			$this -> CI -> doctrine -> em -> flush();
		}
	}


	public function getCurrentTermDefaulterSummary() {

		$currentTerm = Utilities::getCurrentTerm(TRUE);
		
		if(is_null($currentTerm)){
			return array('success'=>FALSE, 'msg'=>'No current term');
		}
		
		$now = new DateTime();
		
		if($now < $currentTerm->getDateEnded()){
			return array('success'=>FALSE, 'msg'=>'Term has not ended yet');
		}
		
		$sql = "select fs.id, fs.fee_frequency_type_id, 
		(IFNULL(fs.compulsary_amount_owed,0) - IFNULL(fs.compulsary_amount_paid,0)) AS 'total_debt_amount',
				(select id from debt_status where name='OUTSTANDING') AS 'debt_status_id'
				,(select id from debt_type where name='FEES') AS 'debt_type_id'
				, ".$currentTerm->getId(). " AS 'term_incurred_id'
				, s.account_id
				, sh.class_instance_id
				FROM student_fees_summary fs 
				JOIN fee_frequency_type q ON q.id = fs.fee_frequency_type_id
				JOIN student s ON s.id = fs.student_id
				JOIN student_class_history sh ON sh.student_id= fs.student_id AND sh.term_id = ?
				WHERE fs.term_id=?
				AND (IFNULL(fs.compulsary_amount_owed,0) - IFNULL(fs.compulsary_amount_paid,0)) > 0
				and fs.fees_summary_status_id=(SELECT id FROM fees_summary_status WHERE name='CURRENT');";

		$rsm = new \Doctrine\ORM\Query\ResultSetMapping;
		$rsm -> addScalarResult('id', 'id');
		$rsm -> addScalarResult('fee_frequency_type_id', 'fee_frequency_type_id');
		$rsm -> addScalarResult('total_debt_amount', 'total_debt_amount');
		$rsm -> addScalarResult('debt_status_id', 'debt_status_id');
		$rsm -> addScalarResult('debt_type_id', 'debt_type_id');
		$rsm -> addScalarResult('term_incurred_id', 'term_incurred_id');
		$rsm -> addScalarResult('account_id', 'account_id');
		$rsm -> addScalarResult('class_instance_id', 'class_instance_id');

		$query = $this -> CI -> doctrine -> em -> createNativeQuery($sql, $rsm);
		$query -> setParameter(1, $currentTerm->getId());
		$query -> setParameter(2, $currentTerm->getId());

		$result = $query -> getArrayResult();

		return array('success'=>TRUE, 'msg'=>'No. of defaulters found: '.count($result), 'count'=>count($result),'defaulters'=>$result);
	}
	

	public function closePaidUpFeesSummaries() {
			
		$currentTerm = Utilities::getCurrentTerm(TRUE);
		
		if(is_null($currentTerm)){
			return array('success'=>FALSE, 'msg'=>'No current term');
		}
		
		$now = new DateTime();
		
		if($now < $currentTerm->getDateEnded()){
			return array('success'=>FALSE, 'msg'=>'Term has not ended yet');
		}

		$allPaidStatus = $this->getFeesSummaryStatusByName('ALL PAID');
		
		$sql = "select fs.id FROM student_fees_summary fs 
				WHERE fs.term_id=?
				AND fs.compulsary_amount_owed IS NOT NULL
				AND fs.compulsary_amount_paid IS NOT NULL
				AND (fs.compulsary_amount_owed - fs.compulsary_amount_paid) <= 0
				and (fs.fees_summary_status_id IS NULL OR fs.fees_summary_status_id=(SELECT id FROM fees_summary_status WHERE name='CURRENT')); ";

		$rsm = new \Doctrine\ORM\Query\ResultSetMapping;
		$rsm -> addScalarResult('id', 'id');

		$query = $this -> CI -> doctrine -> em -> createNativeQuery($sql, $rsm);
		$query -> setParameter(1, $currentTerm->getId());
		
		$result = $query -> getArrayResult();
		
		if(is_array($result)&& count($result)>0){
				
			foreach($result as $p){
				$summ = $this -> CI -> doctrine -> em -> find('Entities\StudentFeesSummary', $p['id']);
				
				$summ->setFeesSummaryStatus($allPaidStatus);
				$summ -> setDateLastModified(new DateTime());
				
				$this -> CI -> doctrine -> em -> persist($summ);
			
			}
			
			try { //commit
				$this -> CI -> doctrine -> em -> flush();
				} catch( \PDOException $e ) {
					WriteLog(LogAction::DebtOperation, 'close-fees-summary', -1, $e -> getMessage());
					return NULL;
				}
				
				return array('success'=>TRUE, 'msg'=>'Successfully closed.','count'=>count($result));
		}

		return array('success'=>FALSE, 'msg'=>'Nothing to close found','count'=>0);
	}
			
	public function createFeesDebt($ddetail) {
					
				
		$summ = $this -> CI -> doctrine -> em -> find('Entities\StudentFeesSummary', $ddetail['id']);	
		
		if(is_null($summ)){
			return array('success'=>FALSE,'msg'=>'Unknown fees summary');
		}	
		
		if($summ->getFeesSummaryStatus()->getName()!='CURRENT'){
			return array('success'=>FALSE,'msg'=>'Invalid status');
		}		
			
		$owingStatus = $this->getFeesSummaryStatusByName('OWING');
		
		$debt = new Entities\Debt;
		
		$totalDebt = 0;

		$debt -> setNarrative(strtoupper($ddetail['narrative']));
		$debt -> setClassInstance($this -> CI -> doctrine -> em -> getReference('Entities\ClassInstance', $ddetail['class_instance_id']));
		$debt -> setDebtStatus($this -> CI -> doctrine -> em -> getReference('Entities\DebtStatus', $ddetail['debt_status_id']));
		$debt -> setDebtType($this -> CI -> doctrine -> em -> getReference('Entities\DebtType', $ddetail['debt_type_id']));
		$debt -> setTermIncurred($this -> CI -> doctrine -> em -> getReference('Entities\Term', $ddetail['term_incurred_id']));
		$debt -> setAccount($this -> CI -> doctrine -> em -> getReference('Entities\Account', $ddetail['account_id']));
		$debt -> setFeeFrequencyType($this -> CI -> doctrine -> em -> getReference('Entities\FeeFrequencyType', $ddetail['fee_frequency_type_id']));
		
		$debt -> setTotalDebtAmount($ddetail['total_debt_amount']);
		
		$totalDebt+=$ddetail['total_debt_amount'];
			
		$debt -> setDateCreated(new DateTime());
		$debt -> setDateLastModified(new DateTime());
		$debt -> setIsValid(1);
		

		$this -> CI -> doctrine -> em -> persist($debt);
		
		//2. update summary as owing
		$summ->setFeesSummaryStatus($owingStatus);
		$summ -> setDateLastModified(new DateTime());
		
		$this -> CI -> doctrine -> em -> persist($summ);

		try { //commit
			$this -> CI -> doctrine -> em -> flush();
		} catch( \PDOException $e ) {
			WriteLog(LogAction::DebtOperation, 'debt', -1, $e -> getMessage());
				return NULL;
		}

		return $debt;
	}

}
?>
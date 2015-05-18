<?php

class Reportservice {

	private $CI;

	function __construct() {
		$this -> CI = &get_instance();

		//memory_get_peak_usage

		//WriteLog( LogAction::Info, 'memory', -1, 'mem (MB) in use : '.(memory_get_peak_usage(TRUE)/1024/1024).' set mem: '.ini_get('memory_limit') );
	}

	public function getFeesStatus($studentId) {

		$student = $this -> CI -> doctrine -> em -> find('Entities\Student', $studentId);

		if ($student == NULL) {
			return '<div>Student Does exist</div>';
		}

		$templ = $this -> getTemplate('student-term-fees-status');

		$templ = str_replace('{STUDENT}', strtoupper($student -> getSurname()) . ' ' . strtoupper($student -> getFirstName()), $templ);
		$templ = str_replace('{STUDENT_NUMBER}', $student -> getStudentNumber(), $templ);
		$templ = str_replace('{CURRENT_USER}', strtoupper($this -> CI -> session -> userdata('surname')) . ' ' . $this -> CI -> session -> userdata('first_name'), $templ);
		$class = 'N/A';

		if ($student -> getClassInstance()) {
			$class = $student -> getClassInstance() -> getName();
		}

		$this -> CI -> load -> library('Accountsservice');

		$acSvc = new Accountsservice();

		$currentTerm = Utilities::getCurrentTerm();

		$feesPaid = $acSvc -> getFeesPaidForTerm($studentId, $currentTerm -> getId());

		$table = '<table class="table table-striped table-bordered table-condensed zeb class-list">' . '<thead><tr><td>#</td><td>FEE DESCRIPTION</td>' . '<td>Date Paid</td><td class="tright">Amount (UGX)</td><td>Bank Slip No.</td></tr></thead><tbody>';

		$i = 1;
		$all_total = 0;
		$compulsary_paid = 0;
		foreach ($feesPaid as $f) {

			$fee_name = $f['name'];

			$amount_str = number_format(-1 * $f['amount']);

			if ($f['is_compulsary'] == 1) {

				$fee_name = '<b>' . $f['name'] . '</b>';

				$amount_str = '<b>' . number_format(-1 * $f['amount']) . '</b>';

				$compulsary_paid += $f['amount'];
			}

			$table .= '<tr><td>' . $i . '</td><td>' . $fee_name . '</td><td>' . $f['date_done'] . '</td><td class="tright">' . $amount_str . '</td><td>' . $f['pay_slip_number'] . '</td></tr>';
			$i++;

			$all_total += $f['amount'];
		}

		$all_total = -1 * $all_total;
		$compulsary_paid = -1 * $compulsary_paid;

		$bursaries = $acSvc -> getStudentBursary($studentId);

		if (is_array($bursaries) && count($bursaries) > 0) {
			foreach ($bursaries as $b) {
				$table .= '<tr><td>' . $i . '</td><td>Bursary</td><td>' . $b['date_created'] -> format('d-M-Y') . '</td><td class="tright">' . number_format($b['amount']) . '</td><td>' . $b['given_by'] . '</td></tr>';
				$i++;
				$all_total += $b['amount'];
				$compulsary_paid += $b['amount'];
			}
		}

		$table .= '<tr><td colspan="3" align="center">TOTAL PAID (ALL)</td><td class="tright"><b>' . number_format($all_total) . '</b></td><td> </td></tr>';

		$classFees = 0;

		if ($student -> getClassInstance()) {
			$classFees = $acSvc -> getTotalCompulsaryClassFees($student -> getClassInstance() -> getId(), $student -> getFeesProfile() -> getId(), 'TERMINAL');
		}

		$bal = $classFees - $compulsary_paid;
		$table .= '<tr><td colspan="3" align="center"><b>EXPECTED COMPULSARY CLASS FEES</b></td><td class="tright"><b>' . number_format($classFees) . '</b></td><td> </td></tr>';
		$table .= '<tr><td colspan="3" align="center">TOTAL COMPULSARY FEES PAID</td><td class="tright"><b>' . number_format($compulsary_paid) . '</b></td><td> </td></tr>';
		$table .= '<tr><td colspan="3" align="center"><b>BALANCE (COMPULSARY FEES OWED)</b></td><td class="tright"><b>' . number_format($bal) . '</b></td><td> </td></tr>';

		$table .= '</tbody></table>';

		$templ = str_replace('{TOTAL_FEES}', number_format($classFees), $templ);

		$templ = str_replace('{TERM_FEES}', $table, $templ);

		$templ = str_replace('{CLASS_NAME}', $class, $templ);

		$templ = str_replace('{SCHOOL_NAME}', GetAppVariable('school_name'), $templ);
		$templ = str_replace('{SCHOOL_ADDRESS}', GetAppVariable('school_address'), $templ);

		$templ = str_replace('{TODAY}', date('D j, M Y, g:i A'), $templ);

		$this -> makePDF($templ);
	}

	public function getTermBursaries($termId) {

		$term = $this -> CI -> doctrine -> em -> find('Entities\Term', $termId);

		if ($term == NULL) {
			return '<div>Term Does exist</div>';
		}

		$templ = $this -> getTemplate('term-bursaries');

		$templ = str_replace('{TERM_NAME}', $term -> getName(), $templ);
		$templ = str_replace('{CURRENT_USER}', strtoupper($this -> CI -> session -> userdata('surname')) . ' ' . $this -> CI -> session -> userdata('first_name'), $templ);

		$this -> CI -> load -> library('Accountsservice');

		$acSvc = new Accountsservice();

		$term_awards = $acSvc -> getTermBursaries($termId);

		$table = '
		<table class="table table-striped table-bordered table-condensed zeb">
					<thead>
						<tr>
							<th>#</th>
							<th>Surname</th>
							<th>First Name</th>
							<th>St. No.</th>
							<th>Class</th>
							<th>Profile</th>
							<th>Amount</th>
							<th>% of Fees</th>
						</tr>
					</thead>
					<tbody>';
		$i = 1;
		$total = 0;
		foreach ($term_awards as $a) {
			$total += floatval($a['amount']);
			$table .= '<tr><td>' . $i++ . '</td><td>' . $a['surname'] . '</td><td>' . $a['first_name'] . '</td><td>' . $a['student_number'] . '</td><td>' . Utilities::getShortClassName($a['class']) . '</td><td>' . $a['fees_profile'] . '</td><td class="money-right">' . number_format($a['amount']) . '</td><td>' . number_format($a['pct'], 1) . '%</td></tr>';
		}
		$table .= '<tr><td colspan="6"><b>TOTAL</b></td><td class="money-right">' . number_format($total) . '</td><td>-</td></tr>';

		$table .= '</tbody></table>';

		$templ = str_replace('{BURSARIES}', $table, $templ);

		$templ = str_replace('{SCHOOL_NAME}', GetAppVariable('school_name'), $templ);
		$templ = str_replace('{SCHOOL_ADDRESS}', GetAppVariable('school_address'), $templ);

		$templ = str_replace('{TODAY}', date('D j, M Y, g:i A'), $templ);

		$this -> makePDF($templ);
	}

	public function getClassList($classiId) {

		$this -> CI -> load -> library('Academicservice');
		$acSvc = new Academicservice();

		$cl = $acSvc -> getClassInstance($classiId);

		$clname = '';

		if (is_null($cl)) {
			echo '<h3 class="error">Unknown Class!</h3>';
			return;
		} else {
			$clname = $cl -> getName();
		}

		$this -> CI -> load -> library('Studentservice');
		$stSvc = new Studentservice();

		$enrolled = $stSvc -> getStudentStatusByName('CURRENTLY_ENROLLED');

		$hits = $stSvc -> getStudentsAsArray(array('class_instance' => $classiId, 'status' => $enrolled -> getId()));

		$stats = array('Gender' => GroupByKeySummary($hits, 'gender'));
		$stats['Houses'] = GroupByKeySummary($hits, 'house');
		$stats['Fees Profiles'] = GroupByKeySummary($hits, 'fees_profile');

		$templ = $this -> getTemplate('class-list');

		$templ = str_replace('{CURRENT_USER}', strtoupper($this -> CI -> session -> userdata('surname')) . ' ' . $this -> CI -> session -> userdata('first_name'), $templ);

		$table = '<table class="table table-striped table-bordered table-condensed zeb class-list"><thead>' . '<tr><th>#</th><th>Surname</th><th>First Name</th><th class="central">Gender</th><th class="central">Student No.</th><th class="central">En. Year</th><th>Fees Profile</th><th>House</th></tr>' . '</thead><tbody>';

		foreach ($hits as $h) {
			$table .= '<tr><td>' . $h['index'] . '</td><td>' . $h['surname'] . '</td><td>' . $h['first_name'] . '</td><td class="central">' . $h['gender'] . '</td><td>' . $h['student_number'] . '</td><td class="central">' . $h['enrolled'] . '</td><td>' . $h['fees_profile'] . '</td><td>' . $h['house'] . '</td>' . '</tr>';
		}

		$table .= '</tbody></table>';

		$templ = str_replace('{DETAILS}', $table, $templ);

		$templ = str_replace('{CLASS_NAME}', $clname, $templ);

		$templ = str_replace('{SCHOOL_NAME}', GetAppVariable('school_name'), $templ);
		$templ = str_replace('{SCHOOL_ADDRESS}', GetAppVariable('school_address'), $templ);

		$templ = str_replace('{TODAY}', date('D j, M Y, g:i:s A'), $templ);

		$templ = str_replace('{REPORT_TITLE}', $clname, $templ);

		$shtml = '<h2>Class Summary</h2>';
		$shtml .= '<table class="table table-striped table-bordered table-condensed zeb class-list"><tbody>';

		foreach ($stats as $key => $value) {
			$shtml .= '<tr><td colspan="3" class="central" style="text-transform:uppercase;background:#fff !important;"><b>' . $key . '</b></td></tr>';

			$shtml .= '<tr><td>Category</td><td>No. of Students</td><td>Percentage</td></tr>';
			foreach ($value as $k => $v) {
				$shtml .= '<tr><td>' . $k . '</td><td>' . $v['number'] . '</td><td>' . $v['pct'] . '</td></tr>';
			}

		}

		$shtml .= '</tbody></table>';

		$templ = str_replace('{SUMMARY}', $shtml, $templ);

		$this -> makePDF($templ);
	}

	public function getStudentInventory($studentId, $termId = NULL) {

		$student = $this -> CI -> doctrine -> em -> find('Entities\Student', $studentId);

		if ($student == NULL) {
			return '<div>Student Does exist</div>';
		}

		$templ = $this -> getTemplate('student-inventory');

		$templ = str_replace('{STUDENT}', strtoupper($student -> getSurname()) . ' ' . strtoupper($student -> getFirstName()), $templ);
		$templ = str_replace('{STUDENT_NUMBER}', $student -> getStudentNumber(), $templ);

		$class = 'N/A';

		if ($student -> getClassInstance()) {
			$class = $student -> getClassInstance() -> getName();
		}

		$this -> CI -> load -> library('Accountsservice');

		$acSvc = new Accountsservice();

		$currentTerm = Utilities::getCurrentTerm();

		$inv = $acSvc -> getStudentItemInventory($studentId, $termId);

		$table = '<table class="table table-striped table-bordered table-condensed zeb class-list"><thead>' . '<th>#</th>
		<th>Item</th><th>No. Brought</th><th>No. Bought</th><th>Cost Charged</th>
		<th>Paid?</th></thead></tbody>';

		$i = 1;
		$total_paid = 0;
		$total_owing = 0;

		foreach ($inv as $v) {

			$charged = $v['total_charge'];
			$was_paid = $v['was_paid'];

			if ($was_paid == 1) {
				$was_paid = 'YES';
				$total_paid += floatval($charged);
			} else if (floatval($charged) > 0) {
				$was_paid = 'NO';
				$total_owing += floatval($charged);
			}

			$table .= '<tr><td>' . $i . '</td><td>' . $v['item_name'] . '</td><td>' . $v['number_brought_by_student'] . '</td><td>' . $v['number_bought_by_school'] . '</td><td class="tright">' . number_format($charged) . '</td><td>' . $was_paid . '</td></tr>';
			$i++;

		}

		$table .= '</tbody>';
		$table .= '<tfooter>';
		$table .= '<tr><th colspan="4" class="tright">TOTAL PAID:</th>';
		$table .= '<th colspan="2" class="tleft">' . number_format($total_paid) . '</th></tr>';
		$table .= '<tr><th colspan="4" class="tright">TOTAL OWED:</th>';
		$table .= '<th colspan="2" class="tleft">' . number_format($total_owing) . '</th></tr>';
		$table .= '</tfooter>';
		$table .= '</table>';

		$templ = str_replace('{INVENTORY}', $table, $templ);

		$templ = str_replace('{CLASS_NAME}', $class, $templ);

		$templ = str_replace('{SCHOOL_NAME}', GetAppVariable('school_name'), $templ);
		$templ = str_replace('{SCHOOL_ADDRESS}', GetAppVariable('school_address'), $templ);
		$templ = str_replace('{CURRENT_USER}', strtoupper($this -> CI -> session -> userdata('surname')) . ' ' . $this -> CI -> session -> userdata('first_name'), $templ);

		$templ = str_replace('{TODAY}', date('D j, M Y, g:i A'), $templ);

		$this -> makePDF($templ);
	}

	public function getClassFeesStatus($classiId, $freqName) {

		$this -> CI -> load -> library('Feesreportservice');
		$frSvc = new Feesreportservice();

		$details = $frSvc -> getClassFeesProjection($classiId, $freqName);

		if (!is_null($details) && is_array($details) && count($details) > 0) {

			$this -> CI -> load -> library('Accountsservice');
			$actSvc = new Accountsservice();

			$class = $this -> CI -> doctrine -> em -> find('Entities\ClassInstance', $classiId);

			$this -> CI -> load -> library('excel');

			$objExcel = new PHPExcel();

			$objExcel -> setActiveSheetIndex(0);

			$klass = $class -> getName();
			$klass = str_replace(array(']', '['), '', $klass);
			$klass = str_replace('PRIMARY', 'P.', $klass);

			$objExcel -> getActiveSheet() -> setTitle($klass);

			$objExcel -> getActiveSheet() -> setCellValue('A1', 'Surname');
			$objExcel -> getActiveSheet() -> setCellValue('B1', 'First Name');
			$objExcel -> getActiveSheet() -> setCellValue('C1', 'St. No.');
			$objExcel -> getActiveSheet() -> setCellValue('D1', 'Fees Profile');
			$objExcel -> getActiveSheet() -> setCellValue('E1', 'Compulsary Fees Paid');
			$objExcel -> getActiveSheet() -> setCellValue('F1', 'Compulsary Balance Left');
			$objExcel -> getActiveSheet() -> setCellValue('G1', 'Non-Compulsary Paid');
			$objExcel -> getActiveSheet() -> setCellValue('H1', 'Other Fees Owed');

			$styleArray = array('font' => array('bold' => true, ));

			$objExcel -> getActiveSheet() -> getStyle('A1:F1') -> applyFromArray($styleArray);

			$row = 2;

			$total_expected = 0;
			$total_paid = 0;
			$total_balance = 0;

			foreach ($details as $st) {

				$bal = 0;
				$compulsary_fees = $st['compulsary_amount_owed'];
				$compulsary_paid = floatval($st['compulsary_amount_paid']);

				if ($compulsary_fees == NULL || intval($compulsary_fees) < 1) {
					$compulsary_fees = $actSvc -> getCompulsaryTotalClassFees($st['class_id'], $st['fees_profile_id'], $freqName);
				}

				$total_expected += floatval($compulsary_fees);

				$bal = floatval($compulsary_fees) - $compulsary_paid;

				$total_balance += $bal;

				$total_paid += $compulsary_paid;

				$objExcel -> getActiveSheet() -> setCellValueByColumnAndRow(0, $row, $st['surname']);
				$objExcel -> getActiveSheet() -> setCellValueByColumnAndRow(1, $row, $st['first_name']);
				$objExcel -> getActiveSheet() -> setCellValueByColumnAndRow(2, $row, $st['student_number']);
				$objExcel -> getActiveSheet() -> setCellValueByColumnAndRow(3, $row, $st['fees_profile']);
				$objExcel -> getActiveSheet() -> setCellValueByColumnAndRow(4, $row, $compulsary_paid);
				$objExcel -> getActiveSheet() -> setCellValueByColumnAndRow(5, $row, $bal);
				$objExcel -> getActiveSheet() -> setCellValueByColumnAndRow(6, $row, $st['other_amount_paid']);
				$objExcel -> getActiveSheet() -> setCellValueByColumnAndRow(7, $row, $st['other_amount_owed']);

				$row++;
			}

			$row++;
			$objExcel -> getActiveSheet() -> setCellValueByColumnAndRow(1, $row, 'COMPULSARY FEES STATUS');
			$row++;
			$objExcel -> getActiveSheet() -> setCellValueByColumnAndRow(0, $row, 'Total Exepected');
			$objExcel -> getActiveSheet() -> setCellValueByColumnAndRow(1, $row, 'Total Paid');
			$objExcel -> getActiveSheet() -> setCellValueByColumnAndRow(2, $row, 'Balance');
			$row++;
			$objExcel -> getActiveSheet() -> setCellValueByColumnAndRow(0, $row, $total_expected);
			$objExcel -> getActiveSheet() -> setCellValueByColumnAndRow(1, $row, $total_paid);
			$objExcel -> getActiveSheet() -> setCellValueByColumnAndRow(2, $row, $total_balance);

			$objExcel -> getActiveSheet() -> getColumnDimension('A') -> setAutoSize(true);
			$objExcel -> getActiveSheet() -> getColumnDimension('B') -> setAutoSize(true);
			$objExcel -> getActiveSheet() -> getColumnDimension('C') -> setAutoSize(true);
			$objExcel -> getActiveSheet() -> getColumnDimension('D') -> setAutoSize(true);
			$objExcel -> getActiveSheet() -> getColumnDimension('E') -> setAutoSize(true);
			$objExcel -> getActiveSheet() -> getColumnDimension('F') -> setAutoSize(true);

			$filename = uniqid('class_fee__', TRUE) . '.xlsx';
			header('Content-Type: application/vnd.ms-excel');
			//mime type
			header('Content-Disposition: attachment;filename="' . $filename . '"');
			//tell browser what's the file name
			header('Cache-Control: max-age=0');
			//no cache

			$objWriter = PHPExcel_IOFactory::createWriter($objExcel, 'Excel2007');
			$objWriter -> save('php://output');
			//force download

		}
	}

	//private functions
	private function makePDF($html, $filename = NULL) {

		$this -> CI -> load -> helper('file');
		$this -> CI -> load -> library('Wkhtmltopdf');

		$h2 = new Wkhtmltopdf( array('tmp' => $this -> CI -> config -> item('wkhtmltopdf_tmp'), 'bin' => $this -> CI -> config -> item('wkhtmltopdf_path'), 'load-error-handling' => 'ignore'));

		if (is_null($filename)) {
			$filename = uniqid() . '--' . date('DjMY-gis');
		}

		$filename .= '.html';
		$dir = $this -> CI -> config -> item('reports_tmp');

		$filename = 'report_file' . $filename;

		$fp = fopen('./' . $dir . '/' . $filename, 'w');
		fwrite($fp, $html);
		fclose($fp);

		$h2 -> addPage(base_url() . $dir . '/' . $filename);

		$h2 -> send();
	}

	private function getTemplate($name) {

		$string = read_file('./templates/' . $name . '.html');
		$string = str_replace('{LOGO}', base_url() . 'templates/imgs/logo.jpg', $string);
		$string = str_replace('{STYLE}', base_url() . 'assets/css/reporting.css', $string);
		return $string;
	}

}
?>
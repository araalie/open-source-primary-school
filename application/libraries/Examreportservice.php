<?
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Examreportservice {

	private $CI;

	private $style_header;

	public function __construct() {
		$this -> CI = &get_instance();

		$this -> style_header = array('font' => array('bold' => true));
	}

	public function getClassExamReport($class_instance_id, $exam_instance_id) {

		$this -> CI -> load -> library('Exammanager');
		$exMan = new Exammanager();

		$results = $exMan -> getClassResults($class_instance_id, $exam_instance_id);

		$this -> CI -> load -> library('excel');

		$objExcel = new PHPExcel();

		$objExcel -> setActiveSheetIndex(0);

		if ($results['success'] == TRUE) {
			$subjects = $results['subject_list'];
			$info = $results['info'];

			$exam = $exMan -> getExamById($exam_instance_id);

			$this -> CI -> load -> library('Academicservice');
			$acSvc = new Academicservice();
			$cls = $acSvc -> getClassInstance($class_instance_id);

			$objExcel -> getActiveSheet() -> setTitle('Exam Results');

			$objExcel -> getActiveSheet() -> setCellValue('A1', 'EXAM:');
			$objExcel -> getActiveSheet() -> setCellValue('B1', $exam -> getName());
			$objExcel -> getActiveSheet() -> mergeCells('B1:K1');

			$objExcel -> getActiveSheet() -> setCellValue('A2', 'CLASS:');
			$objExcel -> getActiveSheet() -> setCellValue('B2', $cls -> getName());
			$objExcel -> getActiveSheet() -> mergeCells('B2:H2');

			//write titles
			$objExcel -> getActiveSheet() -> setCellValue('A4', 'SURNAME');
			$objExcel -> getActiveSheet() -> setCellValue('B4', 'FIRST NAME');
			$objExcel -> getActiveSheet() -> setCellValue('C4', 'St. No.');

			$row = 4;
			$starting_pos = ord('D');
			$last_column = $starting_pos;

			for ($h = 0; $h < count($subjects); $h++) {

				if ($info['grading_mode'] == 'AGGREGATE_GRADING') {

					$k = $starting_pos + 2 * $h;
					$objExcel -> getActiveSheet() -> setCellValue(chr($k) . $row, $subjects[$h]['subject_name']);
					$objExcel -> getActiveSheet() -> mergeCells(chr($k) . $row . ':' . chr($k + 1) . $row);

					$objExcel -> getActiveSheet() -> setCellValue(chr($k) . ($row + 1), '%');
					$objExcel -> getActiveSheet() -> setCellValue(chr($k + 1) . ($row + 1), 'GRADE');
					$last_column += 2;

				} else {
					$objExcel -> getActiveSheet() -> setCellValue(chr($starting_pos + $h) . $row, $subjects[$h]['subject_name']);
					$objExcel -> getActiveSheet() -> setCellValue(chr($starting_pos + $h) . ($row + 1), '%');
					$last_column++;
				}
			}

			$objExcel -> getActiveSheet() -> setCellValue(chr($last_column++) . ($row + 1), 'Total Marks');

			if ($info['grading_mode'] == 'AGGREGATE_GRADING') {
				$objExcel -> getActiveSheet() -> setCellValue(chr($last_column++) . ($row + 1), 'Aggregates');
				$objExcel -> getActiveSheet() -> setCellValue(chr($last_column++) . ($row + 1), 'Division');
			}

			$objExcel -> getActiveSheet() -> setCellValue(chr($last_column++) . ($row + 1), 'Position');

			$row++;
			$objExcel -> getActiveSheet() -> getStyle('A1:' . chr($last_column) . $row) -> applyFromArray($this -> style_header);

			$student_results = $results['results'];

			for ($w = 0; $w < count($student_results); $w++) {
				$row++;
				$objExcel -> getActiveSheet() -> setCellValue('A' . ($row), $student_results[$w]['surname']);
				$objExcel -> getActiveSheet() -> setCellValue('B' . ($row), $student_results[$w]['first_name']);
				$objExcel -> getActiveSheet() -> setCellValue('C' . ($row), $student_results[$w]['student_number']);

				$summary_start_position = 0;

				for ($h = 0; $h < count($subjects); $h++) {

					$mKey = 'marks_' . $subjects[$h]['subject_instance'];
					$gKey = 'grade_' . $subjects[$h]['subject_instance'];

					if ($info['grading_mode'] == 'AGGREGATE_GRADING') {
						$k = $starting_pos + 2 * $h;
						$objExcel -> getActiveSheet() -> setCellValue(chr($k) . $row, $student_results[$w][$mKey]);
						$objExcel -> getActiveSheet() -> setCellValue(chr($k + 1) . $row, $student_results[$w][$gKey]);
						$summary_start_position = $k + 1;
					} else {
						$objExcel -> getActiveSheet() -> setCellValue(chr($starting_pos + $h) . $row, $student_results[$w][$mKey]);
						$summary_start_position = $starting_pos + $h + 1;
					}
				}

				if ($info['grading_mode'] == 'AGGREGATE_GRADING') {
					$summary_start_position++;
				}

				$objExcel -> getActiveSheet() -> setCellValue(chr($summary_start_position++) . $row, $student_results[$w]['total_marks']);

				if ($info['grading_mode'] == 'AGGREGATE_GRADING') {
					$objExcel -> getActiveSheet() -> setCellValue(chr($summary_start_position++) . $row, $student_results[$w]['total_aggregates']);
					$objExcel -> getActiveSheet() -> setCellValue(chr($summary_start_position++) . $row, $student_results[$w]['division']);
				}

				$objExcel -> getActiveSheet() -> setCellValue(chr($summary_start_position++) . $row, $student_results[$w]['position']);

			}

			//auto size

			for ($q = ord('A'); $q <= $summary_start_position; $q++) {
				$objExcel -> getActiveSheet() -> getColumnDimension(chr($q)) -> setAutoSize(true);
			}

		} else {
			$objExcel -> getActiveSheet() -> setTitle('No marks');
		}

		$filename = uniqid('class_report__', TRUE) . '.xlsx';
		header('Content-Type: application/vnd.ms-excel');
		//mime type
		header('Content-Disposition: attachment;filename="' . $filename . '"');
		//tell browser what's the file name
		header('Cache-Control: max-age=0');
		//no cache

		$objWriter = PHPExcel_IOFactory::createWriter($objExcel, 'Excel2007');
		$objWriter -> save('php://output');
	}

}

/*
 * $default_border = array(
 'style' => PHPExcel_Style_Border::BORDER_THIN,
 'color' => array('rgb'=>'1006A3')
 );
 * $style_header = array(
 'borders' => array(
 'bottom' => $default_border,
 'left' => $default_border,
 'top' => $default_border,
 'right' => $default_border,
 ),
 'fill' => array(
 'type' => PHPExcel_Style_Fill::FILL_SOLID,
 'color' => array('rgb'=>'E1E0F7'),
 ),
 'font' => array(
 'bold' => true,
 )
 );
 * */
?>
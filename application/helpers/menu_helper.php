<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

if (!class_exists('AccessManager')) {

	class AccessManager {

		private static $initialized = false;

		private static $menu = NULL;

		private static function initialize() {
			if (self::$initialized)
				return;

			self::$initialized = true;

			$uaccount = array('section' => 'MY ACCOUNT', 'order' => 0, 'groups' => NULL, 'kids' => array( array('url' => 'account/my_profile', 'text' => 'My Profile', 'attrs' => 'title="My System Account Details, Password"')));

			$manageSch = array('section' => 'Manage School', 'order' => 0, 'groups' => array('SYSTEM ADMINISTRATOR', 'DIRECTOR'), 'kids' => array( array('url' => 'student_houses/', 'text' => 'Houses', 'attrs' => 'title="Manage Houses for students"'), array('url' => 'student_status_management/', 'text' => 'Student Status', 'attrs' => 'title="Manage Status of students such as expeled, quit, etc"')));

			self::$menu = array($uaccount, $manageSch);

			$scholarships = array('section' => 'BURSARIES', 'order' => 0, 'groups' => array('DIRECTOR'), 'kids' => array( array('url' => 'bursaries/', 'text' => 'Bursaries', 'attrs' => 'title="Bursaries Management"'), array('url' => 'bursaries/term_awards', 'text' => 'Term Awards', 'attrs' => 'title="Bursary Awards"')));
			self::$menu[] = $scholarships;

			$marksEntry = array('section' => 'Marks Entry', 'order' => 0, 'groups' => array('SECRETARY'), 'kids' => array( array('url' => 'marks_editing/', 'text' => 'Marks Entry', 'attrs' => 'title="Manage Editing of student marks"')));
			self::$menu[] = $marksEntry;

			$mainFees = array('section' => 'A\c : Fees', 'order' => 0, 'groups' => array('BURSAR'), 'kids' => array( array('url' => 'student_payments/', 'text' => 'Student Payments', 'attrs' => 'title="Manage Student Fees Payments"'), array('url' => 'student_fees_profile_management/', 'text' => 'Student Fees Profiles', 'attrs' => 'title="Manage Boarding/Day Scholar Profiles for students"')));
			self::$menu[] = $mainFees;

			$inventoryMenu = array('section' => 'A\c : Inventory', 'order' => 0, 'groups' => array('BURSAR', 'DIRECTOR'), 'kids' => array( array('url' => 'student_requirements_management/', 'text' => 'Requirements Costs', 'attrs' => 'title="Set Requirements Costs for this term"'), array('url' => 'required_items/', 'text' => 'Requirements', 'attrs' => 'title="Manage Student Items"')));
			self::$menu[] = $inventoryMenu;

			$staffs = array('section' => 'Staff Management', 'order' => 0, 'groups' => array('DIRECTOR'), 'kids' => array( array('url' => 'staff_management/', 'text' => 'Staff', 'attrs' => 'title="Manage Staff [Create, Update or Delete]"'), array('url' => 'staff_job_titles/', 'text' => 'Job Titles', 'attrs' => 'title="Manage Job Titles for Staff"')));

			self::$menu[] = $staffs;

			$academic = array('section' => 'Academic Structure', 'order' => 0, 'groups' => array('DIRECTOR', 'DIRECTORY OF STUDIES'), 'kids' => array( array('url' => 'academic_years/', 'text' => 'Years', 'attrs' => 'title="Create School Years"'), array('url' => 'academic_terms/', 'text' => 'School Terms', 'attrs' => 'title="Manage Creation of New School Terms"'), 
			array('url' => 'class_creation/', 'text' => 'Class Activation', 'attrs' => 'title="Manage Classes for the term"')
			,array('url' => 'subjects/', 'text' => 'Subjects', 'attrs' => 'title="Manage Subjects/Course Units"'), array('url' => 'classes_subject_assignment/', 'text' => 'Subject Assignment', 'attrs' => 'title="Manage assignment of subjects to classes"')));

			self::$menu[] = $academic;

			$enrol = array('section' => 'Enrollment', 'order' => 0, 'groups' => array('DIRECTOR', 'SECRETARY'), 'kids' => array( array('url' => 'enrollment/', 'text' => 'Registration', 'attrs' => 'title="Register Students Add/Modify"'), array('url' => 'student_class_assignment/', 'text' => 'Class Assignment', 'attrs' => 'title="Assign Students to Classes"')));

			self::$menu[] = $enrol;

			$exams = array('section' => 'Exam Management', 'order' => 0, 'groups' => array('DIRECTORY OF STUDIES', 'DIRECTOR'), 'kids' => array( array('url' => 'exam_results/', 'text' => 'Results', 'attrs' => 'title="Manage Results of Exams"'), array('url' => 'exam_management/', 'text' => 'Manage Exams', 'attrs' => 'title="Manage All Exams"'), array('url' => 'grading/', 'text' => 'Grading Schemes', 'attrs' => 'title="Manage Grading Schemes"')));

			self::$menu[] = $exams;

			$fees = array('section' => 'Fees : management', 'order' => 0, 'groups' => array('SYSTEM ADMINISTRATOR'), 'kids' => array( array('url' => 'fees_management/', 'text' => 'Set Term Fees', 'attrs' => 'title="Modify Fees"'), array('url' => 'fees_components/', 'text' => 'Fees Components', 'attrs' => 'title="Modify Fees Types"')));
			self::$menu[] = $fees;

			$genReports = array('section' => 'Reports : General', 'order' => 0, 'groups' => NULL, 'kids' => array( array('url' => 'reports_classes/', 'text' => 'Classes', 'attrs' => 'title="Current Students per class"'), array('url' => 'reports_houses/', 'text' => 'Houses', 'attrs' => 'title="Current Students per house"')));

			self::$menu[] = $genReports;

			$reportsFees = array('section' => 'Reports : Class Fees', 'order' => 0, 'groups' => array('BURSAR', 'DIRECTOR'), 'kids' => array( array('url' => 'reports_class_level_fees/', 'text' => 'Reports+Projections', 'attrs' => 'title="Fees Reports+Projections"'), array('url' => '#', 'text' => 'Inventory', 'attrs' => 'title="Class Inventory Status"')));

			self::$menu[] = $reportsFees;

			$search = array('section' => 'Search', 'order' => 0, 'groups' => array('SECRETARY', 'BURSAR', 'DIRECTOR', 'HEAD TEACHER', 'REGISTRAR'), 'kids' => array( array('url' => 'search_students/', 'text' => 'Find Students', 'attrs' => 'title="Search for students"')));
			self::$menu[] = $search;

			$sys_admin = array('section' => 'System Management', 'order' => 0, 'groups' => array('SYSTEM ADMINISTRATOR', 'DIRECTOR'), 'kids' => array( array('url' => 'user_accounts/', 'text' => 'User Accounts', 'attrs' => 'title="User Accounts"')));

			self::$menu[] = $sys_admin;
		}

		public static function getMenu($userGroups = NULL) {
			self::initialize();

			$html = '';

			$CI = &get_instance();
			$ugs = $CI -> session -> userdata('group_list');

			foreach (self::$menu as $k) {

				$include = FALSE;

				if (is_null($k['groups'])) {
					$include = TRUE;
				} else if (!is_null($k['groups']) && count($k['groups']) > 0) {

					$inter = array_intersect($ugs, $k['groups']);
					if (count($inter) > 0)
						$include = TRUE;
				}

				if ($include) {
					$html .= '<h3 class="nav-header" id="' . str_replace(array(' ', ':', '\\'), '-', strtolower($k['section'])) . '">' . $k['section'] . '</h3>';
					$html .= '<div><ul>';
					foreach ($k['kids'] as $c) {
						$html .= '<li>' . anchor($c['url'], $c['text'], $c['attrs']) . '</li>';
					}

					$html .= '</div></ul>';
				}
			}

			return $html;
		}

		public static function only($userGroups) {
			self::initialize();

			$myAcccess = array();

			$CI = &get_instance();
			$ugs = $CI -> session -> userdata('group_list');

			if (is_string($userGroups)) {
				$myAcccess[] = $userGroups;
			} else if (is_array($userGroups)) {
				$myAcccess = $userGroups;
			}

			$inter = array_intersect($ugs, $myAcccess);

			if (count($inter) > 0) {

			} else {
				$CI -> session -> set_userdata('access-denied', TRUE);
				redirect('access_denied/');
			}

		}

	}

}
?>
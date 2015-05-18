<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Student_houses extends GeneralAuth_Controller {

	public function __construct() {
		parent::__construct();
		AccessManager::only(array('DIRECTOR', 'REGISTROR', 'HEAD TEACHER'));
	}

	public function index() {

		$this -> load -> library('Housemanager');

		$HouseSvc = new Housemanager();

		$data['house_list'] = $HouseSvc -> getHouseStats();

		$data['house_id'] = '';
		$data['house_name'] = '';
		$data['house_description'] = '';
		$data['page'] = 'meta/student-houses';

		$this -> load -> view('layout/default', $data);
	}

	public function edit($id = NULL) {
		$this -> load -> library('Housemanager');

		$data['house_id'] = '';
		$data['house_name'] = '';
		$data['house_description'] = '';

		$HouseSvc = new Housemanager();

		if ($this -> input -> post('house_name')) {
			$House = $HouseSvc -> editHouse($this -> input -> post('house_name'), $this -> input -> post('house_description'), $this -> input -> post('house_id'));

			if (!is_null($House)) {
				$data['house_id'] = $House -> getId();
				$data['house_name'] = $House -> getName();
				$data['house_description'] = $House -> getDescription();
			}
		} else {
			$House = $HouseSvc -> getHouse($id);
			if (!is_null($House)) {
				$data['house_id'] = $House -> getId();
				$data['house_name'] = $House -> getName();
				$data['house_description'] = $House -> getDescription();
			}
		}

		$data['house_list'] = $HouseSvc -> getHouseStats();

		$data['page'] = 'meta/student-houses';

		$this -> load -> view('layout/default', $data);
	}

}

/* End of file student_houses.php */
/* Location: ./application/controllers/student_Houses.php */

<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Fees_components extends GeneralAuth_Controller {

	public function __construct() {
		
		parent::__construct();
		
		AccessManager::only('DIRECTOR');
		
	}
	
	public function index() {

		$data['page_title'] = 'School Fees Components Management';

		$this -> load -> library('Accountsservice');

		$acSvc = new Accountsservice();

		$opts = new Optionsmanager();

		$data['component'] = '';
		$data['component_id'] = '';
		$data['description'] = '';
		$data['fees_components'] = $acSvc -> getFeesComponents();
		$data['fees_profile_id'] = '';
		$data['fees_profiles'] = $opts -> getCostingGroupOptions();

		// august 5th 2012
		$data['fees_freq_id'] = '';
		$data['fees_freqs'] = $opts -> getFeesFrequencyOptions();

		$data['page'] = 'accounts/fees-components';

		$this -> load -> view('layout/default', $data);
	}

	public function edit($id = NULL) {

		$data['page_title'] = 'School Fees Components Management';

		$this -> load -> library('Accountsservice');

		$acSvc = new Accountsservice();

		$opts = new Optionsmanager();

		$data['component'] = '';
		$data['component_id'] = '';
		$data['description'] = '';
		$data['feedback'] = '';
		$data['fees_components'] = $acSvc -> getFeesComponents();
		$data['fees_profile_id'] = '';
		$data['fees_profiles'] = $opts -> getCostingGroupOptions();

		// august 5th 2012
		$data['fees_freq_id'] = '';
		$data['fees_freqs'] = $opts -> getFeesFrequencyOptions();

		//POST
		if ($this -> input -> post('component')) {

			$result = $acSvc -> editFeesComponent($this -> input -> post());
			$data['component_id'] = $this -> input -> post('component_id');
			if ($result['success']) {
				$data['feedback'] = $result['msg'];
				$fcomp = $result['cmp'];
				if ($fcomp) {
					$data['component'] = $fcomp -> getName();
					$data['component_id'] = $fcomp -> getId();
					$data['description'] = $fcomp -> getNarrative();

					if ($fcomp -> getFeesProfile()) {
						$data['fees_profile_id'] = $fcomp -> getFeesProfile() -> getId();
					}

					if ($fcomp -> getFeeFrequencyType()) {
						$data['fees_freq_id'] = $fcomp -> getFeeFrequencyType() -> getId();
					}

				}
			} else {
				$data['feedback'] = 'error|Component was not saved|' . $result['msg'];
			}

		}
		//end POST
		else {

			if (is_null($id)) {
				redirect('fees_components/');
			}
			$fcomp = $acSvc -> getFeesComponent($id);
			if (is_null($fcomp)) {
				redirect('fees_components/');
			}
			if ($fcomp) {
				$data['component'] = $fcomp -> getName();
				$data['component_id'] = $fcomp -> getId();
				$data['description'] = $fcomp -> getNarrative();

				if ($fcomp -> getFeesProfile()) {
					$data['fees_profile_id'] = $fcomp -> getFeesProfile() -> getId();
				}

				if ($fcomp -> getFeeFrequencyType()) {
					$data['fees_freq_id'] = $fcomp -> getFeeFrequencyType() -> getId();
				}
			}
		}

		$data['fees_components'] = $acSvc -> getFeesComponents();

		$data['page'] = 'accounts/fees-components';

		$this -> load -> view('layout/default', $data);
	}

}

/* End of file fees_management.php */
/* Location: ./application/controllers/fees_management.php */

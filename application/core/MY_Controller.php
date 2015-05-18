<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller {
	
	protected $the_user;
	
	public function __construct() {
		
		parent::__construct();
		
	}
}

class GeneralAuth_Controller extends MY_Controller {
	
	public function __construct() {
		
		parent::__construct();
		
		$this->load->library('form_validation');
		$this->load->library('security');
		$this->load->library('tank_auth');
		$this->lang->load('tank_auth');
			
		if(!$this->tank_auth->is_logged_in()){			
				redirect('account/login');
			}

	}
}

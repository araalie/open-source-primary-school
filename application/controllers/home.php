<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends GeneralAuth_Controller {

	public function index()
	{
	$this->load->library('Setupmanager');
	
	$opts = new Setupmanager();
	$name = $opts->getSchoolName();
	
	$data['school_name']=$name;
	$data['school_address']=$opts->getSchoolAddress();
	
	$this->load->view('layout/default', $data);
	}
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */
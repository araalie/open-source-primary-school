<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class User_accounts  extends GeneralAuth_Controller {
		
	public function __construct() {
		
		parent::__construct();
		
		AccessManager::only(array('DIRECTOR','SYSTEM ADMINISTRATOR'));
		
	}
	
	public function index() {

		$this -> load -> library('Userservice');		
		$suSvc = new Userservice();
						
		$data['sys_users']=$suSvc->getSystemUsers();
		
		$data['groups']=$suSvc->getGroups();
		$data['groups_flip']=array_flip($data['groups']);
		
		$data['page'] = 'system/user-management';

		$this -> load -> view('layout/default', $data);	
	}


	public function reset_user_password() {

		$this -> load -> library('Userservice');		
		$suSvc = new Userservice();
		
		echo json_encode($suSvc->resetPassword($this->input->post('user_id'),$this->input->post('new_pass')));
	}

	public function edit_membership() {

		$this -> load -> library('Userservice');		
		$suSvc = new Userservice();
		
		echo json_encode($suSvc->editGroupMembership($this->input->post('staff_id'), $this->input->post('user_groups')));
		
	}	
}

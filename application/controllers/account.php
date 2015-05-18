<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Account extends CI_Controller {

	public function __construct() {

		parent::__construct();

		$this -> load -> library('form_validation');
		$this -> load -> library('security');
		$this -> load -> library('tank_auth');
		$this -> lang -> load('tank_auth');

	}

	public function index() {
		$this -> login();
	}

	public function login() {

		if ($this -> tank_auth -> is_logged_in()) {// logged in
			redirect('');

		} else {
			$data['login_by_username'] = ($this -> config -> item('login_by_username', 'tank_auth') AND $this -> config -> item('use_username', 'tank_auth'));
			$data['login_by_email'] = $this -> config -> item('login_by_email', 'tank_auth');

			$this -> form_validation -> set_rules('login', 'Login', 'trim|required|xss_clean');
			$this -> form_validation -> set_rules('password', 'Password', 'trim|required|xss_clean');
			$this -> form_validation -> set_rules('remember', 'Remember me', 'integer');

			$data['errors'] = array();

			if ($this -> form_validation -> run()) {// validation ok

				if ($this -> tank_auth -> login($this -> form_validation -> set_value('login'), $this -> form_validation -> set_value('password'), $this -> form_validation -> set_value('remember'), $data['login_by_username'], $data['login_by_email'])) {
					// success

					$this -> load -> library('Userservice');
					$uSvc = new Userservice();
					$uSvc -> setupUserSessionAux($this -> input -> post('login'));
					WriteLog(LogAction::Login, 'system-user', $this -> session -> userdata('user_id'), 'logging on');

					redirect('');

				} else {
					$errors = $this -> tank_auth -> get_error_message();

					$msg = '';

					foreach ($errors as $k => $v)
						$msg .= $k . ' : ' . $this -> lang -> line($v);

					if (isset($errors['banned'])) {// banned user
						$this -> _show_message($this -> lang -> line('auth_message_banned') . ' ' . $errors['banned']);

					} else {// fail
						foreach ($errors as $k => $v)
							$data['errors'][$k] = $this -> lang -> line($v);
					}
				}
			}

			$this -> load -> view('layout/login', $data);
		}
	}

	function logout() {

		if (!$this -> tank_auth -> is_logged_in()) {// NOT logged in
			redirect('');
		}

		WriteLog(LogAction::Logout, 'system-user', NULL, 'logout');
		$this -> tank_auth -> logout();
		$this -> login();
	}

	function my_profile() {

		if (!$this -> tank_auth -> is_logged_in()) {// NOT logged in
			redirect('');
		}

					$this -> load -> library('Userservice');
					$uSvc = new Userservice();
					$latest = $uSvc->getCurrentUserLatestActivity();
		$data['page_title'] = 'My Profile';
		$data['page'] = 'auth/my_profile';
		$data['latest']= $latest;
		
		$this -> load -> view('layout/default', $data);
	}

	function change_password() {
		if (!$this -> tank_auth -> is_logged_in()) {// NOT logged in
			redirect('');
		}
		
		$success =$this -> tank_auth -> change_password($this->input->post('old_passwd'),$this->input->post('new_passwd'));
		
		if($success){
			WriteLog(LogAction::UserAccountActivity, 'system-user', NULL, 'changed password');
		}else{
			WriteLog(LogAction::UserAccountActivity, 'system-user', NULL, 'failed to change password');
		}
		
		$errors='';
		foreach ($this->tank_auth->get_error_message() as $key => $value) {
		$errors.=$key.' : '.$value.'<br/>';	
		}
		
		echo json_encode(array('success'=>$success, 'msg'=>$errors));
	}

}

/* End of file account.php */
/* Location: ./application/controllers/account.php */

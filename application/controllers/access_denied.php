<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Access_denied extends GeneralAuth_Controller {
	
	public function index()
	{
		$denied = $this->session->userdata('access-denied');
		
		if($denied){
			
			$this->session->unset_userdata('access-denied');
			
			$data['page_title']='Access Denied';
			$data['page']='auth/access-denied';
			
			$this->load->view('layout/default', $data);	
		}else{
			if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			 		echo json_encode(array('success'=>FALSE, 'msg'=>'Access Denied'));
				return;
				}
			redirect('home/');
		}
		
	}
	
}

/* End of file access_denied.php */
/* Location: ./application/controllers/access_denied.php */
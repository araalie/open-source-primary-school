<?php

class Setupmanager{


	function __construct() {
		$this->CI = & get_instance();
	}
	
	public function getSchoolName(){

	$schName = $this->CI->doctrine->em->getRepository('Entities\AppOption')->findOneBy(array('key_name' => 'school_name'));
	
	if(is_null($schName)){
		return 'No School Name';
	}
	
	return $schName->getValue();
	
	}
	
	public function getSchoolAddress(){

	$schName = $this->CI->doctrine->em->getRepository('Entities\AppOption')->findOneBy(array('key_name' => 'school_address'));
	
	if(is_null($schName)){
		return 'No Address';
	}
	
	return $schName->getValue();
	
	}


	public function getVariable($key){

	$var = $this->CI->doctrine->em->getRepository('Entities\AppOption')->findOneBy(array('key_name' => $key));
	
	if(is_null($var)){
		return '';
	}
	
	return $var->getValue();
	
	}}

?>
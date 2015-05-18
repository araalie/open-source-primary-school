<?php

class Jobtitlemanager{
	
	
	function __construct() {
		$this->CI = & get_instance();
	}
	
	
	public function getAllJobTitles($validOnly=FALSE){
		
		$records = NULL;
		
		if($validOnly){
			$records = $this->CI->doctrine->em->getRepository("Entities\JobTitle")->findByIsValid(1);		
			}	else{
			$records = $this->CI->doctrine->em->getRepository("Entities\JobTitle")->findAll();			
		}
		
		
		return $records;
	}
	
	public function editJobTitle($name, $description, $id=NULL){
		
		$ttl = new Entities\JobTitle;
		
		if(is_null($id) || $id==''){
			$ttl->setTitle($name);
			$ttl->setDescription($description);
			$ttl->setDateCreated(new DateTime());
			$ttl->setDateLastModified(new DateTime());
			$ttl->setIsValid(1);	
		}
		else{
			$ttl = $this->CI->doctrine->em->find('Entities\JobTitle', $id);
			
			if($ttl==NULL){
				return NULL;
			}
			
			$ttl->setTitle($name);
			$ttl->setDescription($description);
			$ttl->setDateLastModified(new DateTime());
		}
		
		$this->CI->doctrine->em->persist($ttl);
		
		
		try {
			$this->CI->doctrine->em->flush();
		}
		catch( \PDOException $e )
		{
			if( $e->getCode() === '23000' )
			{
				return NULL;				
			}
		}
		
		return $ttl;
	}
	
	public function getJobTitle($id){		
		return $this->CI->doctrine->em->find('Entities\JobTitle', $id);		
	}	
}


?>
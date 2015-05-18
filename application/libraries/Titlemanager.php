<?php

class Titlemanager{
	
	
	function __construct() {
		$this->CI = & get_instance();
	}
	
	
	public function getAllTitles($validOnly=FALSE){
		
		$records = NULL;
		
		if($validOnly){
			$records = $this->CI->doctrine->em->getRepository("Entities\Title")->findByIsValid(1);		
			}	else{
			$records = $this->CI->doctrine->em->getRepository("Entities\Title")->findAll();			
		}
		
		
		return $records;
	}
	
	public function editTitle($name, $description, $id=NULL){
		
		$ttl = new Entities\Title;
		
		if(is_null($id) || $id==''){
			$ttl->setName($name);
			$ttl->setDescription($description);
			$ttl->setDateCreated(new DateTime());
			$ttl->setDateLastModified(new DateTime());
			$ttl->setIsValid(1);	
		}
		else{
			$ttl = $this->CI->doctrine->em->find('Entities\Title', $id);
			
			if($ttl==NULL){
				return NULL;
			}
			
			$ttl->setName($name);
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
	
	public function getTitle($id){		
		return $this->CI->doctrine->em->find('Entities\Title', $id);		
	}	
}


?>
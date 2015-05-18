<?php

class Housemanager{
	
	
	function __construct() {
		$this->CI = & get_instance();
	}
	
	
	public function getAllHouses($validOnly=FALSE){
		
		$records = NULL;
		
		if($validOnly){
			$records = $this->CI->doctrine->em->getRepository("Entities\House")->findByIsValid(1);		
			}	else{
			$records = $this->CI->doctrine->em->getRepository("Entities\House")->findAll();			
		}
		
		return $records;
	}
	
	
	public function getHouseStats() {

		$sql = "select h.id, h.name, h.description, 
				(SELECT COUNT(*) FROM student s WHERE s.is_valid=1 AND s.house_id=h.id AND s.student_status_id= 
				(SELECT ss.id FROM student_status ss WHERE ss.name='CURRENTLY_ENROLLED' ) )
				 AS `students_in_house` from house h
				WHERE h.is_valid=1	";

		$rsm = new \Doctrine\ORM\Query\ResultSetMapping;
		$rsm -> addScalarResult('id', 'id');
		$rsm -> addScalarResult('name', 'name');
		$rsm -> addScalarResult('description', 'description');
		$rsm -> addScalarResult('students_in_house', 'students_in_house');

		$query = $this -> CI -> doctrine -> em -> createNativeQuery($sql, $rsm);
		$result = $query -> getArrayResult();

		return $result;
	}
		
	public function editHouse($name, $description, $id=NULL){
		
		$ttl = new Entities\House;
		
		if(is_null($id) || $id==''){
			$ttl->setName(strtoupper($name));
			$ttl->setDescription($description);
			$ttl->setDateCreated(new DateTime());
			$ttl->setDateLastModified(new DateTime());
			$ttl->setIsValid(1);	
		}
		else{
			$ttl = $this->CI->doctrine->em->find('Entities\House', $id);
			
			if($ttl==NULL){
				return NULL;
			}
			
			$ttl->setName(strtoupper($name));
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
	
	public function getHouse($id){		
		return $this->CI->doctrine->em->find('Entities\House', $id);		
	}
	
	public function getNextHouse()
	{
			$sql="SELECT h.id, h.name, count(*) AS 'no_of_students'  FROM student s
				RIGHT JOIN house h on h.id=s.house_id
				GROUP by h.name
				ORDER by 3, h.name
				";
		
		$rsm = new \Doctrine\ORM\Query\ResultSetMapping;
		$rsm->addEntityResult('Entities\House', 'h');
		$rsm->addFieldResult('h', 'id', 'id');
		$rsm->addFieldResult('h', 'name', 'name');
		
		$query = $this->CI->doctrine->em->createNativeQuery($sql, $rsm);
		
		$res = $query->getArrayResult();	
		
		if(is_null($res) || count($res)==0 ){
			$records = $this->CI->doctrine->em->getRepository("Entities\House")->findAll();
			return $records[0]->getId();
		}
		return $res[0]['id'];
	}
	
		
}


?>
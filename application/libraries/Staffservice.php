<?php

class Staffservice{
	
	
	function __construct() {
		$this->CI = & get_instance();
	}
	
	
	public function editStaff($form){
		
		$staff = new Entities\SchoolStaff;
		
		$id = $form['staff_id'];
		
		if(is_null($id) || $id==''){
			$staff->setFirstName(strtoupper($form['first_name']));
			$staff->setSurname(strtoupper($form['surname']));
			$staff->setGender($form['staff_gender']);
			$staff->setOtherNames(strtoupper($form['other_names']));
			
			$staff->setTelephone1($form['telephone1']);
			$staff->setTelephone2($form['telephone2']);
			$staff->setEmail($form['staff_email']);
			
			$staff->setAddress($form['staff_address']);
			$staff->setRemarks($form['staff_remarks']);
			
			$staff->setYearJoined(NULL);
			$staff->setYearLeft(NULL);
			
			if(intval($form['year_start'])>0){
				$staff->setYearJoined($form['year_start']);	
			}
			
			if(intval($form['year_end'])>0){
				$staff->setYearLeft($form['year_end']);
			}
			
			if($form['dob']==''){
				$staff->setDateOfBirth(NULL);	
				}else{
				$dob = new DateTime($form['dob']);
				
				$staff->setDateOfBirth($dob);
				
			}
			
			$staff->setTitle($this->CI->doctrine->em->getReference('Entities\Title',$form['staff_title']));
			
			if($form['staff_job_title']!=''){
				$staff->setJobTitle($this->CI->doctrine->em->getReference('Entities\JobTitle',$form['staff_job_title']));	
			}
			
			
			$staff->setDateCreated(new DateTime());
			$staff->setDateLastModified(new DateTime());
			$staff->setIsValid(1);
			
			$this->CI->doctrine->em->persist($staff);
			
			
			try {
				$this->CI->doctrine->em->flush();
				WriteLog( LogAction::CreateStaff,'staff', $staff->getId(),$staff->getSurname().' added to system');
			}
			catch( \PDOException $e )
			{
				//echo $e;
			}
			
			return $staff;
			
		}
		else{
			$staff = $this->CI->doctrine->em->find('Entities\SchoolStaff', $id);
			
			if($staff==NULL){
				return NULL;
			}
			
			$staff->setFirstName(strtoupper($form['first_name']));
			$staff->setSurname(strtoupper($form['surname']));
			$staff->setGender($form['staff_gender']);
			$staff->setOtherNames(strtoupper($form['other_names']));
			
			$staff->setTelephone1($form['telephone1']);
			$staff->setTelephone2($form['telephone2']);
			$staff->setEmail($form['staff_email']);
			
			$staff->setAddress($form['staff_address']);
			$staff->setRemarks($form['staff_remarks']);
			
			$staff->setYearJoined(NULL);
			$staff->setYearLeft(NULL);
			
			if(intval($form['year_start'])>0){
				$staff->setYearJoined($form['year_start']);	
			}
			
			if(intval($form['year_end'])>0){
				$staff->setYearLeft($form['year_end']);
			}
			
			if($form['dob']==''){
				$staff->setDateOfBirth(NULL);	
				}else{
				$dob = new DateTime($form['dob']);
				
				$staff->setDateOfBirth($dob);
				
			}
			
			$staff->setTitle($this->CI->doctrine->em->getReference('Entities\Title',$form['staff_title']));
			
			if($form['staff_job_title']!=''){
				$staff->setJobTitle($this->CI->doctrine->em->getReference('Entities\JobTitle',$form['staff_job_title']));	
				}		
			
			$staff->setDateLastModified(new DateTime());
			
			$this->CI->doctrine->em->persist($staff);
			
			
			try {
				$this->CI->doctrine->em->flush();
				WriteLog( LogAction::EditStaff,'staff', $staff->getId(),$staff->getSurname().' edited');
			}
			catch( \PDOException $e )
			{
				//echo $e;
			}
			
			return $staff;
		}
		
		return NULL;
		}	
	
	public function getStaff($id){		
		
		$query = $this->CI->doctrine->em->createQuery('SELECT s FROM Entities\SchoolStaff s LEFT JOIN s.title t LEFT JOIN s.job_title j WHERE s.id = ?1');
		$query->setParameter(1, $id);
		$stf = $query->getResult();
		
		if(is_array($stf) && count($stf)>0){
			return $stf[0];
		}
		return NULL;
		//return $this->CI->doctrine->em->find('Entities\SchoolStaff', $id);		
	}
	
	public function getAllStaff($validOnly=FALSE){		
		$records = $this->CI->doctrine->em->getRepository("Entities\SchoolStaff")->findAll();		
		return $records;
	}
	
	
	public function getStaffList($page=1, $size=25){	
		
		
		$query = $this->CI->doctrine->em->
		createQuery('SELECT s FROM Entities\SchoolStaff s LEFT JOIN s.title t LEFT JOIN s.job_title j ORDER BY s.surname')
			->setFirstResult($page-1)
			->setMaxResults($size);
		
		$paginator = new Doctrine\ORM\Tools\Pagination\Paginator($query, $fetchJoin = true);
		
		/*$c = count($paginator);
		foreach ($paginator as $post) {
			echo $post->getSurname() . "\n";
			}*/
		
		return $paginator;
		}	
}


?>
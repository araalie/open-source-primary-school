<?php

class Optionsmanager{
	
	
	function __construct() {
		$this->CI = & get_instance();
	}
	
	
	public function getExamStatusesOptions(){
		
		$tlist = array();
		
		$tlist['']='None';
		
		$records = $this->CI->doctrine->em->getRepository("Entities\ExamStatus")->findBy(array('is_valid'=>1));		
		
		foreach($records as $t){
			$tlist[$t->getId()]= $t->getName();
		}
		
		return $tlist;
	}
	
	
	public function getTitlesOptions(){
		
		$tlist = array();
		
		$tlist['']='Not Available';
		
		$records = $this->CI->doctrine->em->getRepository("Entities\Title")->findBy(array('is_valid'=>1));		
		
		foreach($records as $t){
			$tlist[$t->getId()]= $t->getName();
		}
		
		return $tlist;
		}	
	
	
	public function getStudentStatusOptions($empty=NULL){
		
		$tlist = array();
		
		if(is_null($empty)){
			$tlist['']='Not Available';	
		}else{
			$tlist['']=$empty;
		}
		
		
		$records = $this->CI->doctrine->em->getRepository("Entities\StudentStatus")->findBy(array('is_valid'=>1));		
		
		foreach($records as $t){
			$tlist[$t->getId()]= $t->getName();
		}
		
		return $tlist;
		}	
	
	public function getJobTitlesOptions(){
		
		$tlist = array();
		
		$tlist['']='Not Available';
		
		$records = $this->CI->doctrine->em->getRepository("Entities\JobTitle")->findBy(array('is_valid'=>1));		
		
		foreach($records as $t){
			$tlist[$t->getId()]= $t->getTitle();
		}
		
		return $tlist;
		}	
	
	
	public function getTermStatusOptions(){
		
		$statuses = array();		
		
		$records = $this->CI->doctrine->em->getRepository("Entities\TermStatus")->findBy(array('is_valid'=>1));		
		
		foreach($records as $t){
			$statuses[$t->getId()]= $t->getTitle();
		}
		
		return $statuses;
		}	
	
	
	public function getAcademicYearStatusOptions(){
		
		$statuses = array();		
		
		$records = $this->CI->doctrine->em->getRepository("Entities\AcademicStatus")->findBy(array('is_valid'=>1));		
		
		foreach($records as $t){
			$statuses[$t->getId()]= $t->getTitle();
		}
		
		return $statuses;
	}
	
	public function getHousesOptions(){
		
		$tlist = array();
		
		$tlist['']='Not Available';
		
		$records = $this->CI->doctrine->em->getRepository("Entities\House")->findBy(array('is_valid'=>1));		
		
		foreach($records as $t){
			$tlist[$t->getId()]= $t->getName();
		}
		
		return $tlist;
		}	
	
	
	public function getTermTypeOptions(){
		
		$tlist = array();
		
		$records = $this->CI->doctrine->em->getRepository("Entities\TermType")->findBy(array('is_valid'=>1));		
		
		foreach($records as $t){
			$tlist[$t->getId()]= $t->getName();
		}
		
		return $tlist;
	}
	
	public function getGenderOptions(){
		return array('' => 'Unkown', 'M' => 'Male', 'F' => 'Female');
	}
	
	public function getFunctionalYears($StartAt2K3=FALSE){
		
		$yearNow=date("Y");
		$yrs = array();
		$yrs['']='Not Available';
		
		$lower = 2000;
		
		if($StartAt2K3){
			$lower=2003;	
		}
		
		for($h=$yearNow; $h>=$lower; $h--){
			$yrs[$h]=$h;
		}
		return $yrs;
	}
	
	
	public function getFunctionalYearOptions($emptyTitle=NULL){
		
		$yearNow=date("Y");
		$yrs = array();
		$yrs['']= is_null($emptyTitle)? 'Not Available' : $emptyTitle;
		
		$lower = 2000;
		
		
		for($h=$yearNow; $h>=$lower; $h--){
			$yrs[$h]=$h;
		}
		return $yrs;
	}
	
	public function getValidAcedmicYears(){
		
		$yearNow=date("Y");
		$yrs = array();
		
		$lower = 2003;
		
		for($h=$yearNow+1; $h>=$lower; $h--){
			$yrs[$h]=$h;
		}
		return $yrs;
	}
	
	public function getCreatedAcademicYears(){
		
		$yrs = array();
		
		$records = $this->CI->doctrine->em->getRepository("Entities\AcademicYear")->findBy(array('is_valid'=>1));		
		
		$y=0;
		
		foreach($records as $r){
			$yrs[$r->getId()]= $r->getName();
			$y++;
		}
		
		if($y==0){
			$yrs['']='No Academic Years';
		}
		
		
		return $yrs;
	}
	
	public function getCreatedClassTypes(){
		
		$yrs = array();
		
		$query = $this->CI->doctrine->em->
		createQuery('SELECT t FROM Entities\ClassType t LEFT JOIN t.school_division d');
		
		$records= $query->getResult();	
		
		$y=0;
		
		
		foreach($records as $r){
			
			$yrs[$r->getId()]=$r->getName();//'['.$r->getSchoolDivision()->getName().'] '.$r->getName();
			$y++;
		}
		
		if($y==0){
			$yrs['']='No Academic Years';
		}
		
		
		return $yrs;
	}
	
	
	public function getTermInstances($criteria=NULL){
		
		$this->CI->load->library('Academicservice');		
		$acSvc = new Academicservice();
		
		$terms = $acSvc->getTermsInstances();
		
		if($terms){
			$opts = array();
			foreach($terms as $t){
				$opts[$t->getId()] = $t->getName();
			}
			
			return $opts;	
		}
		
		return array(''=>'No Active/New Terms');
	}
	
	public function getCurrentClassInstances($criteria=NULL,$emptyTitle=NULL){
		
		$this->CI->load->library('Academicservice');		
		$acSvc = new Academicservice();
		
		$classies = $acSvc->getCurrentClassInstances($criteria);
		
		$opts = array();
		
		if(!is_null($emptyTitle)){
			$opts['']= $emptyTitle;	
		}		
		
		if($classies){
			
			foreach($classies as $c){
				$opts[$c->getId()] = $c->getName();
			}
			
			return $opts;	
		}
		
		return array(''=>'No Classes');
		}	
	
	
	public function getSubjects($criteria=NULL){
		
		$this->CI->load->library('Studysubjectservice');		
		$sSvc = new Studysubjectservice();
		
		$classies = $sSvc->getAllSubjects();
		
		if($classies){
			$opts = array();
			foreach($classies as $c){
				$opts[$c->getId()] = $c->getName();
			}
			
			return $opts;	
		}
		
		return array(''=>'No Classes');
		}	
	
	
	public function getSubjectsforDualist($classi_id=NULL){
		
		$result = array();
		
		$result['new']= array();
		$result['old']= array();
		
		$this->CI->load->library('Studysubjectservice');		
		$sSvc = new Studysubjectservice();
		
		$classies = $sSvc->getAllSubjects();
		
		if($classies){
			$opts = array();
			
			if(!is_null($classi_id) && intval($classi_id)>0){
				$currentAttachedSubjects = $sSvc->getClassSubjects($classi_id);
				$att = array();
				foreach($currentAttachedSubjects as $a){
					if($a->getStudySubject() && $a->getIsValid()==1){
						$att[]= $a->getStudySubject()->getId();
					}
				}
				
				foreach($classies as $c){
					
					if(in_array($c->getId(), $att)){
						$result['old'][$c->getId()] = $c->getName();
						}else{
						$result['new'][$c->getId()] = $c->getName();	
					}
					}	
				
				}else{
				foreach($classies as $c){
					$result['new'][$c->getId()] = $c->getName();
					}	
			}
		}
		
		return $result;
		}	
	
	
	public function getCurrentClasses($emptyTitle=NULL){
		
		$queryBuilder = $this->CI->doctrine->em->createQueryBuilder();
		
		$queryBuilder->select('c')
			->from('Entities\ClassInstance', 'c')
			->leftJoin('c.term', 't')
			->leftJoin('t.term_status', 's')
			->leftJoin('c.class_type', 'y')
			->leftJoin('y.school_division', 'v')
			->where('s.name=:status')
			->setParameter('status', 'IN_PROGRESS')
			->orderBy('v.id, y.level','ASC');
		
		
		$query = $queryBuilder->getQuery();
		
		$classes = $query->getResult();
		
		
		$clist=array();
		
		$clist['']= is_null($emptyTitle) ? 'No Class' : $emptyTitle;
		
		foreach($classes as $c){
			$clist[$c->getId()]	= $c->getName();
		}
		
		return $clist;
	}
	
	public function getPreviousTermClasses($termId=NULL){
		
		$pterm =NULL;
		
		$clist=array();
		
		$clist['']= 'No Class';
		
		
		if(is_null($termId)){
			$cterm =Utilities::getCurrentTerm();
			
			$pterm = $cterm->getPreviousTerm();
			
			if($pterm==NULL){
				return $clist;
			}
		}else{
			
			$cterm = $this -> CI -> doctrine -> em -> find('Entities\Term', $termId);
			
			if(!is_null($cterm)){
				$pterm = $cterm->getPreviousTerm();
			}
			
			if($pterm==NULL){
				return $clist;
			}
		}
		
		
		$queryBuilder = $this->CI->doctrine->em->createQueryBuilder();
		
		$queryBuilder->select('c')
			->from('Entities\ClassInstance', 'c')
			->leftJoin('c.term', 't')
			->leftJoin('t.term_status', 's')
			->leftJoin('c.class_type', 'y')
			->leftJoin('y.school_division', 'v')
			->where('t.id=:id')
			->setParameter('id', $pterm->getId())
			->orderBy('v.id, y.level','ASC');
		
		
		$query = $queryBuilder->getQuery();
		
		$classes = $query->getResult();
		
		foreach($classes as $c){
			$clist[$c->getId()]	= $c->getName();
		}
		
		return $clist;
	}
	
	public function getCurrentExamsListing(){
		
		$this->CI->load->library('Exammanager');
		$exMan = new Exammanager();
		$exms = $exMan->getCurrentTermExamsList();
		
		$ea=array();
		
		$ea['']='Select an Exam';
		
		if(!is_null($exms)){
			foreach($exms as $e){
				$ea[$e->getId()] = $e->getName();
				}	
		}
		
		return $ea;
	}


	public function getMyCurrentExamsClasses($exam_id){
		
		$this->CI->load->library('Exammanager');
		$exMan = new Exammanager();
		$exms = $exMan->getMyClasses($exam_id);
		
		$cs=array();
				
		if(!is_null($exms)){
			foreach($exms as $e){
				$cs[$e->getClassInstance()->getId()] = $e->getClassInstance()->getName();
				}	
		}
		
		return $cs;
	}	
	
	public function getMyCurrentExamsSubjects($classi_id){
		
		$this->CI->load->library('Exammanager');
		$exMan = new Exammanager();
		$subjects = $exMan->getMySubjects($classi_id);
		
		$courses=array();
				
		if(!is_null($subjects)){
			foreach($subjects as $s){
				$courses[$s->getId()] = Utilities::getLeanName($s->getName());
				}	
		}
		
		return $courses;
	}		

	
	public function getFeesProfileOptions($emptyTitle = NULL){
		
		$plist = array();
		
		$plist['']= is_null($emptyTitle) ? 'Choose Fees Profile' : $emptyTitle;
		
		$records = $this->CI->doctrine->em->getRepository("Entities\FeesProfile")->findBy(array('is_valid'=>1,'costing_only'=>0));		
		
		foreach($records as $t){
			$plist[$t->getId()]= $t->getName();
		}
		
		return $plist;
	}	

	public function getFeesFrequencyOptions($emptyTitle = NULL){
		
		$plist = array();
		
		$plist['']= is_null($emptyTitle) ? 'Choose Payment Frequency' : $emptyTitle;
		
		$records = $this->CI->doctrine->em->getRepository("Entities\FeeFrequencyType")->findBy(array('is_valid'=>1));		
		
		foreach($records as $t){
			$plist[$t->getId()]= $t->getName();
		}
		
		return $plist;
	}	

	public function getCostingGroupOptions($emptyTitle = NULL){
		
		$plist = array();
		
		$plist['']= is_null($emptyTitle) ? 'Choose Costing Group' : $emptyTitle;
		
		$records = $this->CI->doctrine->em->getRepository("Entities\FeesProfile")->findBy(array('is_valid'=>1));		
		
		foreach($records as $t){
			$plist[$t->getId()]= $t->getName();
		}
		
		return $plist;
	}	
}
?>
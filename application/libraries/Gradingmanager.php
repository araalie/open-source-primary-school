<?php

class Gradingmanager{
	
	
	function __construct() {
		$this->CI = & get_instance();
		$this->grades = array('D1','D2','C3','C4','C5','C6','P7','P8','F9');
	}
	
	
	public function getGrades(){
		
		return $this->grades;
	}
	
	
	public function editGrading($form){
		
		$gz= $this->grades;
		
		if(!(intval($form['g_id'])>0)){
			
			$grad = new Entities\Grading;
			$grad->setName(strtoupper($form['g_name']));
			$grad->setDescription($form['description']);
			$grad->setGradingStatus($this->getGradingStatusByName('NEW'));
			$grad->setDateCreated(new DateTime());
			$grad->setDateLastModified(new DateTime());
			$grad->setIsValid(1);	
			
			$this->CI->doctrine->em->persist($grad);
			
			try {
				$this->CI->doctrine->em->flush();
			}
			catch( \PDOException $e )
			{
				if( $e->getCode() === '23000' )
				{
					WriteLog( LogAction::Grading,'grading-creation', -1, $e->getMessage() );
					return array('success'=>FALSE,'msg'=>$e->getMessage());		
				}
			}
			
			foreach($gz as $r){
				$grange = new Entities\GradingRange;
				$grange->setCode($r);
				$grange->setMinimum($form[$r.'_min']);
				$grange->setMaximum($form[$r.'_max']);
				$grange->setGrading($grad);
				$grange->setDateCreated(new DateTime());
				$grange->setDateLastModified(new DateTime());
				$grange->setIsValid(1);
				$this->CI->doctrine->em->persist($grange);
			}
			
			try {
				$this->CI->doctrine->em->flush();
			}
			catch( \PDOException $e )
			{
				if( $e->getCode() === '23000' )
				{
					WriteLog( LogAction::Grading,'grading-creation', -1, $e->getMessage() );
					return array('success'=>FALSE,'msg'=>$e->getMessage());		
				}
			}
			
			return array('success'=>TRUE,'msg'=>'Grading created', 'id'=>$grange->getId());
			
			}else{ // update
			
		}
	}
	
	
	public function getGradingStatusByName($state){
		$st = $this->CI->doctrine->em->getRepository('Entities\GradingStatus')->findOneBy(array('name' =>$state));
		return $st;
	}
	
	public function editGradesInterface($id=NULL)
	{
		$html='<table class="table table-striped table-bordered table-condensed zeb">'
		.'<caption>GRADE RANGES</caption>'
		.'<thead><tr><th>Grade</th><th>Min</th><th>Max</th></tr></thead><tbody>';
		foreach($this->grades as $g){
			$html.='<tr><td>'.$g.'</td><td>'
			.'<input id="'.$g.'_min" name="'.$g.'_min" class="{required:true,digits:true,min:0,max:100} input-small gmin" />'
			.'</td><td>'
			.'<input id="'.$g.'_max" name="'.$g.'_max" class="{required:true,digits:true,min:0,max:100} input-small gmax" />'
			.'</td></tr>';
		}
		$html.='</tbody></table>';
		return $html;
	}
	
	
	public function getAllGradingSchemes()
	{
		$schemes = $this->CI->doctrine->em->createQuery('SELECT g FROM Entities\Grading g WHERE g.is_valid=1 ORDER BY g.date_created DESC')
				->getResult();
		
		$result = array();
		
		foreach($schemes as $s)
		{
			$tmp = array('id'=>$s->getId(), 'name'=>$s->getName());
			
			$qb = $this->CI->doctrine->em
			->createQuery('SELECT g FROM Entities\GradingRange g WHERE g.grading=?1 ORDER BY g.maximum DESC');
			$qb->setParameter(1, $s->getId());	
			
			$rg = $qb->getResult();
			
			$ts=array();
			
			foreach($rg as $i){
				$t= array('code'=>$i->getCode(),'min'=>$i->getMinimum(),'max'=>$i->getMaximum());
				$ts[]=$t;
			}
			
			$tmp['ranges'] = $ts;
			
			$result[]=$tmp;
		}
		
		return $result;
	}
	
	
	public function setDefaultGradingModes(){
		
		$modes = $this->CI->doctrine->
				 em->createQuery('SELECT t FROM Entities\ClassType t WHERE t.is_valid=1 AND t.default_grading_mode IS NULL')
				 ->getResult();
				
		$byTotals = $this->getGradingModeByName('TOTAL_GRADING');
		$byAggregate = $this->getGradingModeByName('AGGREGATE_GRADING');
		
		foreach($modes as $m){
				
			$update=FALSE;
			
			if($m->getLevel()<=3){
				$m->setDefaultGradingMode($byTotals);
				$update=TRUE;
			}else if($m->getLevel()>3){
				$m->setDefaultGradingMode($byAggregate);
				$update=TRUE;
			}
			
			if($update){
				
				$m -> setDateLastModified(new DateTime());
				$this -> CI -> doctrine -> em -> persist($m);

			try {
				$this -> CI -> doctrine -> em -> flush();
			} catch( \PDOException $e ) {
				
			}
			
			$update=FALSE;
			}	
		}
		
	}
	
	
	public function getGradingModeByName($modeName) {
		
		$mode = $this -> CI -> doctrine -> em -> getRepository('Entities\GradingMode') -> findOneBy(array('name' => $modeName));
		
		return $mode;
	}


}


?>
<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Userservice {

	public function __construct() {
		$this -> CI = &get_instance();
	}

	public function editUser($detail) {

		$this -> CI -> load -> library('tank_auth');

		$authScv = new Tank_auth();

		if (is_null($detail) || !is_array($detail) || !isset($detail['staff_id'])) {
			return array('success' => FALSE, 'msg' => 'Invalid details');
		}

		$staff = $this -> CI -> doctrine -> em -> find('Entities\SchoolStaff', $detail['staff_id']);

		if (is_null($staff)) {
			return array('success' => FALSE, 'msg' => 'Invalid details for staff','id'=>$detail['staff_id']);
		}
		
		$staffId = $detail['staff_id'];

		$groupsEdit = '';

		$userAcc = $staff -> getUser();

		if (is_null($userAcc)) {
			$first_name = $staff -> getFirstName();
			$surname = $staff -> getSurname();
			$email = $staff -> getEmail();

			$username = strtolower($first_name[0] . $surname);

			if (is_null($email) || $email == '') {
				$email = $username . '_' . date('s') . '@tempschool.sch.edu';
			}

			$result = $authScv -> create_user($username, $email, $detail['password'], FALSE);
			// no email activation

			if (is_null($result)) {
				return array('success' => FALSE, 'msg' => $authScv -> get_error_message());
			}

			$userAcc = $this -> CI -> doctrine -> em -> find('Entities\User', $result['user_id']);

			if (!is_null($userAcc)) {

				$staff -> setUser($userAcc);
				$staff -> setDateLastModified(new DateTime());

				$this -> CI -> doctrine -> em -> persist($staff);
				$this -> CI -> doctrine -> em -> flush();

				if (isset($detail['groups'])) {
					$res = $this -> editGroupMembership($staffId, $detail['groups']);

					if ($res['success']) {
						$groupsEdit = 'Groups membership has been updated';
					}
				}

				return array('success' => TRUE, 'msg' => 'User Account created successfully.' . $groupsEdit, 'user_id' => $userAcc -> getId());
			}

			return array('success' => FALSE, 'msg' => 'Could not create account.');

		} else {
			if (isset($detail['groups'])) {
				$res = $this -> editGroupMembership($staffId, $detail['groups']);

				if ($res['success']) {
					$groupsEdit = 'Groups membership has been updated';
				}
			}
			
			return array('success' => TRUE, 'msg' => 'User account exits. '.$groupsEdit);
		}

	}//end of edit user

	public function editGroupMembership($staffId, $groups = array()) {

		if (is_null($groups) || !is_array($groups) || !(count($groups) > 0)) {
			return array('success' => FALSE, 'msg' => 'Invalid or No Group(s) Specified.');
		}

		$query1 = $this -> CI -> doctrine -> em -> createQuery('SELECT u FROM Entities\UserGroupMembership u JOIN u.school_staff h JOIN u.user_group g WHERE h.id=?1 AND g.name NOT IN(?2)');
		$query1 -> setParameter(1, $staffId);
		$query1 -> setParameter(2, $groups);

		$membership1 = $query1 -> getResult();

		$toDelete = FALSE;

		if (!is_null($membership1) || !(count($membership1) > 0)) {
			$toDelete = TRUE;
			foreach ($membership1 as $m1) {
				$m1 -> setIsValid(0);
				$m1 -> setDateLastModified(new DateTime());
				$this -> CI -> doctrine -> em -> persist($m1);
			}
		}

		foreach ($groups as $g) {

			$query2 = $this -> CI -> doctrine -> em -> createQuery('SELECT u FROM Entities\UserGroupMembership u JOIN u.school_staff h JOIN u.user_group g WHERE h.id=?1 AND g.name =?2');
			$query2 -> setParameter(1, $staffId);
			$query2 -> setParameter(2, $g);

			$membership2 = $query2 -> getResult();

			if ((count($membership2) > 0) && $membership2[0]->getIsValid()==1) {
				//$mup = $membership2[0];
				//already
			} else {//new
				$ug = $this -> CI -> doctrine -> em -> getRepository('Entities\UserGroup') -> findOneBy(array('name' => strtoupper($g)));

				$ugm = new Entities\UserGroupMembership;

				$ugm -> setSchoolStaff($this -> CI -> doctrine -> em -> getReference('Entities\SchoolStaff', $staffId));
				$ugm -> setUserGroup($ug);
				$ugm -> setDateCreated(new DateTime());
				$ugm -> setDateLastModified(new DateTime());
				$ugm -> setIsValid(1);

				$this -> CI -> doctrine -> em -> persist($ugm);
			}
		}
		
		$this -> CI -> doctrine -> em -> flush();

		return array('success' => TRUE, 'msg'=>'Operation successful');
	}


	public function resetPassword($userId, $newPassword){

		$this -> CI -> load -> library('tank_auth');
		$authScv = new Tank_auth();
		
		$result = $authScv->force_change_password($userId, $newPassword);
		
		if(is_null($result)){
			return array('success'=>FALSE,'msg'=>'Password change failed');
		}else{
			return array('success'=>TRUE,'result'=>$result,'msg'=>'Password changed successfully.');
		}
	}
		
		
	public function setupUserSessionAux($username){

		$sql = "select ss.id as `staff_id`, ss.first_name, ss.surname, GROUP_CONCAT(ug.name SEPARATOR '|') AS `groups` from school_staff ss
				join zauth_users zu ON zu.id = ss.user_id
				join user_group_membership ugm ON ugm.school_staff_id = ss.id
				join user_group ug ON ug.id=ugm.user_group_id				
				WHERE zu.username=?
				GROUP BY ss.id";

		$rsm = new \Doctrine\ORM\Query\ResultSetMapping;
		$rsm -> addScalarResult('staff_id', 'staff_id');
		$rsm -> addScalarResult('first_name', 'first_name');
		$rsm -> addScalarResult('surname', 'surname');
		$rsm -> addScalarResult('groups', 'groups');

		$query = $this -> CI -> doctrine -> em -> createNativeQuery($sql, $rsm);
		$query -> setParameter(1, $username);

		$result = $query -> getArrayResult();
		
		$this -> CI->session->set_userdata('staff_id', $result[0]['staff_id']);
		$this -> CI->session->set_userdata('first_name', $result[0]['first_name']);
		$this -> CI->session->set_userdata('surname', $result[0]['surname']);
		$this -> CI->session->set_userdata('group_list', explode('|',$result[0]['groups']));
		
	}

	
	public function getCurrentUserLatestActivity($page=1){
		
			$username = $this->CI -> session -> userdata('username');
		
		$query = $this -> CI -> doctrine -> em ->
		createQuery('SELECT l FROM Entities\Log l WHERE l.username=?1 ORDER BY l.date_created DESC') 
		->setParameter(1, $username)
		-> setFirstResult($page-1) 
		-> setMaxResults(25);


		return $query->getArrayResult();
	}
	
	
	public function getGroups(){
		
		$groups = array();		
		
		$records = $this->CI->doctrine->em->getRepository("Entities\UserGroup")->findBy(array('is_valid'=>1), array('name' => 'ASC'));		
		
		foreach($records as $t){
			$groups[$t->getId()]= $t->getName();
		}
		
		return $groups;
	}	
		
	public function getSystemUsers(){

		$sql = "SELECT ss.id AS `staff_id`, ss.user_id, ss.first_name, ss.surname, ss.email , ss.staff_status_id
				, ss.system_account_status_id, z.username,z.banned,z.ban_reason 
				, grps.groups
				FROM school_staff ss
				LEFT JOIN zauth_users z ON z.id =ss.user_id
				LEFT JOIN (SELECT ugm.school_staff_id, GROUP_CONCAT(ug.name SEPARATOR '|') AS `groups` FROM user_group ug 
				JOIN user_group_membership ugm ON ug.id=ugm.user_group_id AND ugm.is_valid=1
				GROUP BY ugm.school_staff_id ) grps
				ON grps.school_staff_id = ss.id
				ORDER BY ss.surname";

		$rsm = new \Doctrine\ORM\Query\ResultSetMapping;
		$rsm -> addScalarResult('staff_id', 'staff_id');
		$rsm -> addScalarResult('user_id', 'user_id');
		$rsm -> addScalarResult('first_name', 'first_name');
		$rsm -> addScalarResult('surname', 'surname');
		$rsm -> addScalarResult('email', 'email');
		$rsm -> addScalarResult('staff_status_id', 'staff_status_id');
		$rsm -> addScalarResult('system_account_status_id', 'system_account_status_id');
		$rsm -> addScalarResult('username', 'username');
		$rsm -> addScalarResult('groups', 'groups');
		$rsm -> addScalarResult('banned', 'banned');
		$rsm -> addScalarResult('ban_reason', 'ban_reason');

		$query = $this -> CI -> doctrine -> em -> createNativeQuery($sql, $rsm);

		return $query -> getArrayResult();
		
	}
	
	}

//end Usersvc

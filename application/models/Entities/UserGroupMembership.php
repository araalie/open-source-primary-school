<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entities\UserGroupMembership
 */
class UserGroupMembership
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var datetime $date_created
     */
    private $date_created;

    /**
     * @var datetime $date_last_modified
     */
    private $date_last_modified;

    /**
     * @var boolean $is_valid
     */
    private $is_valid;

    /**
     * @var Entities\SchoolStaff
     */
    private $school_staff;

    /**
     * @var Entities\UserGroup
     */
    private $user_group;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set date_created
     *
     * @param datetime $dateCreated
     * @return UserGroupMembership
     */
    public function setDateCreated($dateCreated)
    {
        $this->date_created = $dateCreated;
        return $this;
    }

    /**
     * Get date_created
     *
     * @return datetime 
     */
    public function getDateCreated()
    {
        return $this->date_created;
    }

    /**
     * Set date_last_modified
     *
     * @param datetime $dateLastModified
     * @return UserGroupMembership
     */
    public function setDateLastModified($dateLastModified)
    {
        $this->date_last_modified = $dateLastModified;
        return $this;
    }

    /**
     * Get date_last_modified
     *
     * @return datetime 
     */
    public function getDateLastModified()
    {
        return $this->date_last_modified;
    }

    /**
     * Set is_valid
     *
     * @param boolean $isValid
     * @return UserGroupMembership
     */
    public function setIsValid($isValid)
    {
        $this->is_valid = $isValid;
        return $this;
    }

    /**
     * Get is_valid
     *
     * @return boolean 
     */
    public function getIsValid()
    {
        return $this->is_valid;
    }

    /**
     * Set school_staff
     *
     * @param Entities\SchoolStaff $schoolStaff
     * @return UserGroupMembership
     */
    public function setSchoolStaff(\Entities\SchoolStaff $schoolStaff = null)
    {
        $this->school_staff = $schoolStaff;
        return $this;
    }

    /**
     * Get school_staff
     *
     * @return Entities\SchoolStaff 
     */
    public function getSchoolStaff()
    {
        return $this->school_staff;
    }

    /**
     * Set user_group
     *
     * @param Entities\UserGroup $userGroup
     * @return UserGroupMembership
     */
    public function setUserGroup(\Entities\UserGroup $userGroup = null)
    {
        $this->user_group = $userGroup;
        return $this;
    }

    /**
     * Get user_group
     *
     * @return Entities\UserGroup 
     */
    public function getUserGroup()
    {
        return $this->user_group;
    }
}
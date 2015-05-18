<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entities\StudentClassHistory
 */
class StudentClassHistory
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var string $comment
     */
    private $comment;

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
     * @var Entities\ClassInstance
     */
    private $class_instance;

    /**
     * @var Entities\Student
     */
    private $student;

    /**
     * @var Entities\Term
     */
    private $term;

    /**
     * @var Entities\FeesProfile
     */
    private $fees_profile;


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
     * Set comment
     *
     * @param string $comment
     * @return StudentClassHistory
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
        return $this;
    }

    /**
     * Get comment
     *
     * @return string 
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set date_created
     *
     * @param datetime $dateCreated
     * @return StudentClassHistory
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
     * @return StudentClassHistory
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
     * @return StudentClassHistory
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
     * Set class_instance
     *
     * @param Entities\ClassInstance $classInstance
     * @return StudentClassHistory
     */
    public function setClassInstance(\Entities\ClassInstance $classInstance = null)
    {
        $this->class_instance = $classInstance;
        return $this;
    }

    /**
     * Get class_instance
     *
     * @return Entities\ClassInstance 
     */
    public function getClassInstance()
    {
        return $this->class_instance;
    }

    /**
     * Set student
     *
     * @param Entities\Student $student
     * @return StudentClassHistory
     */
    public function setStudent(\Entities\Student $student = null)
    {
        $this->student = $student;
        return $this;
    }

    /**
     * Get student
     *
     * @return Entities\Student 
     */
    public function getStudent()
    {
        return $this->student;
    }

    /**
     * Set term
     *
     * @param Entities\Term $term
     * @return StudentClassHistory
     */
    public function setTerm(\Entities\Term $term = null)
    {
        $this->term = $term;
        return $this;
    }

    /**
     * Get term
     *
     * @return Entities\Term 
     */
    public function getTerm()
    {
        return $this->term;
    }

    /**
     * Set fees_profile
     *
     * @param Entities\FeesProfile $feesProfile
     * @return StudentClassHistory
     */
    public function setFeesProfile(\Entities\FeesProfile $feesProfile = null)
    {
        $this->fees_profile = $feesProfile;
        return $this;
    }

    /**
     * Get fees_profile
     *
     * @return Entities\FeesProfile 
     */
    public function getFeesProfile()
    {
        return $this->fees_profile;
    }
}
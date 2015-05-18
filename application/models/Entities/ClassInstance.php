<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entities\ClassInstance
 */
class ClassInstance
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var string $name
     */
    private $name;

    /**
     * @var string $description
     */
    private $description;

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
     * @var Entities\ClassType
     */
    private $class_type;

    /**
     * @var Entities\Term
     */
    private $term;

    /**
     * @var Entities\ClassInstanceStatus
     */
    private $class_instance_status;

    /**
     * @var Entities\SchoolStaff
     */
    private $class_teacher;


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
     * Set name
     *
     * @param string $name
     * @return ClassInstance
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return ClassInstance
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set date_created
     *
     * @param datetime $dateCreated
     * @return ClassInstance
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
     * @return ClassInstance
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
     * @return ClassInstance
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
     * Set class_type
     *
     * @param Entities\ClassType $classType
     * @return ClassInstance
     */
    public function setClassType(\Entities\ClassType $classType = null)
    {
        $this->class_type = $classType;
        return $this;
    }

    /**
     * Get class_type
     *
     * @return Entities\ClassType 
     */
    public function getClassType()
    {
        return $this->class_type;
    }

    /**
     * Set term
     *
     * @param Entities\Term $term
     * @return ClassInstance
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
     * Set class_instance_status
     *
     * @param Entities\ClassInstanceStatus $classInstanceStatus
     * @return ClassInstance
     */
    public function setClassInstanceStatus(\Entities\ClassInstanceStatus $classInstanceStatus = null)
    {
        $this->class_instance_status = $classInstanceStatus;
        return $this;
    }

    /**
     * Get class_instance_status
     *
     * @return Entities\ClassInstanceStatus 
     */
    public function getClassInstanceStatus()
    {
        return $this->class_instance_status;
    }

    /**
     * Set class_teacher
     *
     * @param Entities\SchoolStaff $classTeacher
     * @return ClassInstance
     */
    public function setClassTeacher(\Entities\SchoolStaff $classTeacher = null)
    {
        $this->class_teacher = $classTeacher;
        return $this;
    }

    /**
     * Get class_teacher
     *
     * @return Entities\SchoolStaff 
     */
    public function getClassTeacher()
    {
        return $this->class_teacher;
    }
    /**
     * @var Entities\ClassInstance
     */
    private $previous_class_instance;

    /**
     * @var Entities\ClassInstance
     */
    private $next_class_instance;


    /**
     * Set previous_class_instance
     *
     * @param Entities\ClassInstance $previousClassInstance
     * @return ClassInstance
     */
    public function setPreviousClassInstance(\Entities\ClassInstance $previousClassInstance = null)
    {
        $this->previous_class_instance = $previousClassInstance;
        return $this;
    }

    /**
     * Get previous_class_instance
     *
     * @return Entities\ClassInstance 
     */
    public function getPreviousClassInstance()
    {
        return $this->previous_class_instance;
    }

    /**
     * Set next_class_instance
     *
     * @param Entities\ClassInstance $nextClassInstance
     * @return ClassInstance
     */
    public function setNextClassInstance(\Entities\ClassInstance $nextClassInstance = null)
    {
        $this->next_class_instance = $nextClassInstance;
        return $this;
    }

    /**
     * Get next_class_instance
     *
     * @return Entities\ClassInstance 
     */
    public function getNextClassInstance()
    {
        return $this->next_class_instance;
    }
}
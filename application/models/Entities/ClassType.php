<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entities\ClassType
 */
class ClassType
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
     * @var integer $level
     */
    private $level;

    /**
     * @var boolean $is_first_in_school_division
     */
    private $is_first_in_school_division;

    /**
     * @var boolean $is_last_in_school_division
     */
    private $is_last_in_school_division;

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
     * @var Entities\SchoolDivision
     */
    private $school_division;

    /**
     * @var Entities\GradingMode
     */
    private $default_grading_mode;


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
     * @return ClassType
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
     * @return ClassType
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
     * Set level
     *
     * @param integer $level
     * @return ClassType
     */
    public function setLevel($level)
    {
        $this->level = $level;
        return $this;
    }

    /**
     * Get level
     *
     * @return integer 
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Set is_first_in_school_division
     *
     * @param boolean $isFirstInSchoolDivision
     * @return ClassType
     */
    public function setIsFirstInSchoolDivision($isFirstInSchoolDivision)
    {
        $this->is_first_in_school_division = $isFirstInSchoolDivision;
        return $this;
    }

    /**
     * Get is_first_in_school_division
     *
     * @return boolean 
     */
    public function getIsFirstInSchoolDivision()
    {
        return $this->is_first_in_school_division;
    }

    /**
     * Set is_last_in_school_division
     *
     * @param boolean $isLastInSchoolDivision
     * @return ClassType
     */
    public function setIsLastInSchoolDivision($isLastInSchoolDivision)
    {
        $this->is_last_in_school_division = $isLastInSchoolDivision;
        return $this;
    }

    /**
     * Get is_last_in_school_division
     *
     * @return boolean 
     */
    public function getIsLastInSchoolDivision()
    {
        return $this->is_last_in_school_division;
    }

    /**
     * Set date_created
     *
     * @param datetime $dateCreated
     * @return ClassType
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
     * @return ClassType
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
     * @return ClassType
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
     * Set school_division
     *
     * @param Entities\SchoolDivision $schoolDivision
     * @return ClassType
     */
    public function setSchoolDivision(\Entities\SchoolDivision $schoolDivision = null)
    {
        $this->school_division = $schoolDivision;
        return $this;
    }

    /**
     * Get school_division
     *
     * @return Entities\SchoolDivision 
     */
    public function getSchoolDivision()
    {
        return $this->school_division;
    }

    /**
     * Set default_grading_mode
     *
     * @param Entities\GradingMode $defaultGradingMode
     * @return ClassType
     */
    public function setDefaultGradingMode(\Entities\GradingMode $defaultGradingMode = null)
    {
        $this->default_grading_mode = $defaultGradingMode;
        return $this;
    }

    /**
     * Get default_grading_mode
     *
     * @return Entities\GradingMode 
     */
    public function getDefaultGradingMode()
    {
        return $this->default_grading_mode;
    }
}
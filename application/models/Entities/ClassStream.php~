<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entities\ClassStream
 */
class ClassStream
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
     * @var integer $is_valid
     */
    private $is_valid;

    /**
     * @var Entities\SchoolDivision
     */
    private $school_division;


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
     * @return ClassStream
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
     * @return ClassStream
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
     * @return ClassStream
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
     * @return ClassStream
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
     * @param integer $isValid
     * @return ClassStream
     */
    public function setIsValid($isValid)
    {
        $this->is_valid = $isValid;
        return $this;
    }

    /**
     * Get is_valid
     *
     * @return integer 
     */
    public function getIsValid()
    {
        return $this->is_valid;
    }

    /**
     * Set school_division
     *
     * @param Entities\SchoolDivision $schoolDivision
     * @return ClassStream
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
}
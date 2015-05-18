<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entities\Term
 */
class Term
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
     * @var datetime $date_began
     */
    private $date_began;

    /**
     * @var datetime $date_ended
     */
    private $date_ended;

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
     * @var Entities\AcademicYear
     */
    private $academic_year;

    /**
     * @var Entities\TermStatus
     */
    private $term_status;

    /**
     * @var Entities\TermType
     */
    private $term_type;

    /**
     * @var Entities\Term
     */
    private $previous_term;

    /**
     * @var Entities\Term
     */
    private $next_term;


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
     * @return Term
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
     * @return Term
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
     * Set date_began
     *
     * @param datetime $dateBegan
     * @return Term
     */
    public function setDateBegan($dateBegan)
    {
        $this->date_began = $dateBegan;
        return $this;
    }

    /**
     * Get date_began
     *
     * @return datetime 
     */
    public function getDateBegan()
    {
        return $this->date_began;
    }

    /**
     * Set date_ended
     *
     * @param datetime $dateEnded
     * @return Term
     */
    public function setDateEnded($dateEnded)
    {
        $this->date_ended = $dateEnded;
        return $this;
    }

    /**
     * Get date_ended
     *
     * @return datetime 
     */
    public function getDateEnded()
    {
        return $this->date_ended;
    }

    /**
     * Set date_created
     *
     * @param datetime $dateCreated
     * @return Term
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
     * @return Term
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
     * @return Term
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
     * Set academic_year
     *
     * @param Entities\AcademicYear $academicYear
     * @return Term
     */
    public function setAcademicYear(\Entities\AcademicYear $academicYear = null)
    {
        $this->academic_year = $academicYear;
        return $this;
    }

    /**
     * Get academic_year
     *
     * @return Entities\AcademicYear 
     */
    public function getAcademicYear()
    {
        return $this->academic_year;
    }

    /**
     * Set term_status
     *
     * @param Entities\TermStatus $termStatus
     * @return Term
     */
    public function setTermStatus(\Entities\TermStatus $termStatus = null)
    {
        $this->term_status = $termStatus;
        return $this;
    }

    /**
     * Get term_status
     *
     * @return Entities\TermStatus 
     */
    public function getTermStatus()
    {
        return $this->term_status;
    }

    /**
     * Set term_type
     *
     * @param Entities\TermType $termType
     * @return Term
     */
    public function setTermType(\Entities\TermType $termType = null)
    {
        $this->term_type = $termType;
        return $this;
    }

    /**
     * Get term_type
     *
     * @return Entities\TermType 
     */
    public function getTermType()
    {
        return $this->term_type;
    }

    /**
     * Set previous_term
     *
     * @param Entities\Term $previousTerm
     * @return Term
     */
    public function setPreviousTerm(\Entities\Term $previousTerm = null)
    {
        $this->previous_term = $previousTerm;
        return $this;
    }

    /**
     * Get previous_term
     *
     * @return Entities\Term 
     */
    public function getPreviousTerm()
    {
        return $this->previous_term;
    }

    /**
     * Set next_term
     *
     * @param Entities\Term $nextTerm
     * @return Term
     */
    public function setNextTerm(\Entities\Term $nextTerm = null)
    {
        $this->next_term = $nextTerm;
        return $this;
    }

    /**
     * Get next_term
     *
     * @return Entities\Term 
     */
    public function getNextTerm()
    {
        return $this->next_term;
    }
}
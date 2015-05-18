<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entities\GradingRange
 */
class GradingRange
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var integer $minimum
     */
    private $minimum;

    /**
     * @var integer $maximum
     */
    private $maximum;

    /**
     * @var string $code
     */
    private $code;

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
     * @var Entities\Grading
     */
    private $grading;


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
     * Set minimum
     *
     * @param integer $minimum
     * @return GradingRange
     */
    public function setMinimum($minimum)
    {
        $this->minimum = $minimum;
        return $this;
    }

    /**
     * Get minimum
     *
     * @return integer 
     */
    public function getMinimum()
    {
        return $this->minimum;
    }

    /**
     * Set maximum
     *
     * @param integer $maximum
     * @return GradingRange
     */
    public function setMaximum($maximum)
    {
        $this->maximum = $maximum;
        return $this;
    }

    /**
     * Get maximum
     *
     * @return integer 
     */
    public function getMaximum()
    {
        return $this->maximum;
    }

    /**
     * Set code
     *
     * @param string $code
     * @return GradingRange
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * Get code
     *
     * @return string 
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set date_created
     *
     * @param datetime $dateCreated
     * @return GradingRange
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
     * @return GradingRange
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
     * @return GradingRange
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
     * Set grading
     *
     * @param Entities\Grading $grading
     * @return GradingRange
     */
    public function setGrading(\Entities\Grading $grading = null)
    {
        $this->grading = $grading;
        return $this;
    }

    /**
     * Get grading
     *
     * @return Entities\Grading 
     */
    public function getGrading()
    {
        return $this->grading;
    }
}
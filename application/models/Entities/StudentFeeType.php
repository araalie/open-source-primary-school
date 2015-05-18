<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entities\StudentFeeType
 */
class StudentFeeType
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
     * @var string $narrative
     */
    private $narrative;

    /**
     * @var integer $ordering
     */
    private $ordering;

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
     * @var Entities\FeesProfile
     */
    private $fees_profile;

    /**
     * @var Entities\FeeFrequencyType
     */
    private $fee_frequency_type;


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
     * @return StudentFeeType
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
     * Set narrative
     *
     * @param string $narrative
     * @return StudentFeeType
     */
    public function setNarrative($narrative)
    {
        $this->narrative = $narrative;
        return $this;
    }

    /**
     * Get narrative
     *
     * @return string 
     */
    public function getNarrative()
    {
        return $this->narrative;
    }

    /**
     * Set ordering
     *
     * @param integer $ordering
     * @return StudentFeeType
     */
    public function setOrdering($ordering)
    {
        $this->ordering = $ordering;
        return $this;
    }

    /**
     * Get ordering
     *
     * @return integer 
     */
    public function getOrdering()
    {
        return $this->ordering;
    }

    /**
     * Set date_created
     *
     * @param datetime $dateCreated
     * @return StudentFeeType
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
     * @return StudentFeeType
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
     * @return StudentFeeType
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
     * Set fees_profile
     *
     * @param Entities\FeesProfile $feesProfile
     * @return StudentFeeType
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

    /**
     * Set fee_frequency_type
     *
     * @param Entities\FeeFrequencyType $feeFrequencyType
     * @return StudentFeeType
     */
    public function setFeeFrequencyType(\Entities\FeeFrequencyType $feeFrequencyType = null)
    {
        $this->fee_frequency_type = $feeFrequencyType;
        return $this;
    }

    /**
     * Get fee_frequency_type
     *
     * @return Entities\FeeFrequencyType 
     */
    public function getFeeFrequencyType()
    {
        return $this->fee_frequency_type;
    }
}
<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entities\TermFees
 */
class TermFees
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var float $amount
     */
    private $amount;

    /**
     * @var boolean $is_compulsary
     */
    private $is_compulsary;

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
     * @var Entities\StudentFeeType
     */
    private $student_fee_type;


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
     * Set amount
     *
     * @param float $amount
     * @return TermFees
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * Get amount
     *
     * @return float 
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set is_compulsary
     *
     * @param boolean $isCompulsary
     * @return TermFees
     */
    public function setIsCompulsary($isCompulsary)
    {
        $this->is_compulsary = $isCompulsary;
        return $this;
    }

    /**
     * Get is_compulsary
     *
     * @return boolean 
     */
    public function getIsCompulsary()
    {
        return $this->is_compulsary;
    }

    /**
     * Set date_created
     *
     * @param datetime $dateCreated
     * @return TermFees
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
     * @return TermFees
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
     * @return TermFees
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
     * @return TermFees
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
     * Set student_fee_type
     *
     * @param Entities\StudentFeeType $studentFeeType
     * @return TermFees
     */
    public function setStudentFeeType(\Entities\StudentFeeType $studentFeeType = null)
    {
        $this->student_fee_type = $studentFeeType;
        return $this;
    }

    /**
     * Get student_fee_type
     *
     * @return Entities\StudentFeeType 
     */
    public function getStudentFeeType()
    {
        return $this->student_fee_type;
    }
}
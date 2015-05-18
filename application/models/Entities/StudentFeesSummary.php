<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entities\StudentFeesSummary
 */
class StudentFeesSummary
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var float $term_amount_paid
     */
    private $term_amount_paid;

    /**
     * @var float $term_amount_owed
     */
    private $term_amount_owed;

    /**
     * @var float $annual_amount_paid
     */
    private $annual_amount_paid;

    /**
     * @var float $annual_amount_owed
     */
    private $annual_amount_owed;

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
     * @var Entities\Student
     */
    private $student;

    /**
     * @var Entities\ClassInstance
     */
    private $class_instance;

    /**
     * @var Entities\Term
     */
    private $term;


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
     * Set term_amount_paid
     *
     * @param float $termAmountPaid
     * @return StudentFeesSummary
     */
    public function setTermAmountPaid($termAmountPaid)
    {
        $this->term_amount_paid = $termAmountPaid;
        return $this;
    }

    /**
     * Get term_amount_paid
     *
     * @return float 
     */
    public function getTermAmountPaid()
    {
        return $this->term_amount_paid;
    }

    /**
     * Set term_amount_owed
     *
     * @param float $termAmountOwed
     * @return StudentFeesSummary
     */
    public function setTermAmountOwed($termAmountOwed)
    {
        $this->term_amount_owed = $termAmountOwed;
        return $this;
    }

    /**
     * Get term_amount_owed
     *
     * @return float 
     */
    public function getTermAmountOwed()
    {
        return $this->term_amount_owed;
    }

    /**
     * Set annual_amount_paid
     *
     * @param float $annualAmountPaid
     * @return StudentFeesSummary
     */
    public function setAnnualAmountPaid($annualAmountPaid)
    {
        $this->annual_amount_paid = $annualAmountPaid;
        return $this;
    }

    /**
     * Get annual_amount_paid
     *
     * @return float 
     */
    public function getAnnualAmountPaid()
    {
        return $this->annual_amount_paid;
    }

    /**
     * Set annual_amount_owed
     *
     * @param float $annualAmountOwed
     * @return StudentFeesSummary
     */
    public function setAnnualAmountOwed($annualAmountOwed)
    {
        $this->annual_amount_owed = $annualAmountOwed;
        return $this;
    }

    /**
     * Get annual_amount_owed
     *
     * @return float 
     */
    public function getAnnualAmountOwed()
    {
        return $this->annual_amount_owed;
    }

    /**
     * Set date_created
     *
     * @param datetime $dateCreated
     * @return StudentFeesSummary
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
     * @return StudentFeesSummary
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
     * @return StudentFeesSummary
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
     * Set student
     *
     * @param Entities\Student $student
     * @return StudentFeesSummary
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
     * Set class_instance
     *
     * @param Entities\ClassInstance $classInstance
     * @return StudentFeesSummary
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
     * Set term
     *
     * @param Entities\Term $term
     * @return StudentFeesSummary
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
     * @var float $other_term_payments
     */
    private $other_term_payments;


    /**
     * Set other_term_payments
     *
     * @param float $otherTermPayments
     * @return StudentFeesSummary
     */
    public function setOtherTermPayments($otherTermPayments)
    {
        $this->other_term_payments = $otherTermPayments;
        return $this;
    }

    /**
     * Get other_term_payments
     *
     * @return float 
     */
    public function getOtherTermPayments()
    {
        return $this->other_term_payments;
    }
    /**
     * @var float $other_annual_payments
     */
    private $other_annual_payments;


    /**
     * Set other_annual_payments
     *
     * @param float $otherAnnualPayments
     * @return StudentFeesSummary
     */
    public function setOtherAnnualPayments($otherAnnualPayments)
    {
        $this->other_annual_payments = $otherAnnualPayments;
        return $this;
    }

    /**
     * Get other_annual_payments
     *
     * @return float 
     */
    public function getOtherAnnualPayments()
    {
        return $this->other_annual_payments;
    }
    /**
     * @var float $compulsary_amount_paid
     */
    private $compulsary_amount_paid;

    /**
     * @var float $compulsary_amount_owed
     */
    private $compulsary_amount_owed;

    /**
     * @var float $other_amount_paid
     */
    private $other_amount_paid;

    /**
     * @var float $other_amount_owed
     */
    private $other_amount_owed;

    /**
     * @var Entities\FeeFrequencyType
     */
    private $fee_frequency_type;


    /**
     * Set compulsary_amount_paid
     *
     * @param float $compulsaryAmountPaid
     * @return StudentFeesSummary
     */
    public function setCompulsaryAmountPaid($compulsaryAmountPaid)
    {
        $this->compulsary_amount_paid = $compulsaryAmountPaid;
        return $this;
    }

    /**
     * Get compulsary_amount_paid
     *
     * @return float 
     */
    public function getCompulsaryAmountPaid()
    {
        return $this->compulsary_amount_paid;
    }

    /**
     * Set compulsary_amount_owed
     *
     * @param float $compulsaryAmountOwed
     * @return StudentFeesSummary
     */
    public function setCompulsaryAmountOwed($compulsaryAmountOwed)
    {
        $this->compulsary_amount_owed = $compulsaryAmountOwed;
        return $this;
    }

    /**
     * Get compulsary_amount_owed
     *
     * @return float 
     */
    public function getCompulsaryAmountOwed()
    {
        return $this->compulsary_amount_owed;
    }

    /**
     * Set other_amount_paid
     *
     * @param float $otherAmountPaid
     * @return StudentFeesSummary
     */
    public function setOtherAmountPaid($otherAmountPaid)
    {
        $this->other_amount_paid = $otherAmountPaid;
        return $this;
    }

    /**
     * Get other_amount_paid
     *
     * @return float 
     */
    public function getOtherAmountPaid()
    {
        return $this->other_amount_paid;
    }

    /**
     * Set other_amount_owed
     *
     * @param float $otherAmountOwed
     * @return StudentFeesSummary
     */
    public function setOtherAmountOwed($otherAmountOwed)
    {
        $this->other_amount_owed = $otherAmountOwed;
        return $this;
    }

    /**
     * Get other_amount_owed
     *
     * @return float 
     */
    public function getOtherAmountOwed()
    {
        return $this->other_amount_owed;
    }

    /**
     * Set fee_frequency_type
     *
     * @param Entities\FeeFrequencyType $feeFrequencyType
     * @return StudentFeesSummary
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
    /**
     * @var Entities\TermFeesSummaryStatus
     */
    private $term_fees_summary_status;


    /**
     * Set term_fees_summary_status
     *
     * @param Entities\TermFeesSummaryStatus $termFeesSummaryStatus
     * @return StudentFeesSummary
     */
    public function setTermFeesSummaryStatus(\Entities\TermFeesSummaryStatus $termFeesSummaryStatus = null)
    {
        $this->term_fees_summary_status = $termFeesSummaryStatus;
        return $this;
    }

    /**
     * Get term_fees_summary_status
     *
     * @return Entities\TermFeesSummaryStatus 
     */
    public function getTermFeesSummaryStatus()
    {
        return $this->term_fees_summary_status;
    }
    /**
     * @var Entities\FeesSummaryStatus
     */
    private $fees_summary_status;


    /**
     * Set fees_summary_status
     *
     * @param Entities\FeesSummaryStatus $feesSummaryStatus
     * @return StudentFeesSummary
     */
    public function setFeesSummaryStatus(\Entities\FeesSummaryStatus $feesSummaryStatus = null)
    {
        $this->fees_summary_status = $feesSummaryStatus;
        return $this;
    }

    /**
     * Get fees_summary_status
     *
     * @return Entities\FeesSummaryStatus 
     */
    public function getFeesSummaryStatus()
    {
        return $this->fees_summary_status;
    }
}
<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entities\Debt
 */
class Debt
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var float $total_debt_amount
     */
    private $total_debt_amount;

    /**
     * @var float $paid_amount
     */
    private $paid_amount;

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
     * @var Entities\Account
     */
    private $account;

    /**
     * @var Entities\ClassInstance
     */
    private $class_instance;

    /**
     * @var Entities\Term
     */
    private $term_incurred;

    /**
     * @var Entities\Term
     */
    private $term_cleared;

    /**
     * @var Entities\DebtType
     */
    private $debt_type;

    /**
     * @var Entities\DebtStatus
     */
    private $debt_status;


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
     * Set total_debt_amount
     *
     * @param float $totalDebtAmount
     * @return Debt
     */
    public function setTotalDebtAmount($totalDebtAmount)
    {
        $this->total_debt_amount = $totalDebtAmount;
        return $this;
    }

    /**
     * Get total_debt_amount
     *
     * @return float 
     */
    public function getTotalDebtAmount()
    {
        return $this->total_debt_amount;
    }

    /**
     * Set paid_amount
     *
     * @param float $paidAmount
     * @return Debt
     */
    public function setPaidAmount($paidAmount)
    {
        $this->paid_amount = $paidAmount;
        return $this;
    }

    /**
     * Get paid_amount
     *
     * @return float 
     */
    public function getPaidAmount()
    {
        return $this->paid_amount;
    }

    /**
     * Set date_created
     *
     * @param datetime $dateCreated
     * @return Debt
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
     * @return Debt
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
     * @return Debt
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
     * Set account
     *
     * @param Entities\Account $account
     * @return Debt
     */
    public function setAccount(\Entities\Account $account = null)
    {
        $this->account = $account;
        return $this;
    }

    /**
     * Get account
     *
     * @return Entities\Account 
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * Set class_instance
     *
     * @param Entities\ClassInstance $classInstance
     * @return Debt
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
     * Set term_incurred
     *
     * @param Entities\Term $termIncurred
     * @return Debt
     */
    public function setTermIncurred(\Entities\Term $termIncurred = null)
    {
        $this->term_incurred = $termIncurred;
        return $this;
    }

    /**
     * Get term_incurred
     *
     * @return Entities\Term 
     */
    public function getTermIncurred()
    {
        return $this->term_incurred;
    }

    /**
     * Set term_cleared
     *
     * @param Entities\Term $termCleared
     * @return Debt
     */
    public function setTermCleared(\Entities\Term $termCleared = null)
    {
        $this->term_cleared = $termCleared;
        return $this;
    }

    /**
     * Get term_cleared
     *
     * @return Entities\Term 
     */
    public function getTermCleared()
    {
        return $this->term_cleared;
    }

    /**
     * Set debt_type
     *
     * @param Entities\DebtType $debtType
     * @return Debt
     */
    public function setDebtType(\Entities\DebtType $debtType = null)
    {
        $this->debt_type = $debtType;
        return $this;
    }

    /**
     * Get debt_type
     *
     * @return Entities\DebtType 
     */
    public function getDebtType()
    {
        return $this->debt_type;
    }

    /**
     * Set debt_status
     *
     * @param Entities\DebtStatus $debtStatus
     * @return Debt
     */
    public function setDebtStatus(\Entities\DebtStatus $debtStatus = null)
    {
        $this->debt_status = $debtStatus;
        return $this;
    }

    /**
     * Get debt_status
     *
     * @return Entities\DebtStatus 
     */
    public function getDebtStatus()
    {
        return $this->debt_status;
    }
    /**
     * @var string $narrative
     */
    private $narrative;


    /**
     * Set narrative
     *
     * @param string $narrative
     * @return Debt
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
     * @var Entities\FeeFrequencyType
     */
    private $fee_frequency_type;


    /**
     * Set fee_frequency_type
     *
     * @param Entities\FeeFrequencyType $feeFrequencyType
     * @return Debt
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
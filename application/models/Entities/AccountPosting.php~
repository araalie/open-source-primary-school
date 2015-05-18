<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entities\AccountPosting
 */
class AccountPosting
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
     * @var Entities\Transaction
     */
    private $transaction;

    /**
     * @var Entities\Account
     */
    private $account;

    /**
     * @var Entities\StudentFeeType
     */
    private $student_fee_type;

    /**
     * @var Entities\AccountPostingStatus
     */
    private $account_posting_status;


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
     * @return AccountPosting
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
     * Set date_created
     *
     * @param datetime $dateCreated
     * @return AccountPosting
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
     * @return AccountPosting
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
     * @return AccountPosting
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
     * Set transaction
     *
     * @param Entities\Transaction $transaction
     * @return AccountPosting
     */
    public function setTransaction(\Entities\Transaction $transaction = null)
    {
        $this->transaction = $transaction;
        return $this;
    }

    /**
     * Get transaction
     *
     * @return Entities\Transaction 
     */
    public function getTransaction()
    {
        return $this->transaction;
    }

    /**
     * Set account
     *
     * @param Entities\Account $account
     * @return AccountPosting
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
     * Set student_fee_type
     *
     * @param Entities\StudentFeeType $studentFeeType
     * @return AccountPosting
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

    /**
     * Set account_posting_status
     *
     * @param Entities\AccountPostingStatus $accountPostingStatus
     * @return AccountPosting
     */
    public function setAccountPostingStatus(\Entities\AccountPostingStatus $accountPostingStatus = null)
    {
        $this->account_posting_status = $accountPostingStatus;
        return $this;
    }

    /**
     * Get account_posting_status
     *
     * @return Entities\AccountPostingStatus 
     */
    public function getAccountPostingStatus()
    {
        return $this->account_posting_status;
    }
    /**
     * @var Entities\Debt
     */
    private $debt;


    /**
     * Set debt
     *
     * @param Entities\Debt $debt
     * @return AccountPosting
     */
    public function setDebt(\Entities\Debt $debt = null)
    {
        $this->debt = $debt;
        return $this;
    }

    /**
     * Get debt
     *
     * @return Entities\Debt 
     */
    public function getDebt()
    {
        return $this->debt;
    }
}
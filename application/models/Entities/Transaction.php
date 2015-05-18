<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entities\Transaction
 */
class Transaction
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var string $created_by
     */
    private $created_by;

    /**
     * @var string $narrative
     */
    private $narrative;

    /**
     * @var string $pay_slip_number
     */
    private $pay_slip_number;

    /**
     * @var date $date_done
     */
    private $date_done;

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
     * @var Entities\TransactionType
     */
    private $transaction_type;

    /**
     * @var Entities\TransactionStatus
     */
    private $transaction_status;

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
     * Set created_by
     *
     * @param string $createdBy
     * @return Transaction
     */
    public function setCreatedBy($createdBy)
    {
        $this->created_by = $createdBy;
        return $this;
    }

    /**
     * Get created_by
     *
     * @return string 
     */
    public function getCreatedBy()
    {
        return $this->created_by;
    }

    /**
     * Set narrative
     *
     * @param string $narrative
     * @return Transaction
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
     * Set pay_slip_number
     *
     * @param string $paySlipNumber
     * @return Transaction
     */
    public function setPaySlipNumber($paySlipNumber)
    {
        $this->pay_slip_number = $paySlipNumber;
        return $this;
    }

    /**
     * Get pay_slip_number
     *
     * @return string 
     */
    public function getPaySlipNumber()
    {
        return $this->pay_slip_number;
    }

    /**
     * Set date_done
     *
     * @param date $dateDone
     * @return Transaction
     */
    public function setDateDone($dateDone)
    {
        $this->date_done = $dateDone;
        return $this;
    }

    /**
     * Get date_done
     *
     * @return date 
     */
    public function getDateDone()
    {
        return $this->date_done;
    }

    /**
     * Set date_created
     *
     * @param datetime $dateCreated
     * @return Transaction
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
     * @return Transaction
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
     * @return Transaction
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
     * Set transaction_type
     *
     * @param Entities\TransactionType $transactionType
     * @return Transaction
     */
    public function setTransactionType(\Entities\TransactionType $transactionType = null)
    {
        $this->transaction_type = $transactionType;
        return $this;
    }

    /**
     * Get transaction_type
     *
     * @return Entities\TransactionType 
     */
    public function getTransactionType()
    {
        return $this->transaction_type;
    }

    /**
     * Set transaction_status
     *
     * @param Entities\TransactionStatus $transactionStatus
     * @return Transaction
     */
    public function setTransactionStatus(\Entities\TransactionStatus $transactionStatus = null)
    {
        $this->transaction_status = $transactionStatus;
        return $this;
    }

    /**
     * Get transaction_status
     *
     * @return Entities\TransactionStatus 
     */
    public function getTransactionStatus()
    {
        return $this->transaction_status;
    }

    /**
     * Set term
     *
     * @param Entities\Term $term
     * @return Transaction
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
}
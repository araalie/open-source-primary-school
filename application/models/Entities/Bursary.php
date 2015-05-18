<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entities\Bursary
 */
class Bursary
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
     * @var string $comments
     */
    private $comments;

    /**
     * @var string $given_by
     */
    private $given_by;

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
     * Set amount
     *
     * @param float $amount
     * @return Bursary
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
     * Set comments
     *
     * @param string $comments
     * @return Bursary
     */
    public function setComments($comments)
    {
        $this->comments = $comments;
        return $this;
    }

    /**
     * Get comments
     *
     * @return string 
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Set given_by
     *
     * @param string $givenBy
     * @return Bursary
     */
    public function setGivenBy($givenBy)
    {
        $this->given_by = $givenBy;
        return $this;
    }

    /**
     * Get given_by
     *
     * @return string 
     */
    public function getGivenBy()
    {
        return $this->given_by;
    }

    /**
     * Set date_created
     *
     * @param datetime $dateCreated
     * @return Bursary
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
     * @return Bursary
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
     * @return Bursary
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
     * @return Bursary
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
     * Set term
     *
     * @param Entities\Term $term
     * @return Bursary
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
     * @var Entities\Transaction
     */
    private $transaction;


    /**
     * Set transaction
     *
     * @param Entities\Transaction $transaction
     * @return Bursary
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
}
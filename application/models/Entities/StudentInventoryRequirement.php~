<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entities\StudentInventoryRequirement
 */
class StudentInventoryRequirement
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var float $number_brought_by_student
     */
    private $number_brought_by_student;

    /**
     * @var float $number_bought_by_school
     */
    private $number_bought_by_school;

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
     * @var Entities\StudentRequiredItem
     */
    private $student_required_item;

    /**
     * @var Entities\Transaction
     */
    private $transaction;


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
     * Set number_brought_by_student
     *
     * @param float $numberBroughtByStudent
     * @return StudentInventoryRequirement
     */
    public function setNumberBroughtByStudent($numberBroughtByStudent)
    {
        $this->number_brought_by_student = $numberBroughtByStudent;
        return $this;
    }

    /**
     * Get number_brought_by_student
     *
     * @return float 
     */
    public function getNumberBroughtByStudent()
    {
        return $this->number_brought_by_student;
    }

    /**
     * Set number_bought_by_school
     *
     * @param float $numberBoughtBySchool
     * @return StudentInventoryRequirement
     */
    public function setNumberBoughtBySchool($numberBoughtBySchool)
    {
        $this->number_bought_by_school = $numberBoughtBySchool;
        return $this;
    }

    /**
     * Get number_bought_by_school
     *
     * @return float 
     */
    public function getNumberBoughtBySchool()
    {
        return $this->number_bought_by_school;
    }

    /**
     * Set date_created
     *
     * @param datetime $dateCreated
     * @return StudentInventoryRequirement
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
     * @return StudentInventoryRequirement
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
     * @return StudentInventoryRequirement
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
     * @return StudentInventoryRequirement
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
     * @return StudentInventoryRequirement
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
     * @return StudentInventoryRequirement
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
     * Set student_required_item
     *
     * @param Entities\StudentRequiredItem $studentRequiredItem
     * @return StudentInventoryRequirement
     */
    public function setStudentRequiredItem(\Entities\StudentRequiredItem $studentRequiredItem = null)
    {
        $this->student_required_item = $studentRequiredItem;
        return $this;
    }

    /**
     * Get student_required_item
     *
     * @return Entities\StudentRequiredItem 
     */
    public function getStudentRequiredItem()
    {
        return $this->student_required_item;
    }

    /**
     * Set transaction
     *
     * @param Entities\Transaction $transaction
     * @return StudentInventoryRequirement
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
     * @var boolean $was_paid
     */
    private $was_paid;


    /**
     * Set was_paid
     *
     * @param boolean $wasPaid
     * @return StudentInventoryRequirement
     */
    public function setWasPaid($wasPaid)
    {
        $this->was_paid = $wasPaid;
        return $this;
    }

    /**
     * Get was_paid
     *
     * @return boolean 
     */
    public function getWasPaid()
    {
        return $this->was_paid;
    }
}
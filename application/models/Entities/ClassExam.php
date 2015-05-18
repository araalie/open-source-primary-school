<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entities\ClassExam
 */
class ClassExam
{
    /**
     * @var integer $id
     */
    private $id;

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
     * @var Entities\Exam
     */
    private $exam;

    /**
     * @var Entities\ClassInstance
     */
    private $class_instance;

    /**
     * @var Entities\ExamStatus
     */
    private $exam_status;

    /**
     * @var Entities\GradingMode
     */
    private $grading_mode;


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
     * Set date_created
     *
     * @param datetime $dateCreated
     * @return ClassExam
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
     * @return ClassExam
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
     * @return ClassExam
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
     * Set exam
     *
     * @param Entities\Exam $exam
     * @return ClassExam
     */
    public function setExam(\Entities\Exam $exam = null)
    {
        $this->exam = $exam;
        return $this;
    }

    /**
     * Get exam
     *
     * @return Entities\Exam 
     */
    public function getExam()
    {
        return $this->exam;
    }

    /**
     * Set class_instance
     *
     * @param Entities\ClassInstance $classInstance
     * @return ClassExam
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
     * Set exam_status
     *
     * @param Entities\ExamStatus $examStatus
     * @return ClassExam
     */
    public function setExamStatus(\Entities\ExamStatus $examStatus = null)
    {
        $this->exam_status = $examStatus;
        return $this;
    }

    /**
     * Get exam_status
     *
     * @return Entities\ExamStatus 
     */
    public function getExamStatus()
    {
        return $this->exam_status;
    }

    /**
     * Set grading_mode
     *
     * @param Entities\GradingMode $gradingMode
     * @return ClassExam
     */
    public function setGradingMode(\Entities\GradingMode $gradingMode = null)
    {
        $this->grading_mode = $gradingMode;
        return $this;
    }

    /**
     * Get grading_mode
     *
     * @return Entities\GradingMode 
     */
    public function getGradingMode()
    {
        return $this->grading_mode;
    }
}
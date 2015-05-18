<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entities\SubjectExamGrading
 */
class SubjectExamGrading
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
     * @var Entities\Grading
     */
    private $grading;

    /**
     * @var Entities\SubjectInstance
     */
    private $subject_instance;

    /**
     * @var Entities\Exam
     */
    private $exam;


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
     * @return SubjectExamGrading
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
     * @return SubjectExamGrading
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
     * @return SubjectExamGrading
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
     * @return SubjectExamGrading
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

    /**
     * Set subject_instance
     *
     * @param Entities\SubjectInstance $subjectInstance
     * @return SubjectExamGrading
     */
    public function setSubjectInstance(\Entities\SubjectInstance $subjectInstance = null)
    {
        $this->subject_instance = $subjectInstance;
        return $this;
    }

    /**
     * Get subject_instance
     *
     * @return Entities\SubjectInstance 
     */
    public function getSubjectInstance()
    {
        return $this->subject_instance;
    }

    /**
     * Set exam
     *
     * @param Entities\Exam $exam
     * @return SubjectExamGrading
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
}
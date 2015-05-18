<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entities\ExamDone
 */
class ExamDone
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var float $marks
     */
    private $marks;

    /**
     * @var string $grade
     */
    private $grade;

    /**
     * @var string $comments
     */
    private $comments;

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
     * @var Entities\SubjectInstance
     */
    private $subject_instance;

    /**
     * @var Entities\Student
     */
    private $student;

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
     * Set marks
     *
     * @param float $marks
     * @return ExamDone
     */
    public function setMarks($marks)
    {
        $this->marks = $marks;
        return $this;
    }

    /**
     * Get marks
     *
     * @return float 
     */
    public function getMarks()
    {
        return $this->marks;
    }

    /**
     * Set grade
     *
     * @param string $grade
     * @return ExamDone
     */
    public function setGrade($grade)
    {
        $this->grade = $grade;
        return $this;
    }

    /**
     * Get grade
     *
     * @return string 
     */
    public function getGrade()
    {
        return $this->grade;
    }

    /**
     * Set comments
     *
     * @param string $comments
     * @return ExamDone
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
     * Set date_created
     *
     * @param datetime $dateCreated
     * @return ExamDone
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
     * @return ExamDone
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
     * @return ExamDone
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
     * Set subject_instance
     *
     * @param Entities\SubjectInstance $subjectInstance
     * @return ExamDone
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
     * Set student
     *
     * @param Entities\Student $student
     * @return ExamDone
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
     * Set exam
     *
     * @param Entities\Exam $exam
     * @return ExamDone
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
     * @var Entities\ExamDoneStatus
     */
    private $exam_done_status;


    /**
     * Set exam_done_status
     *
     * @param Entities\ExamDoneStatus $examDoneStatus
     * @return ExamDone
     */
    public function setExamDoneStatus(\Entities\ExamDoneStatus $examDoneStatus = null)
    {
        $this->exam_done_status = $examDoneStatus;
        return $this;
    }

    /**
     * Get exam_done_status
     *
     * @return Entities\ExamDoneStatus 
     */
    public function getExamDoneStatus()
    {
        return $this->exam_done_status;
    }
}
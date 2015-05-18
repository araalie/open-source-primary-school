<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entities\TeacherClassHistory
 */
class TeacherClassHistory
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var string $comment
     */
    private $comment;

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
     * @var Entities\StudySubject
     */
    private $study_subject;

    /**
     * @var Entities\SchoolStaff
     */
    private $teacher;

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
     * Set comment
     *
     * @param string $comment
     * @return TeacherClassHistory
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
        return $this;
    }

    /**
     * Get comment
     *
     * @return string 
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set date_created
     *
     * @param datetime $dateCreated
     * @return TeacherClassHistory
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
     * @return TeacherClassHistory
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
     * @return TeacherClassHistory
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
     * @return TeacherClassHistory
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
     * Set study_subject
     *
     * @param Entities\StudySubject $studySubject
     * @return TeacherClassHistory
     */
    public function setStudySubject(\Entities\StudySubject $studySubject = null)
    {
        $this->study_subject = $studySubject;
        return $this;
    }

    /**
     * Get study_subject
     *
     * @return Entities\StudySubject 
     */
    public function getStudySubject()
    {
        return $this->study_subject;
    }

    /**
     * Set teacher
     *
     * @param Entities\SchoolStaff $teacher
     * @return TeacherClassHistory
     */
    public function setTeacher(\Entities\SchoolStaff $teacher = null)
    {
        $this->teacher = $teacher;
        return $this;
    }

    /**
     * Get teacher
     *
     * @return Entities\SchoolStaff 
     */
    public function getTeacher()
    {
        return $this->teacher;
    }

    /**
     * Set term
     *
     * @param Entities\Term $term
     * @return TeacherClassHistory
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
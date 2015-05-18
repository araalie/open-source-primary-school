<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entities\SubjectInstance
 */
class SubjectInstance
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var string $name
     */
    private $name;

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
     * @var Entities\SubjectInstanceStatus
     */
    private $subject_instance_status;

    /**
     * @var Entities\StudySubject
     */
    private $study_subject;

    /**
     * @var Entities\ClassInstance
     */
    private $class_instance;

    /**
     * @var Entities\SchoolStaff
     */
    private $teacher;

    /**
     * @var Entities\Grading
     */
    private $grading;


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
     * Set name
     *
     * @param string $name
     * @return SubjectInstance
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set date_created
     *
     * @param datetime $dateCreated
     * @return SubjectInstance
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
     * @return SubjectInstance
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
     * @return SubjectInstance
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
     * Set subject_instance_status
     *
     * @param Entities\SubjectInstanceStatus $subjectInstanceStatus
     * @return SubjectInstance
     */
    public function setSubjectInstanceStatus(\Entities\SubjectInstanceStatus $subjectInstanceStatus = null)
    {
        $this->subject_instance_status = $subjectInstanceStatus;
        return $this;
    }

    /**
     * Get subject_instance_status
     *
     * @return Entities\SubjectInstanceStatus 
     */
    public function getSubjectInstanceStatus()
    {
        return $this->subject_instance_status;
    }

    /**
     * Set study_subject
     *
     * @param Entities\StudySubject $studySubject
     * @return SubjectInstance
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
     * Set class_instance
     *
     * @param Entities\ClassInstance $classInstance
     * @return SubjectInstance
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
     * Set teacher
     *
     * @param Entities\SchoolStaff $teacher
     * @return SubjectInstance
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
     * Set grading
     *
     * @param Entities\Grading $grading
     * @return SubjectInstance
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
}
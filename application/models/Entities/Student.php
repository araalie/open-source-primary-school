<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entities\Student
 */
class Student
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var string $student_number
     */
    private $student_number;

    /**
     * @var string $first_name
     */
    private $first_name;

    /**
     * @var string $surname
     */
    private $surname;

    /**
     * @var string $other_names
     */
    private $other_names;

    /**
     * @var date $date_of_birth
     */
    private $date_of_birth;

    /**
     * @var string $gender
     */
    private $gender;

    /**
     * @var integer $year_enrolled
     */
    private $year_enrolled;

    /**
     * @var integer $year_completed
     */
    private $year_completed;

    /**
     * @var string $telephone
     */
    private $telephone;

    /**
     * @var string $address
     */
    private $address;

    /**
     * @var string $email
     */
    private $email;

    /**
     * @var string $remarks
     */
    private $remarks;

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
     * @var Entities\House
     */
    private $house;

    /**
     * @var Entities\StudentStatus
     */
    private $student_status;

    /**
     * @var Entities\ClassInstance
     */
    private $class_instance;

    /**
     * @var Entities\FeesProfile
     */
    private $fees_profile;


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
     * Set student_number
     *
     * @param string $studentNumber
     * @return Student
     */
    public function setStudentNumber($studentNumber)
    {
        $this->student_number = $studentNumber;
        return $this;
    }

    /**
     * Get student_number
     *
     * @return string 
     */
    public function getStudentNumber()
    {
        return $this->student_number;
    }

    /**
     * Set first_name
     *
     * @param string $firstName
     * @return Student
     */
    public function setFirstName($firstName)
    {
        $this->first_name = $firstName;
        return $this;
    }

    /**
     * Get first_name
     *
     * @return string 
     */
    public function getFirstName()
    {
        return $this->first_name;
    }

    /**
     * Set surname
     *
     * @param string $surname
     * @return Student
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;
        return $this;
    }

    /**
     * Get surname
     *
     * @return string 
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * Set other_names
     *
     * @param string $otherNames
     * @return Student
     */
    public function setOtherNames($otherNames)
    {
        $this->other_names = $otherNames;
        return $this;
    }

    /**
     * Get other_names
     *
     * @return string 
     */
    public function getOtherNames()
    {
        return $this->other_names;
    }

    /**
     * Set date_of_birth
     *
     * @param date $dateOfBirth
     * @return Student
     */
    public function setDateOfBirth($dateOfBirth)
    {
        $this->date_of_birth = $dateOfBirth;
        return $this;
    }

    /**
     * Get date_of_birth
     *
     * @return date 
     */
    public function getDateOfBirth()
    {
        return $this->date_of_birth;
    }

    /**
     * Set gender
     *
     * @param string $gender
     * @return Student
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
        return $this;
    }

    /**
     * Get gender
     *
     * @return string 
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set year_enrolled
     *
     * @param integer $yearEnrolled
     * @return Student
     */
    public function setYearEnrolled($yearEnrolled)
    {
        $this->year_enrolled = $yearEnrolled;
        return $this;
    }

    /**
     * Get year_enrolled
     *
     * @return integer 
     */
    public function getYearEnrolled()
    {
        return $this->year_enrolled;
    }

    /**
     * Set year_completed
     *
     * @param integer $yearCompleted
     * @return Student
     */
    public function setYearCompleted($yearCompleted)
    {
        $this->year_completed = $yearCompleted;
        return $this;
    }

    /**
     * Get year_completed
     *
     * @return integer 
     */
    public function getYearCompleted()
    {
        return $this->year_completed;
    }

    /**
     * Set telephone
     *
     * @param string $telephone
     * @return Student
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;
        return $this;
    }

    /**
     * Get telephone
     *
     * @return string 
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * Set address
     *
     * @param string $address
     * @return Student
     */
    public function setAddress($address)
    {
        $this->address = $address;
        return $this;
    }

    /**
     * Get address
     *
     * @return string 
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Student
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set remarks
     *
     * @param string $remarks
     * @return Student
     */
    public function setRemarks($remarks)
    {
        $this->remarks = $remarks;
        return $this;
    }

    /**
     * Get remarks
     *
     * @return string 
     */
    public function getRemarks()
    {
        return $this->remarks;
    }

    /**
     * Set date_created
     *
     * @param datetime $dateCreated
     * @return Student
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
     * @return Student
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
     * @return Student
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
     * @return Student
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
     * Set house
     *
     * @param Entities\House $house
     * @return Student
     */
    public function setHouse(\Entities\House $house = null)
    {
        $this->house = $house;
        return $this;
    }

    /**
     * Get house
     *
     * @return Entities\House 
     */
    public function getHouse()
    {
        return $this->house;
    }

    /**
     * Set student_status
     *
     * @param Entities\StudentStatus $studentStatus
     * @return Student
     */
    public function setStudentStatus(\Entities\StudentStatus $studentStatus = null)
    {
        $this->student_status = $studentStatus;
        return $this;
    }

    /**
     * Get student_status
     *
     * @return Entities\StudentStatus 
     */
    public function getStudentStatus()
    {
        return $this->student_status;
    }

    /**
     * Set class_instance
     *
     * @param Entities\ClassInstance $classInstance
     * @return Student
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
     * Set fees_profile
     *
     * @param Entities\FeesProfile $feesProfile
     * @return Student
     */
    public function setFeesProfile(\Entities\FeesProfile $feesProfile = null)
    {
        $this->fees_profile = $feesProfile;
        return $this;
    }

    /**
     * Get fees_profile
     *
     * @return Entities\FeesProfile 
     */
    public function getFeesProfile()
    {
        return $this->fees_profile;
    }
}
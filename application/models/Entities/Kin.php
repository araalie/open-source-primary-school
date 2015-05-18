<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entities\Kin
 */
class Kin
{
    /**
     * @var integer $id
     */
    private $id;

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
     * @var string $gender
     */
    private $gender;

    /**
     * @var string $email
     */
    private $email;

    /**
     * @var string $telephone
     */
    private $telephone;

    /**
     * @var string $address
     */
    private $address;

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
     * @var Entities\KinType
     */
    private $kin_type;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    private $parent_guardians;

    public function __construct()
    {
        $this->parent_guardians = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
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
     * Set first_name
     *
     * @param string $firstName
     * @return Kin
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
     * @return Kin
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
     * @return Kin
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
     * Set gender
     *
     * @param string $gender
     * @return Kin
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
     * Set email
     *
     * @param string $email
     * @return Kin
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
     * Set telephone
     *
     * @param string $telephone
     * @return Kin
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
     * @return Kin
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
     * Set remarks
     *
     * @param string $remarks
     * @return Kin
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
     * @return Kin
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
     * @return Kin
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
     * @return Kin
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
     * Set kin_type
     *
     * @param Entities\KinType $kinType
     * @return Kin
     */
    public function setKinType(\Entities\KinType $kinType = null)
    {
        $this->kin_type = $kinType;
        return $this;
    }

    /**
     * Get kin_type
     *
     * @return Entities\KinType 
     */
    public function getKinType()
    {
        return $this->kin_type;
    }

    /**
     * Add parent_guardians
     *
     * @param Entities\Kin $parentGuardians
     * @return Kin
     */
    public function addKin(\Entities\Kin $parentGuardians)
    {
        $this->parent_guardians[] = $parentGuardians;
        return $this;
    }

    /**
     * Get parent_guardians
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getParentGuardians()
    {
        return $this->parent_guardians;
    }
}
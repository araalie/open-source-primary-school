<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entities\AppOption
 */
class AppOption
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var string $key_name
     */
    private $key_name;

    /**
     * @var string $value
     */
    private $value;

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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set key_name
     *
     * @param string $keyName
     * @return AppOption
     */
    public function setKeyName($keyName)
    {
        $this->key_name = $keyName;
        return $this;
    }

    /**
     * Get key_name
     *
     * @return string 
     */
    public function getKeyName()
    {
        return $this->key_name;
    }

    /**
     * Set value
     *
     * @param string $value
     * @return AppOption
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    /**
     * Get value
     *
     * @return string 
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set date_created
     *
     * @param datetime $dateCreated
     * @return AppOption
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
     * @return AppOption
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
     * @return AppOption
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
}
<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entities\Holiday
 */
class Holiday
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
     * @var string $description
     */
    private $description;

    /**
     * @var datetime $date_began
     */
    private $date_began;

    /**
     * @var datetime $date_ended
     */
    private $date_ended;

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
     * Set name
     *
     * @param string $name
     * @return Holiday
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
     * Set description
     *
     * @param string $description
     * @return Holiday
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set date_began
     *
     * @param datetime $dateBegan
     * @return Holiday
     */
    public function setDateBegan($dateBegan)
    {
        $this->date_began = $dateBegan;
        return $this;
    }

    /**
     * Get date_began
     *
     * @return datetime 
     */
    public function getDateBegan()
    {
        return $this->date_began;
    }

    /**
     * Set date_ended
     *
     * @param datetime $dateEnded
     * @return Holiday
     */
    public function setDateEnded($dateEnded)
    {
        $this->date_ended = $dateEnded;
        return $this;
    }

    /**
     * Get date_ended
     *
     * @return datetime 
     */
    public function getDateEnded()
    {
        return $this->date_ended;
    }

    /**
     * Set date_created
     *
     * @param datetime $dateCreated
     * @return Holiday
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
     * @return Holiday
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
     * @return Holiday
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
     * Set term
     *
     * @param Entities\Term $term
     * @return Holiday
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
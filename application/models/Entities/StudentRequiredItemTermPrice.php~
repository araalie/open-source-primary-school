<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entities\StudentRequiredItemTermPrice
 */
class StudentRequiredItemTermPrice
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var float $price
     */
    private $price;

    /**
     * @var float $transport_cost
     */
    private $transport_cost;

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
     * @var Entities\StudentRequiredItem
     */
    private $student_required_item;

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
     * Set price
     *
     * @param float $price
     * @return StudentRequiredItemTermPrice
     */
    public function setPrice($price)
    {
        $this->price = $price;
        return $this;
    }

    /**
     * Get price
     *
     * @return float 
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set transport_cost
     *
     * @param float $transportCost
     * @return StudentRequiredItemTermPrice
     */
    public function setTransportCost($transportCost)
    {
        $this->transport_cost = $transportCost;
        return $this;
    }

    /**
     * Get transport_cost
     *
     * @return float 
     */
    public function getTransportCost()
    {
        return $this->transport_cost;
    }

    /**
     * Set date_created
     *
     * @param datetime $dateCreated
     * @return StudentRequiredItemTermPrice
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
     * @return StudentRequiredItemTermPrice
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
     * @return StudentRequiredItemTermPrice
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
     * Set student_required_item
     *
     * @param Entities\StudentRequiredItem $studentRequiredItem
     * @return StudentRequiredItemTermPrice
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
     * Set term
     *
     * @param Entities\Term $term
     * @return StudentRequiredItemTermPrice
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
<?php

namespace Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entities\Log
 */
class Log
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var integer $type
     */
    private $type;

    /**
     * @var string $object_type
     */
    private $object_type;

    /**
     * @var integer $object_id
     */
    private $object_id;

    /**
     * @var string $username
     */
    private $username;

    /**
     * @var string $client_ip_address
     */
    private $client_ip_address;

    /**
     * @var string $narrative
     */
    private $narrative;

    /**
     * @var datetime $date_created
     */
    private $date_created;


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
     * Set type
     *
     * @param integer $type
     * @return Log
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Get type
     *
     * @return integer 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set object_type
     *
     * @param string $objectType
     * @return Log
     */
    public function setObjectType($objectType)
    {
        $this->object_type = $objectType;
        return $this;
    }

    /**
     * Get object_type
     *
     * @return string 
     */
    public function getObjectType()
    {
        return $this->object_type;
    }

    /**
     * Set object_id
     *
     * @param integer $objectId
     * @return Log
     */
    public function setObjectId($objectId)
    {
        $this->object_id = $objectId;
        return $this;
    }

    /**
     * Get object_id
     *
     * @return integer 
     */
    public function getObjectId()
    {
        return $this->object_id;
    }

    /**
     * Set username
     *
     * @param string $username
     * @return Log
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set client_ip_address
     *
     * @param string $clientIpAddress
     * @return Log
     */
    public function setClientIpAddress($clientIpAddress)
    {
        $this->client_ip_address = $clientIpAddress;
        return $this;
    }

    /**
     * Get client_ip_address
     *
     * @return string 
     */
    public function getClientIpAddress()
    {
        return $this->client_ip_address;
    }

    /**
     * Set narrative
     *
     * @param string $narrative
     * @return Log
     */
    public function setNarrative($narrative)
    {
        $this->narrative = $narrative;
        return $this;
    }

    /**
     * Get narrative
     *
     * @return string 
     */
    public function getNarrative()
    {
        return $this->narrative;
    }

    /**
     * Set date_created
     *
     * @param datetime $dateCreated
     * @return Log
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
}
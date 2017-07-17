<?php
namespace ClientBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="ClientBundle\Repository\HistoricRepository")
 */
class Historic
{

	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;
	/**
	 *
	 * @ORM\ManyToOne(targetEntity="\ClientBundle\Entity\Client")
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $client;
	/**
	 * @ORM\Column(type="string",length=128, nullable=false)
	 *
	 */
	private $type;
	/**
	 * @ORM\Column(type="decimal", nullable=false)
	 *
	 */
	private $eventid;
	/**
	 *@ORM\Column(type="datetime", nullable=false)
	 *
	 */
	private $date;

        
    public function __construct() {
        $this->date =new \DateTime();
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
     * Set type
     *
     * @param string $type
     *
     * @return Historic
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set eventId
     *
     * @param string $eventId
     *
     * @return Historic
     */
    public function setEventId($eventId)
    {
        $this->eventid = $eventId;

        return $this;
    }

    /**
     * Get eventId
     *
     * @return string
     */
    public function getEventId()
    {
        return $this->eventid;
    }

    /**
     * Set date
     *
     * @param string $date
     *
     * @return Historic
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return string
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set clientid
     *
     * @param \ClientBundle\Entity\Client $clientid
     *
     * @return Historic
     */
    public function setClient(\ClientBundle\Entity\Client $client)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get clientid
     *
     * @return \ClientBundle\Entity\Client
     */
    public function getClient()
    {
        return $this->client;
    }
    
    public function toArray(){
    	
    	$return=array(
    			'id'=>$this->id,
    			'client'=>$this->client->toArray(),
    			'type'=>$this->type,
    			'eventid'=>$this->eventid,
    			'date'=>$this->date,
    			);
    	
    	return $return;
    	
    }
}

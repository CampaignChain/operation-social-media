<?php

namespace CampaignChain\Operation\SocialMediaBundle\Entity;

use CampaignChain\CoreBundle\Entity\Meta;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="campaignchain_operation_social_media_schedule")
 */
class SocialMediaSchedule extends Meta
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToOne(targetEntity="CampaignChain\CoreBundle\Entity\Operation", cascade={"persist"})
     */
    protected $operation;

    /**
     * @ORM\ManyToMany(targetEntity="CampaignChain\CoreBundle\Entity\Location")
     * @ORM\JoinTable(name="campaignchain_operation_social_media_schedule_location",
     *      joinColumns={@ORM\JoinColumn(name="schedule_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="location_id", referencedColumnName="id", unique=false)}
     *      )
     */
    protected $locations;

    /**
     * @ORM\Column(type="text")
     */
    protected $message;

    public function __construct()
    {
        $this->locations = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set operation
     *
     * @param \CampaignChain\CoreBundle\Entity\Operation $operation
     * @return Status
     */
    public function setOperation(\CampaignChain\CoreBundle\Entity\Operation $operation = null)
    {
        $this->operation = $operation;

        return $this;
    }

    /**
     * Get operation
     *
     * @return \CampaignChain\CoreBundle\Entity\Operation
     */
    public function getOperation()
    {
        return $this->operation;
    }

    /**
     * Add location
     *
     * @param \CampaignChain\CoreBundle\Entity\Location $locations
     * @return Location
     */
    public function addLocation(\CampaignChain\CoreBundle\Entity\Location $location)
    {
        $this->locations[] = $location;

        return $this;
    }

    /**
     * Add locations
     *
     * @param \CampaignChain\CoreBundle\Entity\Location $locations
     * @return Location
     */
    public function setLocations(ArrayCollection $locations)
    {
        $this->locations = $locations;

        return $this;
    }

    /**
     * Remove location
     *
     * @param \CampaignChain\CoreBundle\Entity\Location $locations
     */
    public function removeLocation(\CampaignChain\CoreBundle\Entity\Location $location)
    {
        $this->locations->removeElement($location);
    }

    /**
     * Get locations
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLocations()
    {
        return $this->locations;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }
}

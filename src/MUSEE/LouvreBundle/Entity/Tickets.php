<?php

namespace MUSEE\LouvreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use MUSEE\LouvreBundle\Entity\Orders;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;


/**
 * Tickets
 *
 * @ORM\Table(name="tickets")
 * @ORM\Entity(repositoryClass="MUSEE\LouvreBundle\Repository\TicketsRepository")
 */
class Tickets
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
	
	/**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="surname", type="string", length=255)
     */
    private $surname;
	
	
    /**
     * @var \Date
     *
     * @ORM\Column(name="date_birth", type="date")
     */
    private $dateBirth;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=255)
     */
    private $country;
	
	/**
     * @var bool
     *
     * @ORM\Column(name="reduction", type="boolean")
     */
    private $reduction;
	
	/**
     * @ORM\ManyToOne(targetEntity="MUSEE\LouvreBundle\Entity\Orders", inversedBy="ticket")
     * @ORM\JoinColumn()
     */
    private $order;
	
    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Tickets
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
     * Set surname
     *
     * @param string $surname
     *
     * @return Tickets
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
     * Set dateBirth
     *
     * @param string $dateBirth
     *
     * @return Ticket
     */
    public function setDateBirth($dateBirth)
    {
        $this->dateBirth = $dateBirth;

        return $this;
    }

    /**
     * Get dateBirth
     *
     * @return string
     */
    public function getDateBirth()
    {
        return $this->dateBirth;
    }

	 /**
     * Set country
     *
     * @param string $country
     *
     * @return Tickets
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

	/**
     * Set order
     *
     * @param \MUSEE\LouvreBundle\Entity\Orders $order
     *
     * @return Tickets
     */
    public function setOrder(\MUSEE\LouvreBundle\Entity\Orders $order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get order
     *
     * @return MUSEE\LouvreBundle\Entity\Orders
     */
    public function getOrder()
    {
        return $this->order;
    }


    /**
     * Set reduction
     *
     * @param boolean $reduction
     *
     * @return Tickets
     */
    public function setReduction($reduction)
    {
        $this->reduction = $reduction;

        return $this;
    }

    /**
     * Get reduction
     *
     * @return boolean
     */
    public function getReduction()
    {
        return $this->reduction;
    }
}

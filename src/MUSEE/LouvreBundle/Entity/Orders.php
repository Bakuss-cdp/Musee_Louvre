<?php

namespace MUSEE\LouvreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use MUSEE\LouvreBundle\Entity\Tickets;
use MUSEE\LouvreBundle\MUSEELouvreBundle;
use Symfony\Component\Validator\Constraints as Assert;
use MUSEE\LouvreBundle\Validator\closedDays;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Orders
 *
 * @ORM\Table(name="orders")
 * @ORM\Entity(repositoryClass="MUSEE\LouvreBundle\Repository\OrdersRepository")
 */
class Orders
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
     * @ORM\Column(name="email", type="string", length=255)
     * 
	 * @Assert\NotBlank (message="Renseigner votre adresse mail")
     * @Assert\Email (message="Format de votre email est incorrect!")
     */
    private $email;
	
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="ordersDate", type="datetime")
     * @Assert\DateTime()
     */
    private $orderDate;

	 /**
     * @var \DateTime
     *
     * @ORM\Column(name="visitDate", type="date")
	 * @Assert\Date (message="La date doit être au format JJ/MM/AAAA")
     * @Assert\NotBlank (message="Choisissez une date de visite")
     * @Assert\GreaterThan ("yesterday", message="Vous ne pouvez pas choisir une date passée.")
     * @closedDays()
     */
    private $visitDate;

    /**
     * @var string
     *
     * @ORM\Column(name="typeReservation", type="string", length=25)
     */
    private $typeReservation;

	/**
     * @var int
     *
     * @ORM\Column(name="nombrePlaces", type="integer")
     */
    private $nombrePlace;
	
	/**
     * @var int
     *
     * @ORM\Column(name="prices", type="integer")
     */
    private $price;
	
	/**
     * @var string
     *
     * @ORM\Column(name="condit", type="string", length=255)
     *
     */
    private $condito;
	
    /**
     * @var ArrayCollection $ticket
     *
     * @ORM\OneToMany(targetEntity="MUSEE\LouvreBundle\Entity\Tickets", mappedBy="order", cascade={"persist"})
     */
    private $ticket;

	public function __construct()
    {
        $this->orderDate = new \Datetime();
        $this->ticket = new ArrayCollection();
        // $this->$visitDate = new \Datetime('now');
    }
	
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
     * Set email
     *
     * @param string $email
     *
     * @return Orders
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
     * Set orderDate
     *
     * @param \DateTime $orderDate
     *
     * @return Orders
     */
    public function setOrderDate($orderDate)
    {
        $this->orderDate = $orderDate;

        return $this;
    }

    /**
     * Get orderDate
     *
     * @return \DateTime
     */
    public function getOrderdate()
    {
        return $this->orderDate;
    }

    /**
     * Set visitDate
     *
     * @param \DateTime $visitDate
     *
     * @return Orders
     */
    public function setVisitDate($visitDate)
    {
        $this->visitDate = $visitDate;

        return $this;
    }

    /**
     * Get visitDate
     *
     * @return \DateTime
     */
    public function getVisitDate()
    {
        return $this->visitDate;
    }

	/**
     * Set typeReservation
     *
     * @param boolean $typeReservation
     *
     * @return Orders
     */
    public function setTypeReservation($typeReservation)
    {
        $this->typeReservation = $typeReservation;

        return $this;
    }

    /**
     * Get typeReservation
     *
     * @return bool
     */
    public function getTypeReservation()
    {
        return $this->typeReservation;
    }
	
    /**
     * Set nombrePlace
     *
     * @param integer $nombrePlace
     *
     * @return Orders
     */
    public function setNombrePlace($nombrePlace)
    {
        $this->nombrePlace = $nombrePlace;

        return $this;
    }

    /**
     * Get nombrePlace
     *
     * @return integer
     */
    public function getNombrePlace()
    {
        return $this->nombrePlace;
    }
	
	/**
     * Set price
     *
     * @param integer $price
     *
     * @return Orders
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }
	
    /**
     * Get price
     *
     * @return int price
     */
    public function getPrice()
    {
        return $this->price;
    }
	
	
	/**
     * Set condito
     *
     * @param string $condito
     *
     * @return Orders
     */
    public function setCondito($condito)
    {
        $this->condito = $condito;

        return $this;
    }

    /**
     * Get condito
     *
     * @return string
     */
    public function getCondito()
    {
        return $this->condito;
    }
	
	/**
     * Get Ticket
     *
     * @return object
     */
    public function getTicket()
    {
        return $this->ticket;
    }
	
	
	/**
     * Add ticket
     *
     * @param MUSEE\LouvreBundle\Entity\Tickets $ticket
     *
     * @return Orders
     */
    public function addTicket(Tickets $ticket)
    {
        $this->ticket[] = $ticket;
        $ticket->setOrder($this);

        return $this;
    }

    /**
     * Remove ticket
     *
     * @param MUSEE\LouvreBundle\Entity\Tickets $ticket
     *
     */
    public function removeTicket(Tickets $ticket)
    {
        $this->ticket->removeElement($ticket);
    }
	
	
	/**
     * Le billet journée ne peut pas être payé après 14H00
     * @Assert\Callback
     */
	// 
    public function validate(ExecutionContextInterface $context)
    {
        $today = new \DateTime("now");
        $time = $today->format('H');
        $today->setTime(0, 0, 0);
			if( $this->getVisitDate() == $today && $time >=12 && $this->getTypeReservation() == 'Journée')
			{
				$context->buildViolation('Le billet journée ne peut pas être commandé aprés 14H00')
					->addViolation();
			}
    }
	//
}

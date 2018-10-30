<?php

namespace MUSEE\LouvreBundle\Utilitaires;


use MUSEE\LouvreBundle\Entity\Orders;
use MUSEE\LouvreBundle\Entity\Tickets;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Validator\Constraints\DateTime;

class OrdersManager 
{
	protected $em;
    protected $session;
	const MAX_PLACES = 1000;

    /**
     * OrderManager constructor.
     * @param SessionInterface $session
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em, SessionInterface $session)
    {
        $this->em = $em;
        $this->session = $session;  
    }
	
    /**
     * @param Orders $order
     */
    public function availability(Orders $order)
    {
        $visitDate = $order->getVisitDate();
		$tickets = $order->getTicket();
		$ticketsNumber = count($tickets);
		$numberTicketsSold = "";
		$available ="";
		
        $ticktsOfDate = $this->em 
		->getRepository('MUSEELouvreBundle:Orders')
		->findByVisit_date($visitDate);
		
        foreach($ticktsOfDate as $ticketsSold)
		{
			$nbtickets = $ticketsSold->getNombrePlace(); 
			$numberTicketsSold += $nbtickets;
		}		
        
		$available = self::MAX_PLACES - $numberTicketsSold;
			
			if($available >= $ticketsNumber)
			{
			 //
			  $laDate = $order->getVisitDate();  
			  $datedeVisite = date_format($laDate, 'd-m-Y');
			  $order->setVisitDate(new \DateTime($datedeVisite));
			
			$this->session->set('orders', $order);

			 $this->session->getFlashBag()->add('Info','Le Nombre de places restant pour le: ' . $datedeVisite . ' est '. $available . '.');
			}
			
			else
			{
				$ticket = $order->getTicket()->last();
				$order->removeTicket($ticket);
				$this->session->getFlashBag()->add('Info!:',' Vous devez enlever le dernier visiteur pour la date du: ' . $datedeVisite . '.');				
			}	
    }
	
}

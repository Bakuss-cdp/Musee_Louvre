<?php
namespace MUSEE\LouvreBundle\Utilitaires;
use Doctrine\ORM\EntityManagerInterface;
use MUSEE\LouvreBundle\Entity\Orders;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Stripe\Charge;
use Stripe\Stripe;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class LouvreTransaction
{
	private $stripe_key;
	protected $session;
	protected $em;
	
	/**
	* LouvreTransaction constructor
	* @param Session $session
	* @param EntityManagerInterface $em
	*/
	public function __construct($stripe_key, Session $session, EntityManagerInterface $em)
	{
		$this->stripe_key = $stripe_key;
		$this->session = $session;
		$this->em = $em;
	}
	
	public function payment(Orders $order, $token, $total)
	{
		Stripe::setApiKey($this->stripe_key);
		try
		{	
			\Stripe\Charge::create(array(
			'amount'      => $total,
			'currency'    => 'eur',
			'source'      => $token,
			"capture"    => false,
			'description' => 'Achat de billet visite du Musée louvre',
			));	
			$this->session->getFlashBag()->add('success',
			'Votre Paiement est passé, vous allez recevoir votre confirmation par mail');
		}
		catch (\Exception $e) 
		{
			$this->session->getFlashBag()->add('warning',
			'Paiement échoué');
			return false;
		}
		
		$this->sendOrder($order);
		return true;   
	}
	
	private function sendOrder(Orders $order) 
	{    		
		$this->em->persist($order);
		$this->em->flush();
		
		foreach ($order->getTicket() as $ticket) 
		{
			$ticket->setOrder($order);
			$this->em->persist($ticket);
		}
		
		$this->em->flush();
	}
}
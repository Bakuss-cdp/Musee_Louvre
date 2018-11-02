<?php
// src/MUSEE/LouvreBundle/Controller/MlouvreController.php
namespace MUSEE\LouvreBundle\Controller;
use MUSEE\LouvreBundle\Entity\Tickets;
use MUSEE\LouvreBundle\Entity\Orders;
use MUSEE\LouvreBundle\Entity\Advert;
use MUSEE\LouvreBundle\Form\OrdersType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Stripe\Stripe;
use MUSEE\LouvreBundle\Utilitaires\OrdersManager;

class MlouvreController extends Controller
{
	public function indexAction()
	{
		$session = $this->get('session');
		
		if ($session->has('orders')) 
		{
			$order = $session->get('orders');
			$content = $this->get('templating')->render('MUSEELouvreBundle:Mlouvre:index.html.twig', array(
			'orders' => $order ));
			return new Response($content);
		}
		
		else
		{
			$content = $this->get('templating')->render('MUSEELouvreBundle:Mlouvre:index.html.twig');
			return new Response($content);
		}
	}
	
	public function ticketAction(Request $request)
	{
		$session = $this->get('session');
		$order = new Orders();
		$form = $this->get('form.factory')->create(OrdersType::class, $order);			
		$form->handleRequest($request);
		
		if ($request->isMethod('POST') && $form->isValid()) 
		{				
			$manager = $this->container->get('musee_louvre.manager');
			$ordersManager = $manager->availability($order);
			
			if($ordersManager = true)	
			{
				return $this->redirectToRoute('musee_louvre_orders');
			}
			
		return $this->redirectToRoute('musee_louvre_ticket');
		}
		
		if ($session->has('orders')) 
		{
			$panier = $session->get('orders');
			return $this->render('MUSEELouvreBundle:Mlouvre:ticket.html.twig', array(
			'form' => $form->createView(),
			'orders' => $panier,
			));
		}
		
		return $this->render('MUSEELouvreBundle:Mlouvre:ticket.html.twig', array(
		'form' => $form->createView(),
		));										  
	}
	
	public function ordersAction()
	{
		$session = $this->get('session');
		
		if (!$session->has('orders')) 
		{
			throw $this->createNotFoundException("Aucune commande faite!");
		}
		
		$yourBill = $this->container->get('musee_louvre.bills');
		$order = $session->get('orders');
		$price = $yourBill->workout($order);
		
		return $this->render('MUSEELouvreBundle:Mlouvre:orders.html.twig', array(
		'orders' => $order,
		'price' => $price,
		));	
	}
	
	public function paymentAction()
	{
		if (!isset($_POST['stripeToken'])) 
		{
			throw $this->createNotFoundException("Erreur de page: Retourner à la page Achat billet!");
		}
		
		$token = $_POST['stripeToken'];
		$session = $this->get('session');
		$order = $session->get('orders');
		$billSum = $this->container->get('musee_louvre.bills');
		$price = $billSum->workout($order);
		$total = $price['total'] * 100 ;
		$condito = uniqid();
		$order->setPrice($price['total']);
		$order->setEmail($_POST['stripeEmail']);
		$order->setCondito($condito);
		
		$transaction =  $this->container->get('musee_louvre.transaction');
		$transDone = $transaction->payment($order, $token, $total);
		
		if($transDone = true)
		{
			$mail =  $this->container->get('musee_louvre.mail');
			$mail->sendVisitorOrder($order, $price);
			$session->set('finish', "ok");
			return $this->redirectToRoute("musee_louvre_recap");
		}
	}
	
	public function recapAction()
	{
		$session = $this->get('session');
		
		if ($session->has('finish')) 
		{
			$session->clear();
			return $this->render('MUSEELouvreBundle:Mlouvre:recap.html.twig', array('valider' => "ok"));
		}
		
		elseif ($session->has('orders')) 
		{
			$session->clear();
			return $this->render('MUSEELouvreBundle:Mlouvre:recap.html.twig', array('vider' => "ok"));
		}
		
		else 
		{
			throw $this->createNotFoundException("Allez à la page Accueil");
		}
	}
	
	public function contactAction()
	{
		$content = $this->get('templating')->render('MUSEELouvreBundle:Mlouvre:contact.html.twig');
		return new Response($content);
	}
}
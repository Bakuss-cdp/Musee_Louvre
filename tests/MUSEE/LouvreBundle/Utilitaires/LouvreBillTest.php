<?php
namespace Tests\MUSEE\LouvreBundle\Utilitaires;
use MUSEE\LouvreBundle\Entity\Orders;
use MUSEE\LouvreBundle\Entity\Tickets;
use MUSEE\LouvreBundle\Utilitaires\LouvreBills;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use PHPUnit\Framework\TestCase;

class LouvreBillsTest extends TestCase
{  
	public function testPrices()
	{
		$ThePrice = new LouvreBills();
		$order = new Orders();
		
		$ticket1 = new Tickets();		
		$ticket1->setDateBirth("1988-07-09");
		$ticket1->setReduction(0);
		
		$ticket2 = new  Tickets();
		$ticket2->setDateBirth("1972-12-12");
		$ticket2->setReduction(0);
		
		$order->addTicket($ticket1);
		$order->addTicket($ticket2);
		$samup = $ThePrice->workout($order);
		$this->assertEquals(32, $samup['total']);
	}
	
	public function testPricesWithKid()
	{
		$ThePrice = new LouvreBills();
		$order = new Orders();
		
		$ticket1 = new Tickets();
		$ticket1->setDateBirth("1977-07-09");
		$ticket1->setReduction(1);
		
		$ticket2 = new  Tickets();
		$ticket2->setDateBirth("1986-05-10");
		$ticket2->setReduction(0);
		
		$ticket3 = new  Tickets();
		$ticket3->setDateBirth("2016-04-07");
		$ticket3->setReduction(0);
		
		$order->addTicket($ticket1);
		$order->addTicket($ticket2);
		$order->addTicket($ticket3);
		$samup = $ThePrice->workout($order);
		$this->assertEquals(26, $samup['total']);
	}
}
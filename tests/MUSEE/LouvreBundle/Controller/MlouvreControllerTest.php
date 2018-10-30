<?php

// Tests/MUSEE/LouvreBundle/Controller/MlouvreControllerTest.php

namespace Tests\MUSEE\LouvreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HTTPFoundation\Response;


class MlouvreControllerTest extends WebTestCase
{
	public function testHomepageIsUp()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/home');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
	
	/**
     * @dataProvider urlProvider
     */
	 
	public function testUrl($url, $status)
    {
        $client = static::createClient();

        $crawler = $client->request('GET', $url);

        $this->assertEquals($status, $client->getResponse()->getStatusCode());
    }

    public function urlProvider()
    {
        return array(
            array('/ticket', 200),
            array('/orders', 302),
            array('/payment', 302),
        );
    }
	
	public function testTickets()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/ticket');
	
		$buttonCrawler = $crawler->selectButton('orders[save]');
        $formOrders = $buttonCrawler->form(array(
		    'orders[ticket][0][name]' => "Kumut",
            'orders[ticket][0][surname]' => "Imesh",
            'orders[ticket][0][dateBirth][day]' => '18',
			'orders[ticket][0][dateBirth][month]' => '11',
		    'orders[ticket][0][dateBirth][year]' => '1980',
			'orders[ticket][0][country]' => 'CI',
		    'orders[ticket][0][reduction]'=> '1',
			
			'orders[ticket][1][name]' => "Hamed",
            'orders[ticket][1][surname]' => "Samassi",
            'orders[ticket][1][dateBirth][day]' => '25',
			'orders[ticket][1][dateBirth][month]' => '05',
		    'orders[ticket][1][dateBirth][year]' => '1988',
			'orders[ticket][1][country]' => 'FR',
		    'orders[ticket][1][reduction]'=> '0',
			
            'orders[visitDate]' => "04/07/2018",
            'orders[typeReservation]' => "demi",
            'orders[nombrePlace]' => 2,
            'orders[email]' => "bakuss.cdp@gmail.com"
        ));
        $client->submit($formOrders);
        $crawler = $client->followRedirect();		
	
        $this->assertSame(1, $crawler->filter('html:contains("Email : bakuss.cdp@gmail.com")')->count());
        $this->assertSame(1, $crawler->filter('html:contains("Date de visite : 27/07/2018")')->count());
        $this->assertSame(1, $crawler->filter('html:contains("Prix total : 10 €")')->count());
        $this->assertSame(1, $crawler->filter('html:contains("Kumut Imesh")')->count());
        $this->assertSame(1, $crawler->filter('html:contains("Hamed Samassi")')->count());
    }
   	
}
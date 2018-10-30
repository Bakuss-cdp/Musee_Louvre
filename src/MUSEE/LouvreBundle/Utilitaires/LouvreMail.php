<?php

namespace MUSEE\LouvreBundle\Utilitaires;

use MUSEE\LouvreBundle\Entity\Orders;
use Symfony\Component\Templating\EngineInterface;


class LouvreMail
{
    protected $mailer;
    protected $templating;
    private $from = 'admin@mussedulouvre.com';
    private $reply = 'bakuss.cdp@gmail.com';
    private $name = 'Billetterie du Museée du Louvre';

    public function __construct(\Swift_Mailer $mailer, EngineInterface $templating)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
    }

    public function sendMsg($to, $subject, $body)
    {
        $mail = \Swift_Message::newInstance();
        $mail
            ->setFrom(array($this->from => $this->name))
            ->setTo($to)
            ->setSubject($subject)
            ->setBody($body)
            ->setReplyTo(array($this->reply => $this->reply))
            ->setContentType('text/html')
        ;
        $this->mailer->send($mail);
    }

    public function sendVisitorOrder(Orders $order, $price)
    {
		$DateDeVisite = $order->getVisitDate()->format('d-m-Y');
		$subject = "Confirmation de Billet: Votre visite au Musée du Louvre pour le ".$DateDeVisite;
        $template = 'Emails/validation.html.twig';
        $to = $order->getEMail();
        $body = $this->templating->render($template, array('orders' => $order, "price" => $price));
        $this->sendMsg($to, $subject, $body);
    }
}

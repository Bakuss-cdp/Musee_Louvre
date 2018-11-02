<?php

namespace MUSEE\LouvreBundle\Utilitaires;

use MUSEE\LouvreBundle\Entity\Orders;


class LouvreBills
{
    
    public function workout(Orders $order)
    {
        $total = 0;
        $number = 0;
		$tickets = $order->getTicket();
        $dateofOrder = $order->getOrderDate();

        foreach ($tickets as $ticket) 
		{
            $number ++;
            $dateofBirth = date("Y-m-d", strtotime($ticket->getDateBirth()));

            $thatDate = new \DateTime($dateofBirth);
            $ageGap = $thatDate->diff($dateofOrder);
            $ageinYears = $ageGap->format('%Y');
			
            if (intval($ageinYears) >= 12 && intval($ageinYears) < 60 && !$ticket->getReduction()) 
			{
                $price['ticket'.$number] = 16;
                $total += 16;
            }
			elseif(intval($ageinYears) <= 4) 
			{
                $price['ticket'.$number] = 0;
                $total += 0;
            }
			elseif($ticket->getReduction()) 
			{
                $price['ticket'.$number] = 10;
                $total += 10;
            }
			elseif (intval($ageinYears) >= 60) 
			{
                $price['ticket'.$number] = 12;
                $total += 12;
            }
			else 
			{
                $price['ticket'.$number] = 8;
                $total += 8;
            }
        }

        $price['total'] = $total;
		
		$ladatevisite  = $order->getVisitDate();
		$cettevisitdate = date("Y-m-d", strtotime('$order->getVisitDate()'));
		$cettedate = new \DateTime($cettevisitdate);

        $ageGap = $dateofOrder->diff($cettedate);		

        if ($ageGap->format('%Y') == 0 && $ageGap->format('%m') == 0 && $ageGap->format('%d') == 0 && $ageGap->format('%R%h') <= -12) 
		{
            $typeReservation = true;
            $order->setTypeReservation($typeReservation);
        }
		else 
		{
            $typeReservation = false;
            $order->setTypeReservation($typeReservation);
        }
		
        return $price;
    }
}

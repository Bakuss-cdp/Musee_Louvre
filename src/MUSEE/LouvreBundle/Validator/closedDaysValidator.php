<?php
namespace MUSEE\LouvreBundle\Validator;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraint;

class closedDaysValidator extends ConstraintValidator
{
	public function validate($value, Constraint $constraint)
	{
		$date = date_format($value, 'N');
		
		if ($date == 2) 
		{
			$this->context->buildViolation($constraint->message1)
			->addViolation();
		}
		
		if ($date == 7) 
		{
			$this->context->buildViolation($constraint->message2)
			->addViolation();
		}
		
		if($this->areDaysOff($value)) 
		{
			$day = date_format($value, 'd/m/Y');
			$this->context->buildViolation($constraint->message3, array("{{day}}" => $day))
			->addViolation();
		}
	}
	
	private function areDaysOff($date)
	{
		$theDate = date_format($date, 'U');
		$year = date('Y',$theDate);
		$holidays = array(
		mktime(0, 0, 0, 1,  1,  $year),  // 1er Jour de l'An
		mktime(0, 0, 0, 5,  1,  $year),  // Fête du travail
		mktime(0, 0, 0, 7, 14, $year),  // Fête Nationale
		mktime(0, 0, 0, 11, 1,  $year),  // Toussaint
		mktime(0, 0, 0, 12, 25, $year),  // Noel
		);
		
		return in_array($theDate, $holidays);
	}
}
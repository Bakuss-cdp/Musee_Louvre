<?php

namespace MUSEE\LouvreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use MUSEE\LouvreBundle\Form\TicketsType;

class OrdersType extends AbstractType
{
	 /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
		    //
             ->add('visitDate', DateType::class, array(
			   'label' => 'Date de visite',
			   'widget' => 'single_text',
			   'html5' => true,
			   'format' => 'yyyy-MM-dd',
			   'required' => 'required',
                ))

			->add('typeReservation', ChoiceType::class, array(
                'label_attr' => array(
                    'class' => 'col-sm-2 control-label'),
				'choices' => array(
												'Demi Journée' => 'demi', 
												'Journée' => 'journée'),	
                'label' => 'Type de Reservation')
            )
			
			->add('nombrePlace', ChoiceType::class, array(
                'label_attr' => array(
                    'class' => 'col-sm-2 control-label'),
				'choices' => array(
												'Une personne' => 'Une', 
												'Deux personnes' => 'Deux',
												'Trois personnes' => 'Trois',
												'Quatre personnes' => 'Quatre',
												'Cinq personnes' => 'Cinq'),	
                'label' => 'Nombre de Places')
            )
			->add('email', EmailType::class, array(
                'label_attr' => array(
                    'class' => 'col-sm-2 control-label'),
                'label' => 'Email du reservant')
            )
			
            ->add('ticket', CollectionType::class, array(
                'entry_type'   => TicketsType::class,
                'allow_add'    => true,
                'allow_delete' => true,
				'label' => 'Visiteur du Musée'
            ))
            ->add('save',      SubmitType::class);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MUSEE\LouvreBundle\Entity\Orders'
        ));
    }
}

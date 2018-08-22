<?php

namespace AppBundle\Form;

use AppBundle\Entity\Command;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommandType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fullDay', ChoiceType::class, array(
                'label' => 'Type de billet : ',
                'choices' => array(
                    'Journée' => true,
                    'Demi-journée' => false,
                ),
                'expanded' => true,
                'multiple' => false))
            ->add('visitDate', DateType::class, array(
                    'label' => 'Date de visite : ',
                    'data' => new \DateTime()))
            ->add('numberOfTickets', IntegerType::class, array(
                    'label' => 'Nombre de billets : '))
            ->add('visitorEmail', EmailType::class, array(
                    'label' => 'Email : '))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array('data_class' => Command::class));
    }
}

<?php

namespace AppBundle\Form;

use AppBundle\Entity\Command;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
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
            ->add('ticketType', CheckboxType::class, array(
                'label' => 'Demi-journée ? ',
                'required' => false))
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

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'Commande_:';
    }
}

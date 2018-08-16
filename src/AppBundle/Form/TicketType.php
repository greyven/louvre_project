<?php

namespace AppBundle\Form;

use AppBundle\Entity\Ticket;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TicketType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lastName', TextType::class, array(
                'label' => 'Nom : '))
            ->add('firstName', TextType::class, array(
                'label' => 'Prénom : '))
            ->add('country', CountryType::class, array(
                'label' => 'Pays : ',
                'preferred_choices' => array('FR')))
            ->add('birthDate', BirthdayType::class, array(
                'label' => 'Date de naissance : ',
                'data' => new \DateTime("01-01-1990")))
            ->add('reducedPrice', CheckboxType::class, array(
                'label' => 'Tarif réduit ? ',
                'required' => false))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array('data_class' => Ticket::class));
    }
}
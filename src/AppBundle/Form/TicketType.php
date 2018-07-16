<?php

namespace AppBundle\Form;

use AppBundle\Entity\Ticket;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TicketType extends AbstractType
{
//    private $locale = 'fr';
//
//    public function __construct($locale = 'fr')
//    {
//        $this->locale = $locale;
//    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
//            ->add('costType', ChoiceType::class)
            ->add('lastName', TextType::class)
            ->add('firstName', TextType::class)
            ->add('country', CountryType::class, array(
                "preferred_choices" => array('fr' /*$this->locale*/)))
            ->add('birthDate', BirthdayType::class);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array('data_class' => Ticket::class));
    }

}
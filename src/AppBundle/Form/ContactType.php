<?php

namespace AppBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class ContactType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lastName', TextType::class, array(
                'label' => 'Nom : ',
                'required' => true))
            ->add('firstName', TextType::class, array(
                'label' => 'Prénom : ',
                'required' => true))
            ->add('visitorEmail', EmailType::class, array(
                'label' => 'Email : ',
                'required' => true))
            ->add('subject', TextType::class, array(
                'label' => 'Sujet : ',
                'required' => true))
            ->add('message', TextareaType::class, array(
                'label' => 'Message : ',
                'required' => true))
        ;
    }
}
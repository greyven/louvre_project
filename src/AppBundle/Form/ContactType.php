<?php

namespace AppBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Valid;

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
                'constraints' => [new NotBlank(), new Length(['min' => 1])],
                'required' => true))
            ->add('firstName', TextType::class, array(
                'label' => 'Prénom : ',
                'constraints' => [new NotBlank(), new Length(['min' => 1])],
                'required' => true))
            ->add('visitorEmail', EmailType::class, array(
                'label' => 'Email : ',
                'constraints' => [new NotBlank(), new Email(['message' => 'Veuillez entrer un format d\'email valide.'])],
                'required' => true))
            ->add('subject', TextType::class, array(
                'label' => 'Sujet : ',
                'constraints' => [new NotBlank(), new Length(['min' => 5])],
                'required' => true))
            ->add('message', TextareaType::class, array(
                'label' => 'Message : ',
                'constraints' => [new NotBlank(), new Length(['min' => 10])],
                'required' => true))
        ;
    }
}
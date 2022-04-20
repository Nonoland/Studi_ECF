<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('subject', ChoiceType::class, [
                'label' => 'Sujet de votre demande',
                'required' => true,
                'choices' => [
                    'Je souhaite poser une réclamation' => 'Je souhaite poser une réclamation',
                    'Je souhaite commander un service supplémentaire' => 'Je souhaite commander un service supplémentaire',
                    'Je souhaite en savoir plus sur une suite' => 'Je souhaite en savoir plus sur une suite',
                    'J’ai un souci avec cette application' => 'J’ai un souci avec cette application',
                ]
            ])
            ->add('message', TextareaType::class, [
                'label' => 'Votre message',
                'required' => true,
                'attr' => [
                    'rows' => 5
                ]
            ])
            ->add('sender', EmailType::class, [
                'label' => 'Votre email',
                'required' => true,
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Votre prénom',
                'required' => true
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Votre nom',
                'required' => true
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Envoyer le message'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}

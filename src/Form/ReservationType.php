<?php

namespace App\Form;

use App\Entity\Reservation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $now = new \DateTime();
        $later = new \DateTime();
        $later->add(new \DateInterval('P3D'));

        $builder
            ->add('date_start', DateType::class, [
                'label' => 'Date d\'arrivé',
                'required' => true,
                'widget' => 'choice',
                'html5' => true,
                'data' => $now
            ])
            ->add('date_end', DateType::class, [
                'label' => 'Date de retour',
                'required' => true,
                'widget' => 'choice',
                'html5' => true,
                'data' => $later
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Réserver la suite'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}

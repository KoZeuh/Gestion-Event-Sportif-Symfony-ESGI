<?php

namespace App\Form;

use App\Entity\Participant;
use App\Entity\Event;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ParticipantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                'label' => 'Nom du participant',

            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',

            ])
            ->add('event', EntityType::class, [
                'class' => Event::class,
                'choice_label' => 'name',
                'label' => 'Événement',
                'data' => $options['event'],
                'disabled' => true,
            ])
            
            ->add('longitude', NumberType::class, [
                'label' => 'Longitude',

            ])
            ->add('latitude', NumberType::class, [
                'label' => 'Latitude',

            ])
            ->add('validate', SubmitType::class, [
                'label' => 'Valider l\'inscription',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Participant::class,
            'event' => null,
        ]);
    }
    
}
